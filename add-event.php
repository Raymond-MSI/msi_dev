<?php
require 'vendor/autoload.php';
require __DIR__ . '/google-calendar.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather form data
    $taskName = $_POST["taskName"];
    $startWorkDate = $_POST["startWorkDate"];
    $finalWorkDate = $_POST["finalWorkDate"];
    $resourceEmail = $_POST["resourceEmail"];
    $permalink = $_POST["permalink"];

    // Convert start and end times to Indonesia timezone
    $startWorkDate = new DateTime($startWorkDate, new DateTimeZone('UTC'));
    $startWorkDate->setTimezone(new DateTimeZone('Asia/Jakarta'));


    $finalWorkDate = new DateTime($finalWorkDate, new DateTimeZone('UTC'));
    $finalWorkDate->setTimezone(new DateTimeZone('Asia/Jakarta'));

    // Get the authorized client
    $client = getClient();

    // Create service
    $service = new Google_Service_Calendar($client);

    // Create attendees
    $attendees = array();
    $attendee1 = new Google_Service_Calendar_EventAttendee();
    $attendee1->setEmail($resourceEmail);
    $attendees[] = $attendee1;


    // var_dump($attendees);
    // die;
    // Create event
    $event = new Google_Service_Calendar_Event([
        'summary' => "[I] $taskName",
        'location' => "PT. Mastersystem Infotama",
        'description' => "{Insert your comment here} || This event was created from Wrike task Implementation Link: $permalink",
        'attendees' => $attendees,
        'start' => [
            'dateTime' => $startWorkDate->format('Y-m-d\TH:i:s'),
            'timeZone' => 'Asia/Jakarta',
        ],
        'end' => [
            'dateTime' => $finalWorkDate->format('Y-m-d\TH:i:s'),
            'timeZone' => 'Asia/Jakarta',
        ],
    ]);

    // Insert event
    $calendarId = "$resourceEmail";

    try {
        $event = $service->events->insert($calendarId, $event);
        echo "Event added successfully!";
    } catch (Google\Service\Exception $e) {
        // Handle the exception
        $error = $e->getMessage();
        $code = $e->getCode();
        $reason = $e->getErrors()[0]['reason'];
        // Display an error message to the user or log the error

        echo "Error: $reason";
        echo "Message: $error";
        echo "Code: $code";
    }
}
