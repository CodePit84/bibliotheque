<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use App\Service\CallApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GoogleBooksController extends AbstractController
{
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
    public function indexSearch2(string $word, CallApiService $callApiService, Request $request, EntityManagerInterface $entityManager): Response
    {
        // dd($callApiService->getBooks($word));
        // dd($callApiService->getBooks($word)["items"]);

        $book = new Book();

        

        // $book->setTitle('popo');

        $book->setTitle($callApiService->getBooks($word)["items"][0]["volumeInfo"]["title"]);
        // $book->setReleaseDate($callApiService->getBooks($word)["items"][0]["volumeInfo"]["publishedDate"]);
        $book->setSummary($callApiService->getBooks($word)["items"][0]["volumeInfo"]["description"]);
        
        $form = $this->createForm(BookFormType::class, $book);

        // dd($form);

        
        
        $form->handleRequest($request);
        
        return $this->render('book/addBook.html.twig', [
            'data' => $callApiService->getBooks($word),
            'word' => $word,
            'bookForm' => $form->createView(),
        ]);
    }
}
