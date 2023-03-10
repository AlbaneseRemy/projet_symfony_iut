<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BoutiqueService; 


class BoutiqueController extends AbstractController
{
    #[Route(
        path:'/boutique', 
        name: 'app_boutique',
        )]
    public function index(BoutiqueService $boutique): Response
    {
        $categories = $boutique->findAllCategories();
        return $this->render('boutique/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route(
        path: '/rayon/{idCategorie}', 
        name: 'app_boutique_rayon',
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
}
