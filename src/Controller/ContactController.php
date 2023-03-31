<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route(
        path: '/{_locale}/contact',
        name: 'app_contact',
        requirements: ['_locale' => '%app.supported_locales%'],
        defaults: ['_locale' => 'fr']
    )]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
