<?php
require 'vendor/autoload.php';  // Path to autoload.php from the installed library

// Set up the credentials
$client = new Google\Client();
$client->setAuthConfig('cred.json'); // Path to your downloaded credentials JSON
$client->addScope(Google\Service\Calendar::CALENDAR_EVENTS);

// Create an authorized client
$calendarService = new Google\Service\Calendar($client);

// Get the list of events from a specific calendar
$calendarId = 'primary'; // You can replace 'primary' with your calendar ID
$events = $calendarService->events->listEvents($calendarId);

// Print the events
foreach ($events->getItems() as $event) {
    echo $event->getSummary() . "<br>";
}
?>
