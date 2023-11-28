
<?php

require 'vendor/autoload.php';
$client = new Google\Client();
$client->setAuthConfig('cred.json');
$client->addScope(Google\Service\Calendar::CALENDAR_EVENTS);

var_dump($client);
$calendarService = new Google\Service\Calendar($client);


$calendarId = 'primary';
$events = $calendarService->events->listEvents($calendarId);

// Print the events
foreach ($events->getItems() as $event) {
  echo $event->getSummary() . "<br>";
}




 ?>
