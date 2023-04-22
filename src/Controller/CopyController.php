<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Copy;
use App\Entity\Borrow;
use App\Form\CopyFormType;
use App\Form\BorrowFormType;
use App\Form\CopyFromBookFormType;
use App\Repository\BookRepository;
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

    #[Route('/copyOne/{id}', name: 'copyOne.index')]
    public function indexOne(Copy $copy, CopyRepository $copyRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $copyId = $copy->getId();
        
        $copies = $paginator->paginate(
            // $countryRepository->findAll(),
            // Pour un ordre alphabétique :
            $copyRepository->findBy(['id' => $copyId]),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        
        // $copies = $copyRepository->findBy(['id' => $copyId]);
        
        
        return $this->render('copy/index.html.twig', [
            'copies' => $copies,
        ]);
    }

    #[Route('/copy/addCopy/', name: 'copy.addCopy')]
    public function addCopy(Request $request, EntityManagerInterface $entityManager): Response
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

    #[Route('/copy/addCopyFromBook/{id}', name: 'copy.addCopyFromBook')]
    public function addCopyFromBook(Book $book, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        // $bookTitle = $book->getTitle();

        $copy = new Copy();
        
        $copy->setBook($book);

        // dd($copy);

        // $form = $this->createForm(CopyFromBookFormType::class, $copy);
        $form = $this->createForm(CopyFromBookFormType::class, $copy);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $copy = $form->getData();

            $copy->setBook($book);

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
