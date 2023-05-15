<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookFormType;
use App\Repository\AuthorRepository;
use App\Service\CallApiService;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GoogleBooksController extends AbstractController
{
    /**
     * Search Form Google Books for find 10 books or 1 book ref
     *
     * @param string $word
     * @param CallApiService $callApiService
     * @return Response
     */
    #[Route('/googleBooks/{word}', name: 'googleBooks')]
    public function indexSearch(string $word, CallApiService $callApiService): Response
    {
        // dd($callApiService->getBooks($word));
        // dd($callApiService->getBooks($word)["items"]);

        return $this->render('books_search/indexResult.html.twig', [
            'data' => $callApiService->getBooks($word),
            'word' => $word,
        ]);
    }

    #[Route('/googleBooks2/{word}', name: 'googleBooks2')]
    public function indexSearch2(string $word, CallApiService $callApiService, AuthorRepository $authorRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // dd($callApiService->getBooks($word));
        // dd($callApiService->getBooks($word)["items"]);

        $book = new Book();

        $book->setTitle($callApiService->getBooks($word)["items"][0]["volumeInfo"]["title"]);

        $date = $callApiService->getBooks($word)["items"][0]["volumeInfo"]["publishedDate"];
        $d = new \DateTime($date);
        $book->setReleaseDate($d);

        // Comme sur Google Books la clé de tableau "description" n'existe pas forcément alors setter uniquement si elle existe
        if (array_key_exists("description", $callApiService->getBooks($word)["items"][0]["volumeInfo"])) {
            $book->setSummary($callApiService->getBooks($word)["items"][0]["volumeInfo"]["description"]);
        }

        // $book->getAuthor($callApiService->getBooks($word)["items"][0]["volumeInfo"]["authors"][0]);
        
        // Récupération du nom de l'auteur from GOOGLE BOOKS
        $authorFromGoogle = $callApiService->getBooks($word)["items"][0]["volumeInfo"]["authors"][0];
        // dd($authorFromGoogle);

            // $book->addAuthor($authorRepository->addFromGoogle('emile zola'));
        
        // Si l'auteur figure dans l'AuthorRepository alors on ajoute l'ajoute dans le champs Auteur, sinon on laisse vide 
        if ($authorRepository->addFromGoogle($authorFromGoogle)) {
            $book->addAuthor($authorRepository->addFromGoogle($authorFromGoogle));
        }


        // $book->addAuthor($authorFind, Author $author) ;

        // dd($authorFind);
        // $book->addAuthor($authorFind) ; 
        
        // $book->addAuthor($authorFind) ;
        // dd($book->getAuthor($authorRepository->addFromGoogle('emile zola')));

        $form = $this->createForm(BookFormType::class, $book);

        // dd($form);
  
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $book = $form->getData();

            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Livre ajouté avec succès');

            return $this->redirectToRoute('book.index');
        }
        
        return $this->render('book/addBook.html.twig', [
            'data' => $callApiService->getBooks($word),
            'word' => $word,
            'bookForm' => $form->createView(),
        ]);
    }
}
