<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorFormType;
use App\Form\SearchBookFormType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'author.index')]
    public function index(AuthorRepository $authorRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $authors = $paginator->paginate(
            // Pour un ordre alphabétique :
            $authorRepository->findBy(array(), array('lastName' => 'ASC')),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // Recherche
        $form = $this->createForm(SearchBookFormType::class);

        $search = $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            // On recherche les auteurs correspondants aux mots clés
            $authors = $paginator->paginate(
                $authorRepository->search($search->get('words')->getData()),
                $request->query->getInt('page', 1), /*page number*/
                10 /*limit per page*/
            );
        }
        
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
            'form' => $form->createView()
        ]);
    }

    #[Route('/author/addAuthor/', name: 'author.add')]
    #[IsGranted('ROLE_ADMIN')]
    public function addAuthor(Request $request, EntityManagerInterface $entityManager): Response
    {
        $author = new Author();
        
        $form = $this->createForm(AuthorFormType::class, $author);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $author = $form->getData();

            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Auteur ajouté avec succès');

            return $this->redirectToRoute('author.index');
        }
        
        return $this->render('author/addAuthor.html.twig', [
            'authorForm' => $form->createView(),
        ]);
    }

    #[Route('/author/edit/{id}', name: 'author.edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editAuthor(Author $author, Request $request, EntityManagerInterface $entityManager): Response
    {   
        $form = $this->createForm(AuthorFormType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', 'Auteur modifié avec succès');

            return $this->redirectToRoute('author.index');
        }
        
        return $this->render('author/editAuthor.html.twig', [
            'authorForm' => $form->createView(),
            'author' => $author
        ]);
    }

    #[Route('/author/delete/{id}', name: 'author.delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Author $author, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($author);
        $entityManager->flush();

        $this->addFlash('success', 'Auteur supprimé avec succès');

        return $this->redirectToRoute('author.index');
    }
}
