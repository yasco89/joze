<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Habitat;
use App\Entity\Reservation;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class PaymentController extends AbstractController
{
    private $managerRegistry;
    private $requestStack;
    private $stripeSecretKey;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack, string $stripeSecretKey)
    {
        $this->managerRegistry = $managerRegistry;
        $this->requestStack = $requestStack;
        $this->stripeSecretKey = $stripeSecretKey;
    }

    /**
     * @Route("/payment/{id}", name="payment")
     */
    public function index(int $id)
    {
        $em = $this->managerRegistry->getManager();

        // Récupérer l'habitat par son ID
        $habitat = $em->getRepository(Habitat::class)->find($id);
        if (!$habitat) {
            throw $this->createNotFoundException('L\'habitat n\'existe pas');
        }

        // Récupérer la réservation associée à l'habitat
        $reservation = $em->getRepository(Reservation::class)->findOneBy(['habitat' => $habitat]);
        if (!$reservation) {
            throw $this->createNotFoundException('Réservation non trouvée pour l\'habitat avec l\'ID: ' . $id);
        }

        $dateDebut = $reservation->getDate();
        $dateFin = $reservation->getDateretour();

        // Calculez la différence en jours entre les deux dates
        $interval = $dateDebut->diff($dateFin);
        $nombreDeJours = $interval->days;

        // Supposons que le prix de l'habitat est par jour
        $prixParJourEnEuros = $habitat->getPrix();
        $prixTotalEnCentimes = $prixParJourEnEuros * $nombreDeJours * 100;

        Stripe::setApiKey($this->stripeSecretKey);
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $prixTotalEnCentimes,
            'currency' => 'eur',
        ]);

        return $this->render('payment/index.html.twig', [
            'clientSecret' => $paymentIntent->client_secret,
            'habitat' => $habitat,
        ]);
    }


}
