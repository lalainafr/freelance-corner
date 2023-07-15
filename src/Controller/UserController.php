<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/profile', name: 'user_profile')]
    public function index(UserRepository $repo): Response
    {
        $connectedUserId = $this->getuser()->getId();
        $user = $repo->findOneBy(['id' => $connectedUserId]);
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/user/mission', name: 'user_mission')]
    public function mission(UserRepository $repo): Response
    {
        $connectedUserId = $this->getuser()->getId();
        $user = $repo->findOneBy(['id' => $connectedUserId]);
        $missions = $user->getMissions();
        return $this->render('user/missions.html.twig', [
            'missions' => $missions,
        ]);
    }
}
