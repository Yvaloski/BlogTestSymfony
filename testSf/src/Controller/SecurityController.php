<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\RegistrationType;

use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'security_registration')]
    public function registration(Request $request, EntityManagerInterface  $manager, UserPasswordHasherInterface $encoder)
    {
    
        $user = new User();
        $form = $this -> createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig',[
            'form' => $form->createView()
        ]);
        

    }
    #[Route('/connexion' , name:'security_login')]
    public function login(AuthenticationUtils $authenticationUtils)  {
        // get the login error if there is one

                $error = $authenticationUtils->getLastAuthenticationError();
        
                 // last username entered by the user
                $lastUsername = $authenticationUtils->getLastUsername();
                return $this->render('security/login.html.twig', [
                'SecurityController' => 'security_login',
                'last_username' => $lastUsername,
                'error'         => $error,
                
            ]);
        }

    #[Route('/deconnexion' , name:'security_logout')]
    public function logout(){}
    }

