<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_registration')]
    public function registration(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plain_pwd = $form->getData()->getPlainPassword();
            // hasher le mot de passe pour raison de sécurité
            $hash = $hasher->hashPassword($user, $plain_pwd);
            $form->getData()->setPassword($hash);
            // securité supplementaire pour inviter les attaques xss et les injections sql
            $fullName = $form->getData()->getFullName();
            $form->getData()->setFullName(htmlentities($fullName));
            $em->persist($form->getData());
            $em->flush();
            $this->addFlash('success', 'Votre compte a bien été crée. ');

            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
