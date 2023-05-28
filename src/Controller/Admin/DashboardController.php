<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Borrow;
use App\Entity\Copy;
use App\Entity\Country;
use App\Entity\Gender;
use App\Entity\RegisteredUser;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bibliotheque - Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs App', 'fa-solid fa-user', User::class);
        yield MenuItem::linkToCrud('Auteurs', 'fa-solid fa-pen-nib', Author::class);
        yield MenuItem::linkToCrud('Livres', 'fa-solid fa-book', Book::class);
        yield MenuItem::linkToCrud('Emprunts', 'fa-solid fa-arrow-right-from-bracket', Borrow::class);
        yield MenuItem::linkToCrud('Exemplaires', 'fa-solid fa-copy', Copy::class);
        yield MenuItem::linkToCrud('Pays d\'origine', 'fa-solid fa-flag', Country::class);
        yield MenuItem::linkToCrud('Genre littéraire', 'fa-solid fa-star', Gender::class);
        yield MenuItem::linkToCrud('Abonnés', 'fa-regular fa-user', RegisteredUser::class);
    }
}
