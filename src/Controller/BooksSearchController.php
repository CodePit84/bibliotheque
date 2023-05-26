<?php

namespace App\Controller;

use App\Form\BooksSearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BooksSearchController extends AbstractController
{
    /**
     * Allow us to find some books on Google API
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/search', name: 'searchGoogle')]
    public function search(Request $request): Response
    {  
        $search = null ;

        $form = $this->createForm(BooksSearchType::class, $search);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $search = $form->getData();

            $word = $search["search"];

            return $this->redirectToRoute('googleBooks', array('word' => $word));
        }
        
        return $this->render('books_search/index.html.twig', [
            'bookForm' => $form->createView(),
        ]);
    }
}

