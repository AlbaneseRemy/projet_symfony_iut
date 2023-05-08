<?php
namespace App\Service;

use App\Repository\ProduitRepository;

use Symfony\Component\HttpFoundation\RequestStack;

// Service pour manipuler le panier et le stocker en session
class PanierService
{
    ////////////////////////////////////////////////////////////////////////////
    private $session;   // Le service session
    private $panier;    // Tableau associatif, la clé est un idProduit, la valeur associée est une quantité
                        //   donc $this->panier[$idProduit] = quantité du produit dont l'id = $idProduit
    private $produit;
    const PANIER_SESSION = 'panier'; // Le nom de la variable de session pour faire persister $this->panier

    // Constructeur du service
    public function __construct(RequestStack $requestStack,  ProduitRepository $produitRepository)
    {
        // Récupération des services session et BoutiqueService
        $this->produit = $produitRepository;
        $this->session = $requestStack->getSession();
        // Récupération du panier en session s'il existe, init. à vide sinon
        $this->panier = $this->session->get(self::PANIER_SESSION, []);
    }

    // Renvoie le montant total du panier
    public function getTotal() : float
    {
      $prixTotal = 0;
      foreach ($this->panier as $idProduit => $quantite) {
        $produit = $this->produit->find($idProduit);
        $prixTotal += $produit->getPrix() * $quantite;
      }
      return $prixTotal;
    }

    // Renvoie le nombre de produits dans le panier
    public function getNombreProduits() : int
    {
      $totalProduit = 0;
      foreach ($this->panier as $idProduit => $quantite) {
        $totalProduit += $quantite;
      }
      return $totalProduit;
    }

    // Ajouter au panier le produit $idProduit en quantite $quantite 
    public function ajouterProduit(int $idProduit, int $quantite = 1) : void
    {
      if (array_key_exists($idProduit, $this->panier)) {
        $this->panier[$idProduit] += $quantite;
      } else {
        $this->panier[$idProduit] = $quantite;
      }
      $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    // Enlever du panier le produit $idProduit en quantite $quantite 
    public function enleverProduit(int $idProduit, int $quantite = 1) : void
    {
      if (array_key_exists($idProduit, $this->panier)) {
        $this->panier[$idProduit] -= $quantite;
        if ($this->panier[$idProduit] <= 0) {
          unset($this->panier[$idProduit]);
        }
      }
      $this->session->set(self::PANIER_SESSION, $this->panier);    
    }

    // Supprimer le produit $idProduit du panier  
    public function supprimerProduit(int $idProduit) : void
    {
      if (array_key_exists($idProduit, $this->panier)) {
        unset($this->panier[$idProduit]);
      }
      $this->session->set(self::PANIER_SESSION, $this->panier); 
    }

    // Vider complètement le panier
    public function vider() : void
    {
      $this->panier = [];
      $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    // Renvoie le contenu du panier dans le but de l'afficher
    //   => un tableau d'éléments [ "produit" => un objet produit, "quantite" => sa quantite ]
    public function getContenu() : array
    {
      $contenu = [];
      foreach ($this->panier as $idProduit => $quantite) {
        $produit = $this->produit->find($idProduit);
        $contenu[] = [ "produit" => $produit, "quantite" => $quantite ];
      }
      return $contenu;
    }

}