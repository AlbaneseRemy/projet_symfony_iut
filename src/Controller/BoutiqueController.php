<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BoutiqueService; 


class BoutiqueController extends AbstractController
{
    #[Route(
        path: '/{_locale}/boutique',
        name: 'app_boutique',
        requirements: ['_locale' => '%app.supported_locales%'],
        defaults: ['_locale' => 'fr']
    )]
    public function index(BoutiqueService $boutique): Response
    {
        $categories = $boutique->findAllCategories();
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
    public function rayon(BoutiqueService $boutiqueService, int $idCategorie): Response
    {
        $categorie = $boutiqueService->findCategorieById($idCategorie);
        $produits = $boutiqueService->findProduitsByCategorie($idCategorie);
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
    public function recherche(BoutiqueService $boutique, string $recherche): Response
    {
    $produits = $boutique->findProduitsByLibelleOrTexte($recherche);
    return $this->render('boutique/chercher.html.twig', [
        'produits' => $produits,
        'recherche' => $recherche,
    ]);
}
}