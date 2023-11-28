<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;




class SecurityController extends AbstractController
{

    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
          'last_username' => $lastUsername,
         'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/modifier-mot-de-passe', name: 'modifiermotspasse')]

       public function modifierMotDePasse(Request $request): Response
       {
           $utilisateur = $this->getUser(); // Vous pouvez obtenir l'utilisateur connecté ici

           if (!$utilisateur) {
               throw $this->createAccessDeniedException('Vous devez être connecté pour modifier le mot de passe.');
           }

           if ($request->isMethod('POST')) {
               $ancienMotDePasse = $request->request->get('ancien_mot_de_passe');
               $nouveauMotDePasse = $request->request->get('nouveau_mot_de_passe');

               if ($passwordEncoder->isPasswordValid($utilisateur, $ancienMotDePasse)) {
                   $nouveauMotDePasseCrypte = $passwordEncoder->encodePassword($utilisateur, $nouveauMotDePasse);
                   $utilisateur->setMotDePasse($nouveauMotDePasseCrypte);

                   $entityManager = $this->getDoctrine()->getManager();
                   $entityManager->persist($utilisateur);
                   $entityManager->flush();

                   $this->addFlash('success', 'Mot de passe modifié avec succès.');
               } else {
                   $this->addFlash('error', 'Ancien mot de passe incorrect.');
               }
           }

       return $this->render('security/modifier_mot_de_passe.html.twig', [


       ]);
}
}
