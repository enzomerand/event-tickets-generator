<?php

	include('../library/event-ticket/Config.php');

	$logo = '/../assets/img/logo.png';
	$event_name = 'SuperBoom Fest';
	$loc = '21, quai des Antilles - 44200 Nantes';

	$ticket = new EventTicket\Ticket(getcwd() . $logo, $event_name, 'SP Records');
	$ticket->event_location = $loc;

	$ticket->setGenerator();