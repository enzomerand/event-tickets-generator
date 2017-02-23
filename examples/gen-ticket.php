<?php

	include('../library/event-ticket/Config.php');

	$logo = '\..\assets\img\logo.png';
	$event_name = 'SuperBoom Fest';
	$loc = 'Nantes, France';

	$ticket = new EventTicket\Ticket(getcwd() . $logo, $event_name, 'SP Records');
	$ticket->event_location = $loc;

	$ticket->setGenerator();
	//$ticket->setCodes(['123456']);
	
	$ticket->genTickets([['ticket_name' => 'hey', 'ticket_code' => '1', 'event_date' => '26/02/2018'], ['ticket_code' => '345649329']]);
	//$ticket->genQrCode(3849504);
	//$ticket->genBarCode(394903989);