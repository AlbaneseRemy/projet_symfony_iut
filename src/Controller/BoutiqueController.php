<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use App\Service\PanierService;

class BoutiqueController extends AbstractController
{
    #[Route(
        path: '/{_locale}/boutique',
        name: 'app_boutique',
        requirements: ['_locale' => '%app.supported_locales%'],
        defaults: ['_locale' => 'fr']
    )]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        return $this->render('boutique/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route(
        path: '/{_locale}/rayon/{idCategorie}',
        name: 'app_boutique_rayon',
        requirements: ['_locale' => '%app.supported_locales%'],
        defaults: ['_locale' => 'fr']
    )]
    public function rayon(ProduitRepository $produitRepository, CategorieRepository $categorieRepository, int $idCategorie): Response
    {
        $categorie = $categorieRepository->find($idCategorie)->getLibelle();
        $produits = $produitRepository->findByCategorie($idCategorie);

        return $this->render('boutique/rayon.html.twig', [
            'categorie' => $categorie,
            'produits' => $produits,
        ]);
    }

    #[Route(path:'/{_locale}/boutique/chercher/{recherche}',
    name: 'app_boutique_recherche', 
    requirements:['_locale'=> '%app.supported_locales%', 'recherche' => ".+"], 
    defaults: ['_locale' => 'fr', 'recherche' => ""]
    )]
    public function recherche(ProduitRepository $produitRepository, string $recherche): Response
    {
    $recherche = urldecode($recherche);
    $produits = $produitRepository->findByLibelleOrTexte($recherche);

    return $this->render('boutique/chercher.html.twig', [
        'produits' => $produits,
        'recherche' => $recherche,
    ]);
}
}   