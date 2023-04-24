<?php

namespace App\Controller;

use App\Entity\Copy;
use App\Entity\Borrow;
use App\Form\BorrowFormType;
use App\Form\BorrowEndFormType;
use App\Form\BorrowFromCopyFormType;
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
    public function addBorrow(Request $request, EntityManagerInterface $entityManager): Response
    {
        $borrow = new Borrow();
        
        $form = $this->createForm(BorrowFormType::class, $borrow);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $borrow = $form->getData();

            // On set le retour à false car nouvel emprunt
            $borrow->setReturned(false);

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

    #[Route('/borrow/addBorrowFromCopy/{id}', name: 'borrow.addBorrowFromCopy')]
    public function addBorrowFromCopy(Copy $copy, Request $request, EntityManagerInterface $entityManager): Response
    {
        $borrow = new Borrow();

        // On set l'exemplaire
        $borrow->setCopy($copy);
        
        $form = $this->createForm(BorrowFromCopyFormType::class, $borrow);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $borrow = $form->getData();

            // On set le retour à false car nouvel emprunt
            $borrow->setReturned(false);

            $entityManager->persist($borrow);
            $entityManager->flush();

            $this->addFlash('success', 'Emprunt ajouté avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('borrow.index');
        }
        
        return $this->render('borrow/addBorrowFromCopy.html.twig', [
            'borrowForm' => $form->createView(),
        ]);
    }

    #[Route('/borrow/edit/{id}', name: 'borrow.edit')]
    public function editBorrow(Borrow $borrow, Request $request, EntityManagerInterface $entityManager): Response
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

    // #[Route('/borrow/return/{id}', name: 'borrow.return')]
    // public function returnBook(Borrow $borrow, Request $request, EntityManagerInterface $entityManager): Response
    // {   
    //     $form = $this->createForm(BorrowEndFormType::class, $borrow);
    //     $form->handleRequest($request);

    //     // dd($form);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         // Passer le returned à true

    //         $entityManager->persist($borrow);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Retour de l\'emprunt enregistrer avec succès');

    //         // return $this->redirectToRoute('country.index', array('id' => $userId));
    //         return $this->redirectToRoute('borrow.index');
    //     }
        
    //     return $this->render('borrow/returnBorrow.html.twig', [
    //         'borrowEndForm' => $form->createView(),
    //         'borrow' => $borrow
    //     ]);
    // }

    #[Route('/borrow/return/{id}', name: 'borrow.return')]
    public function returnBook(Borrow $borrow, Request $request, EntityManagerInterface $entityManager): Response
    {   
        // On enregistre la date de retour d'aujourd'hui et on passer le returned à true
        // dump($borrow);
        $today = new \DateTimeImmutable();
        $borrow->setBorrowingEndDate($today);
        $borrow->setReturned(true);

        // dd($borrow);

        $entityManager->persist($borrow);
        $entityManager->flush();

        $this->addFlash('success', 'Retour de l\'emprunt enregistrer avec succès');

        // return $this->redirectToRoute('country.index', array('id' => $userId));
        return $this->redirectToRoute('borrow.index');    

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
