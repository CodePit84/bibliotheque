<?php

namespace App\Controller;

use App\Entity\Publishing;
use App\Form\BookFormType;
use App\Entity\Subscription;
use App\Form\PublishingFormType;
use App\Form\SubscriptionFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubscriptionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriptionController extends AbstractController
{
    #[Route('/subscription', name: 'subscription.index')]
    public function index(SubscriptionRepository $subscriptionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $subscriptions = $paginator->paginate(
            // $countryRepository->findAll(),
            // Pour un ordre alphabétique :
            $subscriptionRepository->findBy(array(), array('id' => 'ASC')),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('subscription/index.html.twig', [
            'subscriptions' => $subscriptions,
        ]);
    }

    #[Route('/subscription/addSubscription/', name: 'subscription.addSubscription')]
    public function addPublishing(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subscription = new Subscription();
        
        $form = $this->createForm(SubscriptionFormType::class, $subscription);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $subscription = $form->getData();
            dd($subscription);



            $entityManager->persist($subscription);
            $entityManager->flush();

            $this->addFlash('success', 'Abonnement ajouté avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('subscription.index');
        }
        
        return $this->render('subscription/addSubscription.html.twig', [
            'subscriptionForm' => $form->createView(),
        ]);
    }

    #[Route('/subscription/edit/{id}', name: 'subscription.edit')]
    public function editSubscription(Subscription $subscription, Request $request, EntityManagerInterface $entityManager): Response
    {   
        $form = $this->createForm(SubscriptionFormType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subscription);
            $entityManager->flush();

            $this->addFlash('success', 'Abonnement modifié avec succès');

            // return $this->redirectToRoute('country.index', array('id' => $userId));
            return $this->redirectToRoute('subscription.index');
        }
        
        return $this->render('subscription/editSubscription.html.twig', [
            'subscriptionForm' => $form->createView(),
            'subscription' => $subscription
        ]);
    }

    #[Route('/subscription/delete/{id}', name: 'subscription.delete')]
    public function delete(Subscription $subscription, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($subscription);
        $entityManager->flush();

        $this->addFlash('success', 'Abonnement supprimé avec succès');

        // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        return $this->redirectToRoute('subscription.index');
    }
}
