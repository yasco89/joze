<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RequestStack;





class RegistrationController extends AbstractController
{



  private $managerRegistry; // stock dans la bdd
  private $requestStack;
  public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack)
  {
    $this->managerRegistry = $managerRegistry;
    $this->requestStack = $requestStack;
  }

    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,ManagerRegistry $managerRegistry): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
          $errors=[];

        if ($form->isSubmitted() && $form->isValid()) {
          $safe = array_map('trim', array_map('strip_tags', $_POST['registration_form']));
          //Verif Username
          if (strlen($safe['username']) < 1 || strlen($safe['username']) > 20) {
              $errors[] = 'Votre pseudo doit contenir entre 1 et 20 caractères';

          }
          if (strlen($safe['name']) < 1 || strlen($safe['name']) > 20) {
              $errors[] = 'Votre nom doit contenir entre 1 et 20 caractères';

          }



          //Verif Mot de passe
              if (strlen($safe['plainPassword']) < 8 || strlen($safe['plainPassword']) > 20) {
                  $errors[] = 'Votre mot de passe doit contenir entre 8 et 20 caractères';

              }

          //Verif email
              if (strlen($safe['email']) < 10 || strlen($safe['email']) > 50) {
                  $errors[] = 'Votre email est trop long';
              } elseif (!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {
                  $errors[] = 'Cette adresse email est invalide';
              }



    if (count($errors) == 0) {
          $user->setRoles(['ROLE_USER']);
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
              $this->addFlash('success', 'Votre compte a bien été créé. Un mail de vérification vous a été envoyé. Veuillez confirmer votre compte afin de réserver vos places.');
              return $this->redirectToRoute('app_login');


        } else {
                $this->addFlash('errors', implode('<br>', $errors));
            }
              } else {
                if(!empty($_POST)){


            $safe = array_map('trim', array_map('strip_tags', $_POST['registration_form']));

            //Verif Username
            if (strlen($safe['username']) < 1 || strlen($safe['username']) > 20) {
                $errors[] = 'Votre pseudo doit contenir entre 1 et 20 caractères';

            }
            if (strlen($safe['name']) < 1 || strlen($safe['name']) > 20) {
                $errors[] = 'Votre nom doit contenir entre 1 et 20 caractères';

            }


                //Verif email
                if (strlen($safe['email']) < 10 || strlen($safe['email']) > 50) {
                    $errors[] = 'Votre email est trop long';
                } elseif (!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Cette adresse email est invalide';
                }

            //Verif Mot de passe
              //Verif Mot de passe
              if (strlen($safe['plainPassword']) < 8 || strlen($safe['plainPassword']) > 20) {
                $errors[] = 'Votre mot de passe doit contenir entre 8 et 20 caractères';

            }
            $this->addFlash('errors', implode('<br>', $errors));
        }

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);

    }


    #[Route('/liste_users', name: 'listusers')]
    #[IsGranted("ROLE_ADMIN")]
    public function list(): Response
    {
        $em = $this->managerRegistry->getManager();
        $users = $em->getRepository(User::class)->findBy([]);


        return $this->render('registration/list.html.twig', [
            'user' => $users,


        ]);
    }





}
