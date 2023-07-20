<?php

namespace App\Controller;

use App\Entity\Missions;
use App\Form\MissionsType;
use App\Repository\MissionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class MissionsController extends AbstractController
{
    #[Security("is_granted('ROLE_USER')")]
    #[Route('/mission/new', name: 'mission_new')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $mission = new Missions;
        $form = $this->createForm(MissionsType::class, $mission);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
        $mission = $form->getData();
        if($this->getUser()){
            $mission->setUser($this->getUser());
        }
        $em->persist($mission);
        $em->flush();
        if($this->getUser() && in_array('ROLE_EMP', $this->getuser()->getRoles())){
            return $this->redirectToRoute('user_mission');
        }
        return $this->redirectToRoute('mission_list');
        }
        return $this->render('missions/new.html.twig', [
        'form' => $form->createView()    
        ]);
    }

    // Seul l'employeur  peut modifier la mission qu'il a déposé ou l'admin
    #[Security("is_granted('ROLE_EMP') or is_granted('ROLE_ADMIN')")]
    #[Route('/mission/edit/{id}', name: 'mission_edit')]
    public function edit($id, EntityManagerInterface $em, Request $request, MissionsRepository $repo): Response
    {
        $mission = $repo->findOneBy(['id'=>$id]);
        $missionUserId = ($mission->getUser()->getId());
        $currentUserId = $this->getUser()->getId();

        // Rediriger à la page de connexion si la mission n'appartient pas à l'employeur et user n'est pas admin
        if($missionUserId !== $currentUserId &&  !in_array('ROLE_ADMIN', $this->getuser()->getRoles())){
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(MissionsType::class, $mission);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
        $mission = $form->getData();
        $em->persist($mission);
        $em->flush();
        if($this->getUser() && in_array('ROLE_EMP', $this->getuser()->getRoles())){
            return $this->redirectToRoute('user_mission');
        }
        return $this->redirectToRoute('mission_list');
        }
        return $this->render('missions/edit.html.twig', [
        'form' => $form->createView()    
        ]);
    }

    #[Security("is_granted('ROLE_USER')")]
    #[Route('/mission/list', name: 'mission_list')]
    public function list(MissionsRepository $repo): Response
    {
        $missions = $repo->findAll();

        return $this->render('missions/list.html.twig', [
        'missions' => $missions,
        ]);
    }

    #[Security("is_granted('ROLE_USER')")]
    #[Route('/mission/show/{id}', name: 'mission_show')]
    public function detail($id, MissionsRepository $repo): Response
    {
        $mission =  $repo->findOneBy(['id' => $id]);
        return $this->render('missions/show.html.twig', [
        'mission' => $mission
        ]);
    }

     // Seul l'employeur peut modifier la mission qu'il a déposé ou l'admin
     #[Security("is_granted('ROLE_EMP') or is_granted('ROLE_ADMIN')")]
    #[Route('/mission/delete/{id}', name: 'mission_delete')]
    public function remove($id, EntityManagerInterface $em, MissionsRepository $repo): Response
    {
        $mission =  $repo->findOneBy(['id' => $id]);
        $missionUserId = ($mission->getUser()->getId());
        $currentUserId = $this->getUser()->getId();

        // Rediriger à la page de connexion si la mission n'appartient pas à l'employeur et user n'est pas admin
        if($missionUserId !== $currentUserId &&  !in_array('ROLE_ADMIN', $this->getuser()->getRoles())){
            return $this->redirectToRoute('app_login');
        }
        $em->remove($mission);
        $em->flush();
        if($this->getUser() && in_array('ROLE_EMP', $this->getuser()->getRoles())){
            return $this->redirectToRoute('user_mission');
        }
        return $this->redirectToRoute('mission_list');
    }
}

