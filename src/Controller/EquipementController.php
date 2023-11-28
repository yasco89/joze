<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\Habitat;
use App\Entity\Reservation;
use App\Entity\RendezVous;
use App\Entity\ReservRDV;

use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RequestStack;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Kernelbalcon;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class EquipementController extends AbstractController
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
      'lissage'               => 'Lissage',
      'creme'                 => 'Crème',
      'defrisage'             => 'Défrisage',
      'perruques'             => 'Perruques',
      'autre'                 => 'Autre',

      // Types de coiffure pour homme
        'Coupe-Homme'             => 'Coupe-Homme',
      'coupe_courte_classique'=> 'Coupe courte classique',
      'pompadour'             => 'Pompadour',
      'undercut'              => 'Undercut',
      'degrade'               => 'Dégradé',
      'coupe_brosse'          => 'Coupe Brosse',
      'coupe_decontractee'    => 'Coupe Décontractée',


      // Types de coiffure pour femme
      'coupe_carree'          => 'Coupe Carrée',
      'coupe_pixie'           => 'Coupe Pixie',
      'coupe_longue_couches'  => 'Coupe Longue avec des Couches',
      'coupe_bob'             => 'Coupe Bob',
      'coupe_degradee'        => 'Coupe Dégradée',

  ];


  public $lieu = [
    'Salon'  => 'Salon',
    'Domicile'  => 'Domicile',
  ];




  #[Route('/add_equipement', name: 'equipement')]

    /**
           * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
           */

  // public function index(Request $request, ManagerRegistry $managerRegistry): Response
  public function index( ManagerRegistry $managerRegistry): Response
  {

    $fileMaxSize = 1024 * 1024 * 60; // Taille maximale de l'image en octet 60 MO

    $fileAllowedMimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']; // Les types mimes attendus
    $fileDirUpload = 'uploads/'; // Chemin de sauvegarde d'image, à partir de là ou je me trouve,
    $maxDuration = 180;


    $produit = new Habitat();

    $errors = [];

    if (!empty($_POST)) {

        $safe = array_map('trim', array_map('strip_tags', $_POST));

        // var_dump($safe);

        if (!isset($safe['famille'])) {
            $errors[] = 'Veuillez sélectionner une famille';
        } elseif (!in_array($safe['famille'], array_keys($this->famille))) {
            $errors[] = 'La famille sélectionnée n\'existe pas';
        }


        if (!isset($safe['type'])) {
            $errors[] = 'Veuillez sélectionner un type';
        } elseif (!in_array($safe['type'], array_keys($this->type))) {
            $errors[] = 'La type sélectionnée n\'existe pas';

        }

        //Verif Titre
        if (strlen($safe['name']) < 1 || strlen($safe['name']) > 50) {
            $errors[] = 'Le nom du produit doit contenir entre 1 et 50 caractères ';
        }

        if (strlen($safe['description']) < 1 || strlen($safe['description']) > 2250) {
            $errors[] = 'La description du produit doit contenir entre 1 et 50 caractères ';
        }



        if (strlen($safe['quantite']) < 1 || strlen($safe['quantite']) > 150) {
             $errors[] = 'La quantite doit être renseigne';
         }



         if (!strlen($safe['prix']) > 2500) {
             $errors[] = 'Le prix doit être renseignée.';
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
                        $produit->$setter($finalFileNamePicture);
                    } else {
                        $errors[] = 'Erreur lors de l\'upload de l\'image';
                    }
                }
            }
        }


        $user = $this->getUser();

        if (!$user->isHost() && !$user->isAdmin()) {
            $errors[] = 'Vous n\'avez pas les droits nécessaires pour ajouter un produit.';
        }



         var_dump($errors);

        // Si je n'ai pas d'erreur, je passe dans la condition
        if (count($errors) == 0) {

            $em = $managerRegistry->getManager();
            // $produit->setImages($fileNamePicture);
            $produit->setFamille($safe['famille']);
            $produit->setType($safe['type']);
            $produit->setDescription($safe['description']);
            $produit->setQuantite($safe['quantite']);
            $produit->setPrix($safe['prix']);

            $produit->setName($safe['name']);
            // Définir l'utilisateur comme étant le 'host' de l'habitat.
            $produit->setHost($user);

            $resulat= $em->persist($produit);

            $em->flush();
            dump($errors);

            $this->addFlash('success', 'Le produit a bien été envoyer');
            return $this->redirectToRoute('home');
        } else {
            $this->addFlash('danger', implode('<br>', $errors));
        }
    }
  return $this->render('equipement/index.html.twig', [
    'famille' => $this->famille,

      'type' => $this->type,

  ]);
  }



    #[Route('/liste', name: 'liste')]
    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
     */
    public function list(): Response
    {
        $em = $this->managerRegistry->getManager();

        if ($this->isGranted('ROLE_ADMIN')) {
            // L'administrateur peut voir tous les habitats
            $produits = $em->getRepository(Habitat::class)->findBy([]);
        } else {
            // Les hôtes ne peuvent voir que leurs propres habitats
            $user = $this->getUser(); // Supposons que l'entité User est liée à l'entité Habitat
            $produits = $em->getRepository(Habitat::class)->findBy(['host' => $user]);
        }

        return $this->render('equipement/list.html.twig', [
         'produit' => $produits,
        ]);
    }






      /**
           * Suppresssion d'une publication
           */

          #[Route('/delete_produit/{id}', name: 'delete_produit')]

          /**
           * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
           */

           // Je sécurise cette page uniquement pour utilisateur connecté ayant le role ADMIN
          // Attention, pour le "IsGranted" ci-dessus, il faut ajouter le "use" correspondant
            public function del(ManagerRegistry $doctrine,int $id): Response
            {
                $repository = $doctrine->getRepository(Habitat::class);
                $produit = $repository-> find($id);

                if (!empty($_GET)) {
                    if (isset($_GET['submit'])) {
                        $em = $doctrine->getManager();

                        // Diminuer la quantité de 1
                        // $quantite = $produit->getQuantite();
                        // if ($quantite > 0) {
                        //     $produit->setQuantite($quantite - 1);
                        // }

                        $em->remove($produit);
                        // Permet de supprimer tout le produit
                        $em->flush();

                        return $this->redirectToRoute('liste');
                    }
                }

                return $this->render('equipement/del.html.twig', [
                    'produit' => $produit,

                ]);
            }





              /**
               * Affichage d'une vue avec le systeme de reservation
               */
              #[Route('/voir-le-stock-equipement/{id}', name: 'view_produit')]
              public function view(int $id, Request $request, ManagerRegistry $managerRegistry): Response
              {
                  $em = $this->managerRegistry->getManager();
                  // Récupérer l'entité Habitat à partir de l'ID
                  $produit = $em->getRepository(Habitat::class)->find($id);
                  $user = $em->getRepository(User::class)->findBy([]);


                  $reservation = new Reservation();
                  $errors = [];

                  // $errors = [];

                  if (!empty($_POST)) {

                      $safe = array_map('trim', array_map('strip_tags', $_POST));



              // Verif Titre
              if (strlen($safe['name']) < 1 || strlen($safe['name']) > 50) {
                  $errors[] = 'Le nom de la reservation doit contenir entre 1 et 50 caractères ';
              }
                      //Verif Titre
                      if (strlen($safe['name']) < 1 || strlen($safe['name']) > 50) {
                          $errors[] = 'Le nom de la reservation doit contenir entre 1 et 50 caractères ';
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


                        // Vérification du numéro de téléphone
                        $tel=$safe['tel'];
                        if (empty($safe['tel'])) {
                            $errors[] = 'Le numéro de téléphone doit être renseigné';
                        } else {
                            // Supprimez les caractères non numériques du numéro de téléphone
                            $tel = preg_replace('/[^0-9]/', '', $tel);

                            // Vérifiez si le numéro a le format attendu (par exemple, 10 chiffres pour un numéro de téléphone standard)
                            if (strlen($tel) !== 10) {
                                $errors[] = 'Le numéro de téléphone est invalide';
                            }
                        }


                      // Si aucune erreur n'a été trouvée, enregistrez la réservation
                      if (count($errors) === 0) {
                        $em = $managerRegistry->getManager();
                        $reservation->setName($safe['name']);
                        $reservation->setPrenom($safe['prenom']);
                        $reservation->setEmail($safe['email']);
                        $reservation->setTel($safe['tel']);
                        // Établissez la relation avec Habitat
                        $reservation->setHabitat($produit);

                        $em->persist($reservation);

                          // Pour diminuer de 1 la quantite lors d\'un ajour de reservation
                          // Réduire la quantité du produit
                        $produit->setQuantite($produit->getQuantite() - 1);
                        $em->flush();

                        $this->addFlash('success', 'La reservation a bien été envoyé');
                        return $this->redirectToRoute('home');
                      }
                  }

                  /**
                   * Si je n'ai pas de produit correspondant (id inexistant en base de données) je fais une page 404
                   * @see https://symfony.com/doc/current/controller.html#managing-errors-and-404-pages
                   */
                  if (empty($produit)) {
                      throw $this->createNotFoundException('Cette information n\'existe pas');
                  }

                  return $this->render('equipement/view.html.twig', [
                      'produits' => $produit,
                      'users' => $user,
                      'reservation' => $reservation,
                      'errors' => $errors,
                  ]);
              }

              // =========================> champs de formulaire pour modification



                   #[Route('/modifierequipement/{id}', name: 'modifierproduit')]

                  public function updateProduit(int $id, Request $request, ManagerRegistry $managerRegistry): Response
                  {
                    $em = $managerRegistry->getManager();
                    $produit = $em->getRepository(Habitat::class)->find($id);

                      if (!$produit) {
                          throw $this->createNotFoundException('Produit non trouvé');
                      }

                      if ($request->isMethod('POST')) {
                          $produit->setName($request->request->get('name'));

                          $produit->setType($request->request->get('type'));

                          $produit->setQuantite($request->request->get('quantite'));
                          $produit->setPrix($request->request->get('prix'));

                    $em->flush();

                          return $this->redirectToRoute('home');
                      }

                      return $this->render('equipement/modifier.html.twig', [
                          'produits' => $produit,
                          'famille' => $this->famille,
                          'type' => $this->type,
                      ]);
                  }









              #[Route('/listereservation', name: 'listereservation')]
          /**
           * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
           */
          public function listReservation(): Response
          {
              $em = $this->managerRegistry->getManager();

              if ($this->isGranted('ROLE_ADMIN')) {
                  // L'administrateur peut voir toutes les réservations
                  $reservations = $em->getRepository(Reservation::class)->findBy([]);

                  return $this->render('equipement/liste_reservations.html.twig', [
                      'reservations' => $reservations,
                  ]);
              }

              // Si l'utilisateur n'est pas administrateur, vous pouvez ajouter une logique de gestion ici
              // Par exemple, pour les hôtes

              return $this->redirectToRoute('accueil'); // Redirige vers une autre page s'il n'est ni administrateur ni hôte
          }








          // Définition de la route pour la vue de la reservation
           #[Route('/voir-la-reservation/{id}', name: 'view_reservation')]

           /**
           * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST') or is_granted('ROLE_USER')")
           */

           public function viewreservation(int $id): Response
 {
     $em = $this->managerRegistry->getManager();
     // Recherche de la réservation en fonction de l'ID
     $reservation = $em->getRepository(Reservation::class)->find($id);
     $users = $em->getRepository(User::class)->findAll(); // Remplacez par la méthode appropriée pour récupérer les utilisateurs


     // Gestion de l'absence de réservation
     if (empty($reservation)) {
         throw $this->createNotFoundException('Cette réservation n\'existe pas');
     }

     return $this->render('equipement/view_reservation.html.twig', [
         'reservation' => $reservation,
         'users' => $users,

     ]);
 }


// se qui suit sont les controlleur ou seulement les host ont les accées et ne pourrons voir que leur hebergement

           /**
            * @Route("/voir-mes-equipements", name="view_my_produits")
            */
           public function viewMyEquipments(): Response
           {
               $em = $this->managerRegistry->getManager();
               $user = $this->getUser(); // récupère l'utilisateur actuellement connecté

               if (!$user) {
                   throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
               }

               $produits = $em->getRepository(Habitat::class)->findBy(['host' => $user]);

               if (empty($produits)) {
                   throw $this->createNotFoundException('Vous n\'avez pas encore d\'habitations.');
               }

               return $this->render('host/meshabitations.html.twig', [
                   'produit' => $produits,
               ]);
           }

// ===================================== - Formulaire pour prendre un Rendez-vous pour une prestation - =====================================

           #[Route('/Rendez-vous', name: 'RENDEZVOUS')]
           /**
                  * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
                  */
                  // public function index(Request $request, ManagerRegistry $managerRegistry): Response

           public function rendezvous( ManagerRegistry $managerRegistry): Response
             {
                 $rdv = new RendezVous();
                 $errors = [];

                 if (!empty($_POST)) {
                   $safe = array_map('trim', array_map('strip_tags', $_POST));

                   if (!isset($safe['famille'])) {
                       $errors[] = 'Veuillez sélectionner une famille';
                   } elseif (!in_array($safe['famille'], array_keys($this->famille))) {
                       $errors[] = 'La famille sélectionnée n\'existe pas';
                   }

                   if (empty($safe['date'])) {
                       $errors[] = 'La date doit être renseignée.';
                   } else {
                       // Valider que la date est au format attendu
                       $date = \DateTime::createFromFormat('Y-m-d', $safe['date']);

                       if (!$date || $date->format('Y-m-d') !== $safe['date']) {
                           $errors[] = 'La date n\'est pas au format valide.';
                       } elseif ($date < new \DateTime()) {
                           $errors[] = 'La date ne peut pas être dans le passé.';
                       } else {

                           $rdv->setDate($date);
                       }
                   }




                   // PERMET DE METTRE UNE HEURE DE DEBUT ET UNE HEURE DE FIN QUI SE CALCUL AUTOMATIQUEMENT
                                   if (empty($safe['heure_debut'])) {
                       $errors[] = 'L\'heure de début doit être renseignée.';
                   } else {
                       // Valider que l'heure de début est au format attendu
                       $heureDebut = \DateTime::createFromFormat('H:i', $safe['heure_debut']);
                       if (!$heureDebut || $heureDebut->format('H:i') !== $safe['heure_debut']) {
                           $errors[] = 'L\'heure de début n\'est pas au format valide.';
                       } else {
                           // Si l'heure de début est valide, affectez-la à votre entité
                           $rdv->setHeureDebut($heureDebut);

                           // Calculez l'heure de fin en ajoutant une heure à l'heure de début
                           $heureFin = clone $heureDebut;
                           $heureFin->add(new \DateInterval('PT1H')); // PT1H représente une heure
                           $rdv->setHeureFin($heureFin);
                       }
                   }

                           if (strlen($safe['quantite']) < 1 || strlen($safe['quantite']) > 150) {
                                $errors[] = 'Le nombre de place doit être renseigne';
                            }


                   if (!isset($safe['type'])) {
                       $errors[] = 'Veuillez sélectionner un type';
                   } elseif (!in_array($safe['type'], array_keys($this->type))) {
                       $errors[] = 'Le type sélectionnée n\'existe pas';
                   }

                     $user = $this->getUser();

                     if (!$user->isHost() && !$user->isAdmin()) {
                         $errors[] = 'Vous n\'avez pas les droits nécessaires pour ajouter un rendez-vous.';
                     }

     // Traitement si pas d'erreur
                     if (count($errors) == 0) {
                         $em = $managerRegistry->getManager();

                         // $rdv->setUsername($safe['username']);
                         // $rdv->setName($safe['name']);
                         $rdv->setFamille($safe['famille']);
                         $rdv->setType($safe['type']);
                         $rdv->setQuantite($safe['quantite']);
                         // $avis->setUser($user); // Décommentez et adaptez si nécessaire


                         $resulat= $em->persist($rdv);
                         $em->flush();
                         dump($errors);
                         // Débogage: Afficher un message après l'enregistrement
                         $this->addFlash('success', 'L\'rdv a bien été envoyé');
                         return $this->redirectToRoute('home');
                     } else {
                         $this->addFlash('danger', implode('<br>', $errors));
                         // Débogage: Afficher un message en cas d'erreurs
                         dump('Échec de l\'enregistrement de l\'rdv, erreurs présentes.');
                     }
                 }

                 // Gestion de la réponse pour les requêtes GET ou si le POST échoue
                 return $this->render('equipement/rendezvous.html.twig', [
                   'famille' => $this->famille,
                     'type' => $this->type,
                       'errors' => $errors,
                 ]);
             }
// ====================== - POUR AFFICHER LE CALENDRIER DES RENDEZ-VOUS DISPONIBLE - =======================

    #[Route('/viewrdv', name: 'view_rdv')]

      /**
       * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
           */

   public function view_rdv(ManagerRegistry $managerRegistry): Response
   {
       $em = $managerRegistry->getManager();
       $rdvlist = $em->getRepository(RendezVous::class)->findAll();

       $Vrdv = [];
       foreach ($rdvlist as $rdv) {
                   $Vrdv [] = $rdv->getFamille();
		               $Vrdv [] = $rdv->getType();
                   $Vrdv [] = $rdv->getDate()->format('Y-m-d H:i:s');
  		             $Vrdv [] = $rdv->getHeureDebut()->format('Y-m-d H:i:s');
                   $Vrdv [] = $rdv->getHeureFin()->format('Y-m-d H:i:s');

       }
       return $this->render('equipement/view_rdv.html.twig', [

           'rdvlist' => $rdvlist,
           'VrdvJson' => json_encode($Vrdv),
       ]);
   }

   /**
    * Affichage d'une vue avec le systeme de reservation
    */
   #[Route('/rdvview/{id}', name: 'rdvview')]
   public function viewRDV(int $id, Request $request, ManagerRegistry $managerRegistry): Response
   {
       $em = $this->managerRegistry->getManager();
       // Récupérer l'entité rdv à partir de l'ID
       $rendezvous = $em->getRepository(RendezVous::class)->find($id);
       $user = $em->getRepository(User::class)->findBy([]);

       $newrdv = new ReservRDV();

       $errors = [];




       if (!empty($_POST)) {
           $safe = array_map('trim', array_map('strip_tags', $_POST));


           if (strlen($safe['name']) < 1 || strlen($safe['name']) > 50) {
               $errors[] = 'Le nom de la reservation doit contenir entre 1 et 50 caractères ';
           }

           if (strlen($safe['username']) < 1 || strlen($safe['username']) > 50) {
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



           if (!isset($safe['lieu'])) {
               $errors[] = 'Veuillez sélectionner un lieu';
           } elseif (!in_array($safe['lieu'], array_keys($this->lieu))) {
               $errors[] = 'Le lieu sélectionnée n\'existe pas';
           }






             // Vérification du numéro de téléphone
             $tel=$safe['tel'];
             if (empty($safe['tel'])) {
                 $errors[] = 'Le numéro de téléphone doit être renseigné';
             } else {
                 // Supprimez les caractères non numériques du numéro de téléphone
                 $tel = preg_replace('/[^0-9]/', '', $tel);

                 // Vérifiez si le numéro a le format attendu (par exemple, 10 chiffres pour un numéro de téléphone standard)
                 if (strlen($tel) !== 10) {
                     $errors[] = 'Le numéro de téléphone est invalide';
                 }
             }


           // Si aucune erreur n'a été trouvée, enregistrez la réservation
           if (count($errors) === 0) {
             $em = $managerRegistry->getManager();
             $newrdv->setName($safe['name']);
             $newrdv->setUsername($safe['username']);
             $newrdv->setEmail($safe['email']);
             $newrdv->setTel($safe['tel']);
             $newrdv->setLieu($safe['lieu']);
             $newrdv->setAdresse($safe['adresse']);
             // Établissez la relation avec rendezvous
             $newrdv->setRdv($rendezvous);


             $em->persist($newrdv);
             dump($errors);

               // Pour diminuer de 1 la quantite lors d\'un ajour de newrdv
               // Réduire la quantité du rendezvous
             $rendezvous->setQuantite($rendezvous->getQuantite() - 1);
             $em->flush();
                // Débogage: Afficher un message après l'enregistrement
                $this->addFlash('success', 'L\'rdv a bien été envoyé');
                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('danger', implode('<br>', $errors));
                // Débogage: Afficher un message en cas d'erreurs
                dump('Échec de l\'enregistrement de l\'rdv, erreurs présentes.');
            }
       }

       /**
        * Si je n'ai pas de rendezvous correspondant (id inexistant en base de données) je fais une page 404
        * @see https://symfony.com/doc/current/controller.html#managing-errors-and-404-pages
        */
       if (empty($rendezvous)) {
           throw $this->createNotFoundException('Cette information n\'existe pas');
       }
       return $this->render('equipement/viewRDV.html.twig', [
            'lieu' => $this->lieu,
           'rendezvouss' => $rendezvous,
           'users' => $user,
           'errors' => $errors,
           'newrdv' => $newrdv,


       ]);
   }



      #[Route('/listerdv', name: 'listerdv')]
  /**
   * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
   */
  public function listrdv(): Response
  {
      $em = $this->managerRegistry->getManager();

      if ($this->isGranted('ROLE_ADMIN')) {
          // L'administrateur peut voir toutes les réservations
          $reservations = $em->getRepository(ReservRDV::class)->findBy([], ['id' => 'DESC']);

          return $this->render('rendezvous/liste_rdv.html.twig', [
              'reservations' => $reservations,
          ]);
      }

      // Si l'utilisateur n'est pas administrateur, vous pouvez ajouter une logique de gestion ici
      // Par exemple, pour les hôtes

      return $this->redirectToRoute('accueil'); // Redirige vers une autre page s'il n'est ni administrateur ni hôte
  }



            // Définition de la route pour la vue de la reservation
            #[Route('/voir-le-rdv/{id}', name: 'view_reservation_rdv')]

             /**
             * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST') or is_granted('ROLE_USER')")
             */

             public function viewrendezvous(int $id): Response
   {
       $em = $this->managerRegistry->getManager();
       // Recherche de la réservation en fonction de l'ID
       $reservation = $em->getRepository(ReservRDV::class)->find($id);
       $users = $em->getRepository(User::class)->findAll(); // Remplacez par la méthode appropriée pour récupérer les utilisateurs


       // Gestion de l'absence de réservation
       if (empty($reservation)) {
           throw $this->createNotFoundException('Cette réservation n\'existe pas');
       }

       return $this->render('rendezvous/rdvpris.html.twig', [
           'reservation' => $reservation,
           'users' => $users,

       ]);
   }


   #[Route('/delete_rdv/{id}', name: 'delete_rdv')]

   /**
    * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_HOST')")
    */

    // Je sécurise cette page uniquement pour utilisateur connecté ayant le role ADMIN
   // Attention, pour le "IsGranted" ci-dessus, il faut ajouter le "use" correspondant
     public function delrdv(ManagerRegistry $doctrine,int $id): Response
     {
         $repository = $doctrine->getRepository(RendezVous::class);
         $rdv = $repository-> find($id);

         if (!empty($_GET)) {
             if (isset($_GET['submit'])) {
                 $em = $doctrine->getManager();



                 $em->remove($rdv);
                 // Permet de supprimer tout le rdv
                 $em->flush();

                 return $this->redirectToRoute('home');
             }
         }

         return $this->render('equipement/delrdv.html.twig', [
             'rdv' => $rdv,

         ]);
     }




         }
