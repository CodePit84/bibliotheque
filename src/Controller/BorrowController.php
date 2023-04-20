<?php

namespace App\Controller;

use App\Entity\Borrow;
use App\Form\BorrowFormType;
use App\Repository\BorrowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BorrowController extends AbstractController
{
    #[Route('/borrow', name: 'borrow.index')]
    public function index(BorrowRepository $borrowRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $borrows = $paginator->paginate(
            // $countryRepository->findAll(),
            // Pour un ordre alphabétique :
            $borrowRepository->findBy(array(), array('id' => 'ASC')),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('borrow/index.html.twig', [
            'borrows' => $borrows,
        ]);
    }

    #[Route('/borrow/addBorrow/', name: 'borrow.addBorrow')]
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $borrow = new Borrow();
        
        $form = $this->createForm(BorrowFormType::class, $borrow);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $borrow = $form->getData();

            $entityManager->persist($borrow);
            $entityManager->flush();

            $this->addFlash('success', 'Emprunt ajouté avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('borrow.index');
        }
        
        return $this->render('borrow/addBorrow.html.twig', [
            'borrowForm' => $form->createView(),
        ]);
    }

    #[Route('/borrow/edit/{id}', name: 'borrow.edit')]
    public function editBook(Borrow $borrow, Request $request, EntityManagerInterface $entityManager): Response
    {   
        $form = $this->createForm(BorrowFormType::class, $borrow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($borrow);
            $entityManager->flush();

            $this->addFlash('success', 'Emprunt modifié avec succès');

            // return $this->redirectToRoute('country.index', array('id' => $userId));
            return $this->redirectToRoute('borrow.index');
        }
        
        return $this->render('borrow/editBorrow.html.twig', [
            'borrowForm' => $form->createView(),
            'borrow' => $borrow
        ]);
    }

    #[Route('/borrow/delete/{id}', name: 'borrow.delete')]
    public function delete(Borrow $borrow, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($borrow);
        $entityManager->flush();

        $this->addFlash('success', 'Emprunt supprimé avec succès');

        // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        return $this->redirectToRoute('borrow.index');
    }
}
