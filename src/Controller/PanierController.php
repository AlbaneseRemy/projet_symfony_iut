<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route(
        path: '/{_locale}/panier',
        name: 'app_panier',
        requirements: ['_locale' => '%app.supported_locales%'],
        defaults: ['_locale' => 'fr']
    )]    public function index(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }


}
