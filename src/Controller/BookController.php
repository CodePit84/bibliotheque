<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/book', name: 'book')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        
        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/book/addBook/', name: 'book.addBook')]
    public function addWithdraw(Request $request, EntityManagerInterface $entityManager): Response
    {
        $movement = new Book();
        
        $form = $this->createForm(BookFormType::class, $movement);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $movement = $form->getData();

            $entityManager->persist($movement);
            $entityManager->flush();

            $this->addFlash('success', 'Livre ajouté avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('app_book');
        }
        
        return $this->render('book/addBook.html.twig', [
            'bookForm' => $form->createView(),
        ]);
    }
}
