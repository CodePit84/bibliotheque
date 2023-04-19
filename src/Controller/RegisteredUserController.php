<?php

namespace App\Controller;

use DateInterval;
use App\Form\AuthorFormType;
use App\Entity\RegisteredUser;
use App\Form\RegisteredUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\RegisteredUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisteredUserController extends AbstractController
{
    #[Route('/registered/user', name: 'registeredUser.index')]
    public function index(RegisteredUserRepository $registeredUserRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $registeredUsers = $paginator->paginate(
            // $countryRepository->findAll(),
            // Pour un ordre alphabétique :
            $registeredUserRepository->findBy(array(), array('lastName' => 'ASC')),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('registered_user/index.html.twig', [
            'registeredUsers' => $registeredUsers,
        ]);
        
    }

    #[Route('/registeredUser/addRegisteredUser/', name: 'registeredUser.addRegisteredUser')]
    public function addRegisteredUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $registeredUser = new RegisteredUser();
        
        $form = $this->createForm(RegisteredUserFormType::class, $registeredUser);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $registeredUser = $form->getData();

            // On clone la date de début pour créer la date de fin + 1 AN
            $subscriptionStartDate = $form->getData()->getSubscriptionStartDate();
            $subscriptionEndDate = clone $subscriptionStartDate;
            $subscriptionEndDate = $subscriptionEndDate->add(new DateInterval('P1Y'));

            $registeredUser->setSubscriptionEndDate($subscriptionEndDate);
            
            // dump($subscriptionEndDate);
            // dd($registeredUser);

            $entityManager->persist($registeredUser);
            $entityManager->flush();

            $this->addFlash('success', 'Abonné ajouté avec succès');

            // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
            return $this->redirectToRoute('registeredUser.index');
        }
        
        return $this->render('registered_user/addRegisteredUser.html.twig', [
            'registeredUserForm' => $form->createView(),
        ]);
    }

    #[Route('/registeredUser/edit/{id}', name: 'registeredUser.edit')]
    public function editregisteredUser(RegisteredUser $registeredUser, Request $request, EntityManagerInterface $entityManager): Response
    {   
        $form = $this->createForm(AuthorFormType::class, $registeredUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($registeredUser);
            $entityManager->flush();

            $this->addFlash('success', 'Abonné modifié avec succès');

            // return $this->redirectToRoute('country.index', array('id' => $userId));
            return $this->redirectToRoute('registeredUser.index');
        }
        
        return $this->render('registeredUser/editRegisteredUser.html.twig', [
            'registeredUserForm' => $form->createView(),
            'registeredUser' => $registeredUser
        ]);
    }

    #[Route('/registeredUser/delete/{id}', name: 'registeredUser.delete')]
    public function delete(RegisteredUser $registeredUser, EntityManagerInterface $entityManager):Response
    {
        $entityManager->remove($registeredUser);
        $entityManager->flush();

        $this->addFlash('success', 'Auteur supprimé avec succès');

        // return $this->redirectToRoute('app_movement_user', array('id' => $userId));
        return $this->redirectToRoute('registeredUser.index');
    }
}
