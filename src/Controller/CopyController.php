<?php

namespace App\Controller;

use App\Entity\Copy;
use App\Entity\Borrow;
use App\Form\CopyFormType;
use App\Form\BorrowFormType;
use App\Repository\CopyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CopyController extends AbstractController
{
    #[Route('/copy', name: 'copy.index')]
    public function index(CopyRepository $copyRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $copies = $paginator->paginate(
            // $countryRepository->findAll(),
            // Pour un ordre alphabétique :
            $copyRepository->findBy(array(), array('reference' => 'ASC')),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        
        return $this->render('copy/index.html.twig', [
            'copies' => $copies,
        ]);
    }

    #[Route('/copy/addCopy/', name: 'copy.addCopy')]
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $copy = new Copy();
        
        $form = $this->createForm(CopyFormType::class, $copy);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $copy = $form->getData();

            $entityManager->persist($copy);
            $entityManager->flush();

            $this->addFlash('success', 'Exemplaire ajouté avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('copy.index');
        }
        
        return $this->render('copy/addCopy.html.twig', [
            'copyForm' => $form->createView(),
        ]);
    }

    #[Route('/copy/edit/{id}', name: 'copy.edit')]
    public function editBook(Copy $copy, Request $request, EntityManagerInterface $entityManager): Response
    {   
        $form = $this->createForm(CopyFormType::class, $copy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($copy);
            $entityManager->flush();

            $this->addFlash('success', 'Exemplaire modifié avec succès');

            // return $this->redirectToRoute('country.index', array('id' => $userId));
            return $this->redirectToRoute('copy.index');
        }
        
        return $this->render('copy/editCopy.html.twig', [
            'copyForm' => $form->createView(),
            'copy' => $copy
        ]);
    }

    #[Route('/copy/delete/{id}', name: 'copy.delete')]
    public function delete(Copy $copy, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($copy);
        $entityManager->flush();

        $this->addFlash('success', 'Exemplaire supprimé avec succès');

        // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        return $this->redirectToRoute('copy.index');
    }
}
