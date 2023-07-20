<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class MessageController extends AbstractController
{
    #[Route('/message/new', name: 'message_new')]
    public function new(EntityManagerInterface $em, Request $request): Response
    {

        $message = new Message;
        if ($this->getUser()) {
            $message->setFullname($this->getUser()->getFullName());
            $message->setEmail($this->getUser()->getEmail());
            $message->setPhone($this->getUser()->getPhone());
        }
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            if ($this->getUser()) {
                $message->setUser($this->getUser());
            }
            $em->persist($message);
            $em->flush();
            if($this->getUser()){
                return $this->redirectToRoute('user_profile');
            }
            return $this->redirectToRoute('app_main');
        }
        return $this->render('message/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // Seul l'admin peut modifier les messages des utilisateur
    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/message/edit/{id}', name: 'message_edit')]
    public function edit($id, EntityManagerInterface $em, Request $request, MessageRepository $repo): Response
    {
        $message = $repo->findOneBy(['id' => $id]);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $em->persist($message);
            $em->flush();
            return $this->redirectToRoute('message_list');
        }
        return $this->render('message/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/message/list', name: 'message_list')]
    public function list(MessageRepository $repo): Response
    {
        $message = $repo->findAll();

        return $this->render('message/list.html.twig', [
            'messages' => $message,
        ]);
    }

    #[Security("is_granted('ROLE_USER')")]
    #[Route('/message/show/{id}', name: 'message_show')]
    public function detail($id, MessageRepository $repo): Response
    {
        $message =  $repo->findOneBy(['id' => $id]);
        return $this->render('message/show.html.twig', [
        'message' => $message,
        ]);
    }

    // Seul l'admin peut supprimer les messages
    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/message/delete/{id}', name: 'message_delete')]
    public function remove($id, EntityManagerInterface $em, MessageRepository $repo): Response
    {
        $message =  $repo->findOneBy(['id' => $id]);
        $em->remove($message);
        $em->flush();
        return $this->redirectToRoute('message_list');
    }
}
