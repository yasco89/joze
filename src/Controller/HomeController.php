<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\ReservRDV;
use App\Entity\RendezVous;
use App\Entity\Product;
use App\Entity\Habitat;
use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Actualites;
use App\Entity\Reservation;

use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Kernelbalcon;

class HomeController extends AbstractController
{

  private $managerRegistry; // stock dans la bdd
  private $requestStack;
  public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack)
  {
    $this->managerRegistry = $managerRegistry;
    $this->requestStack = $requestStack;
  }


    public $famille = [
      'Coupe-Homme'  => 'Coupe-Homme',
      'Coupe-Femme'  => 'Coupe-Femme',
      'Coupe-Enfant'  => 'Coupe-Enfant',
      'Soins'  => 'Soins',
    ];


    public $type = [
      'lissage'  => 'lissage',
      'créme'  => 'créme',
      'defrisage'  => 'defrisage',
      'perruques'  => 'perruques',
      'autre'  => 'autre',
    ];

    public $prestation = [
      'Coupe-Homme'  => 'Coupe-Homme',
      'Coupe-Femme'  => 'Coupe-Femme',
      'Coupe-Enfant'  => 'Coupe-Enfant',
      'Soins'  => 'Soins',

    ];
    public $lieu = [
      'Salon'  => 'Salon',
      'Domicile'  => 'Domicile',
    ];


    #[Route('home', name: 'home')]
    // creation de la page d'acceuil
    public function home(): Response
    {
      $em = $this->managerRegistry->getManager();
      $produits = $em->getRepository(Habitat::class)->findBy([]);
      $vactus = $em->getRepository(Actualites::class)->findBy([]);

        $vavis = $em->getRepository(Avis::class)->findBy([]);
        return $this->render('home/home.html.twig', [
            'produit' => $produits,
            'avis' => $vavis,
            'actus' => $vactus,
        ]);

    }

    #[Route('femme', name: 'femme')]
    // creation de la page d'acceuil
    public function femme(): Response
    {
      $em = $this->managerRegistry->getManager();
      $rdvs = $em->getRepository(RendezVous::class)->findBy([]);
      $produits = $em->getRepository(Habitat::class)->findBy([]);

      $user = $em->getRepository(User::class)->findBy([]);



        return $this->render('prestation/femme.html.twig', [
          'users' => $user,
          'produit' => $produits,
          'rdv' => $rdvs,


        ]);
    }

    #[Route('homme', name: 'homme')]
    // creation de la page d'acceuil
    public function homme(): Response
    {
      $em = $this->managerRegistry->getManager();
      $rdvs = $em->getRepository(RendezVous::class)->findBy([]);
      $produits = $em->getRepository(Habitat::class)->findBy([]);

      $user = $em->getRepository(User::class)->findBy([]);



        return $this->render('prestation/homme.html.twig', [
          'users' => $user,
          'produit' => $produits,
          'rdv' => $rdvs,


        ]);

    }

    #[Route('enfant', name: 'enfant')]
    // creation de la page d'acceuil
    public function enfant(): Response
    {
      $em = $this->managerRegistry->getManager();
      $rdvs = $em->getRepository(RendezVous::class)->findBy([]);
      $produits = $em->getRepository(Habitat::class)->findBy([]);

      $user = $em->getRepository(User::class)->findBy([]);



        return $this->render('prestation/enfant.html.twig', [
          'users' => $user,
          'produit' => $produits,
          'rdv' => $rdvs,


        ]);
    }

    #[Route('soins', name: 'soins')]
    // creation de la page d'acceuil
    public function soins(): Response
    {
      $em = $this->managerRegistry->getManager();
      $rdvs = $em->getRepository(RendezVous::class)->findBy([]);
      $produits = $em->getRepository(Habitat::class)->findBy([]);

      $user = $em->getRepository(User::class)->findBy([]);



        return $this->render('prestation/soins.html.twig', [
          'users' => $user,
          'produit' => $produits,
          'rdv' => $rdvs,


        ]);
    }




    #[Route('/compte', name: 'compte')]
  /**
   * @Security("is_granted('ROLE_USER')")
   */
  public function mycompte(): Response
  {
      $em = $this->managerRegistry->getManager();
      $user = $this->getUser();

      if ($user) {
          // Fetch reservations for the current user's email
          $email = $user->getEmail();
          $reservations = $em->getRepository(ReservRDV::class)->findBy(['email' => $email], ['id' => 'DESC']);
          $produits = $em->getRepository(Reservation::class)->findBy(['email' => $email], ['id' => 'DESC']);

      } else {
          // Handle the case when the user is not authenticated
          throw new AccessDeniedException('Votre rôle d\'utilisateur ne vous laisse pas avoir accées à cette page Merci de bien vouloir vous connecter');
      }

      return $this->render('home/compte.html.twig', [
          'reservations' => $reservations,
          'produits' => $produits,

      ]);
  }






  // controller des page de prestations

  #[Route('parametre', name: 'parametre')]

  /**
   * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
   */

  // creation de la page d'acceuil
  public function param(): Response
  {
    $em = $this->managerRegistry->getManager();


      return $this->render('home/param.html.twig', [


      ]);
  }

#[Route('condition', name: 'condition')]
public function cgu(): Response
{
    return $this->render('home/cgu.html.twig', [

    ]);
}

#[Route('faq', name: 'faq')]
public function faq(): Response
{
    return $this->render('home/faq.html.twig', [

    ]);
}

#[Route('confidentiel', name: 'confidentiel')]
public function confidentiel(): Response
{
    return $this->render('home/confidentiel.html.twig', [

    ]);
}

#[Route('/avis', name: 'avis')]
/**
       * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST') or is_granted('ROLE_USER')")
       */
       // public function index(Request $request, ManagerRegistry $managerRegistry): Response

public function avis(Request $request, ManagerRegistry $managerRegistry): Response
  {

      $em = $this->managerRegistry->getManager();
    $user = $em->getRepository(User::class)->findBy([]);

      $avis = new Avis();
      $errors = [];

      if (!empty($_POST)) {
        $safe = array_map('trim', array_map('strip_tags', $_POST));

        if (strlen($safe['name']) < 1 || strlen($safe['name']) > 50) {
            $errors[] = 'Le nom doit contenir entre 1 et 50 caractères ';
        }

        if (strlen($safe['prenom']) < 1 || strlen($safe['prenom']) > 50) {
            $errors[] = 'Le prénom doit contenir entre 1 et 50 caractères ';
        }
                // Vérification de l'adresse e-mail
        if (empty($safe['email'])) {
            $errors[] = 'L\'adresse e-mail doit être renseignée';
        } else {
            // Vérifiez si l'adresse e-mail est valide
            if (!filter_var($safe['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'L\'adresse e-mail est invalide';
            }
        }

        if (!isset($safe['prestation'])) {
            $errors[] = 'Veuillez sélectionner une prestation';
        } elseif (!in_array($safe['prestation'], array_keys($this->prestation))) {
            $errors[] = 'La prestation sélectionnée n\'existe pas';
        }

          if (!isset($safe['description']) || strlen($safe['description']) > 2250) {
              $errors[] = 'La description de l\'avis doit contenir entre 1 et 2250 caractères';
          }

          if (!isset($safe['note']) || $safe['note'] < 1 || $safe['note'] > 5) {
              $errors[] = 'La note doit être un nombre entre 1 et 5.';
          }

          $user = $this->getUser();

          if (!$user->isHost() && !$user->isAdmin()) {
              $errors[] = 'Vous n\'avez pas les droits nécessaires pour ajouter un produit.';
          }

          // Traitement si pas d'erreur
          if (count($errors) == 0) {
              $em = $managerRegistry->getManager();
              $avis->setName($safe['name']);
              $avis->setPrenom($safe['prenom']);
              $avis->setEmail($safe['email']);
              $avis->setPrestation($safe['prestation']);
              $avis->setDescription($safe['description']);
              $avis->setNote((int) $safe['note']);

              // $avis->setUser($user); // Décommentez et adaptez si nécessaire


              $resulat= $em->persist($avis);
              $em->flush();
              dump($errors);
              // Débogage: Afficher un message après l'enregistrement
              $this->addFlash('success', 'L\'avis a bien été envoyé');
              return $this->redirectToRoute('home');
          } else {
              $this->addFlash('danger', implode('<br>', $errors));
              // Débogage: Afficher un message en cas d'erreurs
              dump('Échec de l\'enregistrement de l\'avis, erreurs présentes.');
          }
      }

      // Gestion de la réponse pour les requêtes GET ou si le POST échoue
      return $this->render('user/avis.html.twig', [
           'prestation' => $this->prestation,
             'users' => $user,
      ]);
  }



  #[Route('/delete_avis/{id}', name: 'delete_avis')]

  /**
   * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
   */

   // Je sécurise cette page uniquement pour utilisateur connecté ayant le role ADMIN
  // Attention, pour le "IsGranted" ci-dessus, il faut ajouter le "use" correspondant
    public function delavis(ManagerRegistry $doctrine,int $id): Response
    {
        $repository = $doctrine->getRepository(Avis::class);
        $avis = $repository-> find($id);

        if (!empty($_GET)) {
            if (isset($_GET['submit'])) {
                $em = $doctrine->getManager();

                $em->remove($avis);
                // Permet de supprimer tout le avis
                $em->flush();

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('user/delavis.html.twig', [
            'avis' => $avis,

        ]);
    }




  // =========================== pour lajout de NEWSLETTER ===========================


  #[Route('/actu', name: 'actu')]
  /**
         * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
         */
         // public function index(Request $request, ManagerRegistry $managerRegistry): Response

  public function actu( ManagerRegistry $managerRegistry): Response
    {
      $fileMaxSize = 1024 * 1024 * 60; // Taille maximale de l'image en octet 60 MO

      $fileAllowedMimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']; // Les types mimes attendus
      $fileDirUpload = 'uploads/'; // Chemin de sauvegarde d'image, à partir de là ou je me trouve,
      $maxDuration = 180;


        $actu = new Actualites();
        $errors = [];

        if (!empty($_POST)) {
          $safe = array_map('trim', array_map('strip_tags', $_POST));

          //Verif Titre
          if (strlen($safe['name']) < 1 || strlen($safe['name']) > 150) {
              $errors[] = 'Le nom du produit doit contenir entre 1 et 50 caractères ';
          }

          //Verif Titre
          if (strlen($safe['article']) < 1 || strlen($safe['article']) > 2250) {
              $errors[] = 'L\'article contenir entre 1 et 2250 caractères ';
          }

            $user = $this->getUser();

            if (!$user->isHost() && !$user->isAdmin()) {
                $errors[] = 'Vous n\'avez pas les droits nécessaires pour ajouter un produit.';
            }


            // Liste des setters pour chaque image
   $setters = ['setImages'];

   foreach ($setters as $index => $setter) {
       $inputName = 'images' . ($index == 0 ? '' : $index + 1);  // images, images2, images3

       if (!empty($_FILES) && isset($_FILES[$inputName])) {
           $image = $_FILES[$inputName];

           if ($image['error'] === UPLOAD_ERR_NO_FILE) {
               $errors[] = 'L\'image est obligatoire';
           } elseif ($image['error'] === UPLOAD_ERR_OK && !in_array($image['type'], $fileAllowedMimes)) {
               $errors[] = 'Le type du fichier n\'est pas autorisé (jpg, gif, png)';
           } elseif ($image['error'] === UPLOAD_ERR_OK && $image['size'] > $fileMaxSize) {
               $errors[] = 'L\'image est trop volumineuse, taille maxi 10 Mo';
           } else {
               // Upload and handle the image
               $finalFileNamePicture = $fileDirUpload . uniqid() . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);

               if (move_uploaded_file($image['tmp_name'], $finalFileNamePicture)) {
                   $actu->$setter($finalFileNamePicture);
               } else {
                   $errors[] = 'Erreur lors de l\'upload de l\'image';
               }
           }
       }
   }

            // Traitement si pas d'erreur
            if (count($errors) == 0) {
                $em = $managerRegistry->getManager();


                $actu->setName($safe['name']);

                $actu->setArticle($safe['article']);


                // $actu->setUser($user); // Décommentez et adaptez si nécessaire


                $resulat= $em->persist($actu);
                $em->flush();
                dump($errors);
                // Débogage: Afficher un message après l'enregistrement
                $this->addFlash('success', 'L\'actu a bien été envoyé');
                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('danger', implode('<br>', $errors));
                // Débogage: Afficher un message en cas d'erreurs
                dump('Échec de l\'enregistrement de l\'actu, erreurs présentes.');
            }
        }

        // Gestion de la réponse pour les requêtes GET ou si le POST échoue
        return $this->render('user/actu.html.twig', [
          'errors' => $errors,
        ]);
    }


    #[Route('/delete_actus/{id}', name: 'delete_actus')]

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
     */

     // Je sécurise cette page uniquement pour utilisateur connecté ayant le role ADMIN
    // Attention, pour le "IsGranted" ci-dessus, il faut ajouter le "use" correspondant
      public function delactus(ManagerRegistry $doctrine,int $id): Response
      {
          $repository = $doctrine->getRepository(Actualites::class);
          $actus = $repository-> find($id);

          if (!empty($_GET)) {
              if (isset($_GET['submit'])) {
                  $em = $doctrine->getManager();

                  $em->remove($actus);
                  // Permet de supprimer tout le actus
                  $em->flush();

                  return $this->redirectToRoute('home');
              }
          }

          return $this->render('user/delactus.html.twig', [
              'actus' => $actus,

          ]);
      }




}
