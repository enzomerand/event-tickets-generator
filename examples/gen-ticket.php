<?php

	include('../library/event-ticket/Config.php');

	$logo = '\..\assets\img\logo.png';
	$event_name = 'SuperBoom Fest';
	$loc = '53 rue du Pontereau, 44300 - Nantes, France';

	$ticket = new EventTicket\Ticket(getcwd() . $logo, $event_name, 'SP Records');
	$ticket->event_location = $loc;

	$ticket->setGenerator();
	//$ticket->setCodes(['123456']);
	
	$ticket->genTickets([['user_first_name' => 'Enzo', 'user_last_name' => ' Merand', 'ticket_code' => '843292932', 'ticket_type' => 'PASS 2 JOURS', 'ticket_price' => '20', 'event_date' => '26/02/2018'], ['ticket_code' => '345649329']]);
	//$ticket->genQrCode(3849504);
	//$ticket->genBarCode(394903989);
	
	//$ticket->exportTickets([['ticket_name' => 'hey', 'ticket_code' => '1', 'event_date' => '26/02/2018'], ['ticket_code' => '345649329']], true, true);