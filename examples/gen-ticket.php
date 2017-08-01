<?php

	include('../library/event-ticket/Config.php');

	$logo = getcwd() . '/../assets/img/logo.png';
	$loc = '21, quai des Antilles - 44200 Nantes';

	$ticket = new EventTicket\Ticket();
	$ticket->event_location = $loc;
	$ticket->use_qr_links = true;
	$ticket->event_logo = $logo;
	$ticket->event_name = 'SuperBoom Fest';
	$ticket->event_date = '26/02/2018 16:08:01';

	//$ticket->setGenerator();
	
	//$ticket->genTickets([['ticket_code' => '1519596289', 'link_validation' => 'http://superboomrecords.fr', 'user_first_name' => 'Jean', 'event_date' => '12/06/2017', 'user_last_name' => 'Dujardin', 'ticket_type' => 'early birds', 'ticket_price' => 22, 'ticket_buy_date' => '26/02/2017'], ['ticket_code' => '7A23923950', 'user_first_name' => 'Michel', 'user_last_name' => 'Dubardin', 'ticket_type' => 'PASS 2 J', 'ticket_price' => 55.5, 'ticket_buy_date' => '26/02/2017']]);
	//$ticket->importTickets(['tickets-import.csv', 'tickets-import2.csv']);
	$ticket->downloadTickets('tickets25807.pdf');
	
	//$ticket->exportTickets([['ticket_code' => '7A23923949', 'event_date' => '12/06/2017', 'user_first_name' => 'Jean', 'user_last_name' => 'Dujardin', 'ticket_type' => 'early birds', 'ticket_price' => 22, 'ticket_buy_date' => '26/02/2017'], ['ticket_code' => '7A23923950', 'user_first_name' => 'Michel', 'user_last_name' => 'Dubardin', 'ticket_type' => 'PASS 2 J', 'ticket_price' => 55.5, 'ticket_buy_date' => '26/02/2017']]);