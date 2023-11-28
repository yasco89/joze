<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Reservation;
use App\Entity\Habitat;
use Doctrine\ORM\EntityManagerInterface;


// pour telecharger pdf

use Dompdf\Dompdf;
use Dompdf\Options;

class ApiDownloadController extends AbstractController
{


  private $managerRegistry; // stock dans la bdd
  private $requestStack;
  public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack)
  {
    $this->managerRegistry = $managerRegistry;
    $this->requestStack = $requestStack;
  }

  /**
   * @Route("/download/reservationsjs", name="download_reservations_js")
   */
  public function downloadReservationsJs(): Response
  {
      $em = $this->managerRegistry->getManager();
      $reservations = $em->getRepository(Reservation::class)->findAll();

      $data = [];
      foreach ($reservations as $reservation) {
          $data[] = [
              'id' => $reservation->getId(),
              'name' => $reservation->getName(),
              'prenom' => $reservation->getPrenom(),
              'email' => $reservation->getEmail(),
              'tel' => $reservation->getTel(),
              'habitat' => $reservation->getHabitat()->getId(),
          ];
      }

      $jsonContent = json_encode($data);

      $response = new Response($jsonContent);
      $response->headers->set('Content-Type', 'application/json');
      $response->headers->set('Content-Disposition', 'attachment; filename="reservations.json"');

      return $response;
  }

// pour telecharger un pdf

public function downloadReservations(): Response
{
    $em = $this->managerRegistry->getManager();
    $reservations = $em->getRepository(Reservation::class)->findAll();

    $htmlContent = '<html><body>';
    foreach ($reservations as $reservation) {
        $htmlContent .= '<p>ID: ' . $reservation->getId() . '</p>';
        $htmlContent .= '<p>Name: ' . $reservation->getName() . '</p>';
        $htmlContent .= '<p>Prénom: ' . $reservation->getPrenom() . '</p>';
        $htmlContent .= '<p>Email: ' . $reservation->getEmail() . '</p>';
        $htmlContent .= '<p>Tel: ' . $reservation->getTel() . '</p>';
        $htmlContent .= '<p>Habitat: ' . $reservation->getHabitat()->getId() . '</p>';
        $htmlContent .= '<hr>';
    }
    $htmlContent .= '</body></html>';

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="reservations.pdf"');

    return $response;
}

// Telcharger format json

  public function downloadHabitats(ManagerRegistry $managerRegistry): Response
  {
      $em = $managerRegistry->getManager();
      $habitats = $em->getRepository(Habitat::class)->findAll();

      $data = [];
      foreach ($habitats as $habitat) {
          $data[] = [
              'id' => $habitat->getId(),
              'name' => $habitat->getName(),
              'description' => $habitat->getDescription(),
              'images' => $habitat->getImages(),
              'quantite' => $habitat->getQuantite(),
              'prix' => $habitat->getPrix(),
              'type' => $habitat->getType(),
              'famille' => $habitat->getFamille(),
          ];
      }

      $jsonContent = json_encode($data);

      $response = new Response($jsonContent);
      $response->headers->set('Content-Type', 'application/json');
      $response->headers->set('Content-Disposition', 'attachment; filename="habitats.json"');

      return $response;
  }



// Format pdf 
/**
 * @Route("/download/habitatspdf", name="download_habitats_pdf")
 */
public function downloadHabitatsPdf(ManagerRegistry $managerRegistry): Response
{
    $em = $managerRegistry->getManager();
    $habitats = $em->getRepository(Habitat::class)->findAll();

    $htmlContent = '<html><body>';
    foreach ($habitats as $habitat) {
        $htmlContent .= '<p>ID: ' . $habitat->getId() . '</p>';
        $htmlContent .= '<p>Name: ' . $habitat->getName() . '</p>';
        $htmlContent .= '<p>Description: ' . $habitat->getDescription() . '</p>';
        // Add other habitat fields as needed
        $htmlContent .= '<hr>';
    }
    $htmlContent .= '</body></html>';

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($htmlContent);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="habitats.pdf"');

    return $response;
}





}
