<?php

	include('../library/event-ticket/Config.php');

	$lang = 'fr_FR';
	$logo = getcwd() . '/../assets/img/logo.png';
	$event_name = 'SuperBoom Fest';
	$loc = '21, quai des Antilles - 44200 Nantes';

	$ticket = new EventTicket\Ticket($lang, $logo, $event_name, 'SB Records');
	$ticket->event_location = $loc;
	$ticket->use_qr_links = true;

	$ticket->setGenerator();
	
	$ticket->genTickets([['ticket_code' => '1519596289', 'link_validation' => 'http://superboomrecords.fr', 'user_first_name' => 'Jean', 'event_date' => '12/06/2017', 'user_last_name' => 'Dujardin', 'ticket_type' => 'early birds', 'ticket_price' => 22, 'ticket_buy_date' => '26/02/2017'], ['ticket_code' => '7A23923950', 'user_first_name' => 'Michel', 'user_last_name' => 'Dubardin', 'ticket_type' => 'PASS 2 J', 'ticket_price' => 55.5, 'ticket_buy_date' => '26/02/2017']]);
	//$ticket->downloadTickets('7A23923949');
	
	//$ticket->exportTickets([['ticket_code' => '7A23923949', 'event_date' => '12/06/2017', 'user_first_name' => 'Jean', 'user_last_name' => 'Dujardin', 'ticket_type' => 'early birds', 'ticket_price' => 22, 'ticket_buy_date' => '26/02/2017'], ['ticket_code' => '7A23923950', 'user_first_name' => 'Michel', 'user_last_name' => 'Dubardin', 'ticket_type' => 'PASS 2 J', 'ticket_price' => 55.5, 'ticket_buy_date' => '26/02/2017']]);