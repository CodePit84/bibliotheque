<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\BookFormType;
use App\Form\CountryFormType;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CountryController extends AbstractController
{
    #[Route('/country', name: 'country.index')]
    public function index(CountryRepository $countryRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $countries = $paginator->paginate(
            // $countryRepository->findAll(),
            // Pour un ordre alphabétique :
            $countryRepository->findBy(array(), array('name' => 'ASC')),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('country/index.html.twig', [
            'countries' => $countries,
        ]);
    }

    #[Route('/country/addCountry/', name: 'country.add')]
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $country = new Country();
        
        $form = $this->createForm(CountryFormType::class, $country);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $country = $form->getData();

            $entityManager->persist($country);
            $entityManager->flush();

            $this->addFlash('success', 'Pays ajouté avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('country.index');
        }
        
        return $this->render('country/addCountry.html.twig', [
            'countryForm' => $form->createView(),
        ]);
    }
}
