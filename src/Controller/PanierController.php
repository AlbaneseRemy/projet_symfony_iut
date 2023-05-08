<?php

namespace App\Controller;

use App\Service\PanierService;

use App\Repository\ProduitRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/{_locale}/panier', name: 'app_panier_index')]
    public function index(PanierService $panierService): Response
    {

        $contenu_panier = $panierService->getContenu();
        $panier_total = $panierService->getTotal();
        $nb_produits = $panierService->getNombreProduits();

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'contenu_panier' => $contenu_panier,
            'panier_total' => $panier_total,
            'nb_produits' => $nb_produits,        
        ]);
    }



    #[Route('/{_locale}/panier/ajouter/{idProduit}/{quantite}', name: 'app_panier_ajouter', requirements:['_locale'=> '%app.supported_locales%', 'idProduit' => '\d+', 'quantite' => '\d+'])]
    public function ajouter(PanierService $panierService, ProduitRepository $produitRepository, int $idProduit, int $quantite): Response
    {

        $produit = $produitRepository->find($idProduit);
        if($produit != null){
            $panierService->ajouterProduit($idProduit, $quantite);
            return $this->redirectToRoute('app_panier_index');
        }else{
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }
    }

    #[Route(' /{_locale}/panier/enlever/{idProduit}/{quantite}', name: 'app_panier_enlever', requirements:['_locale'=> '%app.supported_locales%','idProduit' => '\d+'])]
    public function enlever(PanierService $panierService, int $idProduit, int $quantite): Response
    {
        $panierService->enleverProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route(' /{_locale}/panier/vider', name: 'app_panier_vider', requirements:['_locale'=> '%app.supported_locales%'])]
    public function enleverAll(PanierService $panierService): Response
    {
        $panierService->vider();
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/{_locale}/panier/supprimer/{idProduit}', name: 'app_panier_supprimer', requirements:['_locale'=> '%app.supported_locales%','idProduit' => '\d+'])]
    public function supprimer(PanierService $panierService, ProduitRepository $produitRepository, int $idProduit): Response
    {   
        $produit = $produitRepository->find($idProduit);
        if($produit != null){
            $panierService->supprimerProduit($idProduit);
            return $this->redirectToRoute('app_panier_index');
        }else{
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }
    }


    public function nombreProduits(PanierService $panierService): Response
    {
        return new Response($panierService->getNombreProduits());
    }


}
