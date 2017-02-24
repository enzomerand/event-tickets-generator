<?php

	include('../library/event-ticket/Config.php');

	$logo = '/../assets/img/logo.png';
	$event_name = 'SuperBoom Fest';
	$loc = '21, quai des Antilles - 44200 Nantes';

	$ticket = new EventTicket\Ticket(getcwd() . $logo, $event_name, 'SP Records');
	$ticket->event_location = $loc;

	$ticket->setGenerator();
	
	$ticket->genTickets(['ticket_code' => '7A23923949', 'user_first_name' => 'Jean', 'user_last_name' => 'Dujardin', 'ticket_type' => 'EARLY BIRDS', 'ticket_price' => 22, 'ticket_buy_date' => '26/02/2017']);