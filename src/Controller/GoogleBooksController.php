<?php

namespace App\Controller;

use App\Service\CallApiService;
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
}
