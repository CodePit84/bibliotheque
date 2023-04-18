<?php

namespace App\Controller;

use App\Entity\Gender;
use App\Form\GenderFormType;
use App\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenderController extends AbstractController
{
    #[Route('/gender', name: 'gender.index')]
    public function index(GenderRepository $genderRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $genders = $paginator->paginate(
            // $countryRepository->findAll(),
            // Pour un ordre alphabétique :
            $genderRepository->findBy(array(), array('name' => 'ASC')),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('gender/index.html.twig', [
            'genders' => $genders,
        ]);
    }

    #[Route('/gender/addGender/', name: 'gender.addGender')]
    public function addGender(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gender = new Gender();
        
        $form = $this->createForm(GenderFormType::class, $gender);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $gender = $form->getData();

            $entityManager->persist($gender);
            $entityManager->flush();

            $this->addFlash('success', 'Genre ajouté avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('gender.index');
        }
        
        return $this->render('gender/addGender.html.twig', [
            'genderForm' => $form->createView(),
        ]);
    }

    #[Route('/gender/edit/{id}', name: 'gender.edit')]
    public function editGender(Gender $gender, Request $request, EntityManagerInterface $entityManager): Response
    {   
        $form = $this->createForm(GenderFormType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gender);
            $entityManager->flush();

            $this->addFlash('success', 'Genre modifié avec succès');

            // return $this->redirectToRoute('country.index', array('id' => $userId));
            return $this->redirectToRoute('gender.index');
        }
        
        return $this->render('gender/editGender.html.twig', [
            'genderForm' => $form->createView(),
            'gender' => $gender
        ]);
    }

    #[Route('/gender/delete/{id}', name: 'gender.delete')]
    public function delete(Gender $gender, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($gender);
        $entityManager->flush();

        $this->addFlash('success', 'Genre supprimé avec succès');

        // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        return $this->redirectToRoute('gender.index');
    }
}
