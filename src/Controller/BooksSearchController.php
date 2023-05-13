<?php

namespace App\Controller;

use App\Form\BooksSearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BooksSearchController extends AbstractController
{
    // #[Route('/books/search', name: 'app_books_search')]
    // public function index(): Response
    // {
    //     return $this->render('books_search/index.html.twig', [
    //         'controller_name' => 'BooksSearchController',
    //     ]);
    // }

    #[Route('/search', name: 'searchGoogle')]
    public function search(Request $request): Response
    {  
        $search = null ;

        $form = $this->createForm(BooksSearchType::class, $search);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $search = $form->getData();

            $word = $search["search"];

            // dd($word);

            // dd($search);

            return $this->redirectToRoute('googleBooks', array('word' => $word));
        }
        
        return $this->render('books_search/index.html.twig', [
            'bookForm' => $form->createView(),
        ]);
    }
}

