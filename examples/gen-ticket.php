<?php

	include('../library/event-ticket/Config.php');

	$logo = '/assets/img/logo.png';
	$event_name = 'SuperBoom Fest';
	$loc = 'Nantes, France';

	$ticket = new EventTicket\Ticket($logo, $event_name, 'SP Records');
	$ticket->event_location = $loc;

	$ticket->setGenerator();
	
	 $ticket->exportTickets([['ticket_name' => 'hey', 'ticket_code' => '12345', 'event_date' => '26/02/2018']])[0];