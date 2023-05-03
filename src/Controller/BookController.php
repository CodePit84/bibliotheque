<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use App\Form\SearchBookFormType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookController extends AbstractController
{
    #[Route('/book', name: 'book.index')]
    public function index(BookRepository $bookRepository, AuthorRepository $authorRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $books = $paginator->paginate(
            // $countryRepository->findAll(),
            // Pour un ordre alphabétique :
            $bookRepository->findBy(array(), array('title' => 'ASC')),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // Recherche
        $form = $this->createForm(SearchBookFormType::class);

        $search = $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            // On recherche les livres correspondants aux mots clés
            // $books = $bookRepository->search($search->get('words')->getData());
            $books = $paginator->paginate(
                $bookRepository->search($search->get('words')->getData()),
                $request->query->getInt('page', 1), /*page number*/
                10 /*limit per page*/
            );
        }
        
        
        
        return $this->render('book/index.html.twig', [
            'books' => $books,
            'form' => $form->createView()
        ]);
    }

    #[Route('/book/addBook/', name: 'book.addBook')]
    #[IsGranted('ROLE_ADMIN')]
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        
        $form = $this->createForm(BookFormType::class, $book);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $book = $form->getData();

            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Livre ajouté avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('book.index');
        }
        
        return $this->render('book/addBook.html.twig', [
            'bookForm' => $form->createView(),
        ]);
    }

    #[Route('/book/edit/{id}', name: 'book.edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editBook(Book $book, Request $request, EntityManagerInterface $entityManager): Response
    {   
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Livre modifié avec succès');

            // return $this->redirectToRoute('country.index', array('id' => $userId));
            return $this->redirectToRoute('book.index');
        }
        
        return $this->render('book/editBook.html.twig', [
            'bookForm' => $form->createView(),
            'book' => $book
        ]);
    }

    #[Route('/book/delete/{id}', name: 'book.delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Book $book, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('success', 'Livre supprimé avec succès');

        // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        return $this->redirectToRoute('book.index');
    }
}
