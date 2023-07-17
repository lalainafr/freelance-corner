<?php

namespace App\Controller\Admin;

use App\Entity\Missions;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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
            ->setTitle('Freelance  - CORNER');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::subMenu('TABLEAU DE BROD')->setSubItems([
            MenuItem::linkToCrud('Utilisateur', 'fa fa-user-o', User::class),
            MenuItem::linkToCrud('Mission', 'fas fa-tasks', Missions::class),
        ]);

    }
}
