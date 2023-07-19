<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    // C R U D

    // Seul l'admin peut créer des utilisateurs
    #[Route('/user/new', name: 'user_new')]
    public function new(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $plainPassword = $user->getPassword();
            $hashedPassword = $hasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Seul l'admin peut modifier les utilisateurs
    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/user/edit/{id}', name: 'user_edit')]
    public function edit($id, EntityManagerInterface $em, Request $request, UserRepository $repo): Response
    {
        $user = $repo->findOneBy(['id' => $id]);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Seul l'admin peut voir la liste des utilisateurs
    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/user/list', name: 'user_list')]
    public function list(UserRepository $repo): Response
    {
        $user = $repo->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $user,
        ]);
    }

    // Seul l'admin peut supprimer les utilisateurs
    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function remove($id, EntityManagerInterface $em, UserRepository $repo): Response
    {
        $user =  $repo->findOneBy(['id' => $id]);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('user_list');
    }
}
