<?php

namespace App\Controller;

use App\Entity\Publishing;
use App\Form\BookFormType;
use App\Form\PublishingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PublishingController extends AbstractController
{
    #[Route('/publishing', name: 'publishing.index')]
    public function index(): Response
    {
        return $this->render('publishing/index.html.twig', [
            'controller_name' => 'PublishingController',
        ]);
    }

    #[Route('/publishing/addPublishing/', name: 'publishing.addPublishing')]
    public function addPublishing(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publishing = new Publishing();
        
        $form = $this->createForm(PublishingFormType::class, $publishing);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $book = $form->getData();

            $entityManager->persist($publishing);
            $entityManager->flush();

            $this->addFlash('success', 'Edition ajoutée avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('publishing.index');
        }
        
        return $this->render('publishing/addPublishing.html.twig', [
            'publishingForm' => $form->createView(),
        ]);
    }

    #[Route('/publishing/edit/{id}', name: 'publishing.edit')]
    public function editBook(Publishing $publishing, Request $request, EntityManagerInterface $entityManager): Response
    {   
        $form = $this->createForm(PublishingFormType::class, $publishing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publishing);
            $entityManager->flush();

            $this->addFlash('success', 'Edition modifiée avec succès');

            // return $this->redirectToRoute('country.index', array('id' => $userId));
            return $this->redirectToRoute('publishing.index');
        }
        
        return $this->render('publishing/editPublishing.html.twig', [
            'publishingForm' => $form->createView(),
            'publishing' => $publishing
        ]);
    }

    #[Route('/publishing/delete/{id}', name: 'publishing.delete')]
    public function delete(Publishing $publishing, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($publishing);
        $entityManager->flush();

        $this->addFlash('success', 'Edition supprimée avec succès');

        // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        return $this->redirectToRoute('publishing.index');
    }
}
