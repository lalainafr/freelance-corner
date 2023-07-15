<?php

namespace App\Controller;

use App\Entity\Missions;
use App\Form\MissionsType;
use App\Repository\MissionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MissionsController extends AbstractController
{
    
    #[Route('/mission/new', name: 'mission_new')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $mission = new Missions;
        $form = $this->createForm(MissionsType::class, $mission);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
        $mission = $form->getData();
        $em->persist($mission);
        $em->flush();
        return $this->redirectToRoute('mission_list');
        }
        return $this->render('missions/new.html.twig', [
        'form' => $form->createView()    
        ]);
    }

    #[Route('/mission/edit/{id}', name: 'mission_edit')]
    public function edit($id, EntityManagerInterface $em, Request $request, MissionsRepository $repo): Response
    {
        $mission = $repo->findOneBy(['id'=>$id]);
        $form = $this->createForm(MissionsType::class, $mission);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
        $mission = $form->getData();
        $em->persist($mission);
        $em->flush();
        return $this->redirectToRoute('mission_list');
        }
        return $this->render('missions/edit.html.twig', [
        'form' => $form->createView()    
        ]);
    }

    #[Route('/mission/list', name: 'mission_list')]
    public function list(MissionsRepository $repo): Response
    {
        $missions = $repo->findAll();

        return $this->render('missions/list.html.twig', [
        'missions' => $missions,
        ]);
    }

    #[Route('/mission/delete/{id}', name: 'mission_delete')]
    public function remove($id, EntityManagerInterface $em, MissionsRepository $repo): Response
    {
        $mission =  $repo->findOneBy(['id' => $id]);
        $em->remove($mission);
        $em->flush();

        return $this->redirectToRoute('mission_list');
    }
}

