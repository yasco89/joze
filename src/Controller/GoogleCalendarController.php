<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Google\Client;
use Google\Service\Calendar;



class GoogleCalendarController extends AbstractController
{

  /**
  * @Route("/google-calendar", name="google_calendar")
  */
  public function index(): Response
  {

     // $credentialsPath = '../cred.json';
     //  $client = new Client();
     //  $client->setClientId('314528950101-vc0nv36gtc17vpm7ehffajs36ll4ueq8.apps.googleusercontent.com');
     //  $client->setClientSecret('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');
     //  $client->setRedirectUri('http://127.0.0.1:8000/google-calendar'); // This should be the URI where Google will redirect after authentication
     //  $client->addScope(Calendar::CALENDAR_EVENTS);


     $credentialsPath = '../cred.json';
     $client = new Client();
     $client->setAuthConfig($credentialsPath); // Charger les informations d'identification à partir du fichier JSON
     $client->addScope(Calendar::CALENDAR_EVENTS);
     

      if (!$this->getUser()) {
          // Assuming you're using Symfony's Security component for user authentication
          return $this->redirectToRoute('app_login');
      }

      $client->setAccessToken($this->getUser()->getGoogleAccessToken());

      if ($client->isAccessTokenExpired()) {
          // If access token is expired, refresh it using the refresh token
          $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
          // Update the access token in your user entity
          $this->getUser()->setGoogleAccessToken($client->getAccessToken());
      }

      $calendarService = new Calendar($client);

      // ... your code to list events

      return $this->render('google_calendar/index.html.twig', [
          'eventSummaries' => $eventSummaries,
      ]);
  }
}
