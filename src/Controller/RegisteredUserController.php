<?php

namespace App\Controller;

use App\Entity\RegisteredUser;
use App\Form\RegisteredUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisteredUserController extends AbstractController
{
    #[Route('/registered/user', name: 'app_registered_user')]
    public function index(): Response
    {
        return $this->render('registered_user/index.html.twig', [
            'controller_name' => 'RegisteredUserController',
        ]);
    }

    #[Route('/registeredUser/addRegisteredUser/', name: 'registeredUser.add')]
    public function addRegisteredUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $registeredUser = new RegisteredUser();
        
        $form = $this->createForm(RegisteredUserFormType::class, $registeredUser);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $registeredUser = $form->getData();

            $entityManager->persist($registeredUser);
            $entityManager->flush();

            $this->addFlash('success', 'Abonné ajouté avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('registeredUser.index');
        }
        
        return $this->render('registered_user/addRegisteredUser.html.twig', [
            'registeredUserForm' => $form->createView(),
        ]);
    }
}
