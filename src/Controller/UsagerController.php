<?php

namespace App\Controller;

use App\Entity\Usager;
use App\Form\UsagerType;
use App\Repository\UsagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/usager')]
class UsagerController extends AbstractController
{
    #[Route('/{_locale}/', name: 'app_usager_index', methods: ['GET'])]
    public function index(UsagerRepository $usagerRepository): Response
    {
        return $this->render('usager/index.html.twig', [
            'usager' => $usagerRepository->find(1),
        ]);
    }

    #[Route('/{_locale}/new', name: 'app_usager_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UsagerRepository $usagerRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usager = new Usager();
        $form = $this->createForm(UsagerType::class, $usager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usagerRepository->save($usager, true);
            
            $hashedPassword = $passwordHasher->hashPassword($usager, $usager->getPassword()); 
            $usager->setPassword($hashedPassword); 
            $usager->setRoles(["ROLE_CLIENT"]); 

            return $this->redirectToRoute('app_usager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usager/new.html.twig', [
            'usager' => $usager,
            'form' => $form,
        ]);
    }

}
