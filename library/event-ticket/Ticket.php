<?php
/**
 *  Ticket Class
 */

namespace EventTicket;
 
/**
 *  Cette classe regroupe les fonctionnalitées principales de la librairie
 *  
 *  @author  Nyzo
 *  @version 1.0
 *  @license CC-BY-NC-SA-4.0 Creative Commons Attribution Non Commercial Share Alike 4.0
 */
class Ticket {
	
	private $importer, $generator, $codes, $zip;
	
	public $event_logo, $event_name, $event_orga_name, $event_location, $path_tickets = 'tickets';
	
	/**
	 *  Initialise la classe et charge les librairies. Certains paramètres généraux relatifs aux tickets peuvent être définit
	 */
	public function __construct($event_logo = null, $event_name = null, $event_orga_name = null, $event_location = null){
		$this->generator = new Generator;
		$this->zip       = new \ZipArchive();
		
		$this->event_logo      = $event_logo;
		$this->event_name      = $event_name;
		$this->event_orga_name = $event_orga_name;
		$this->event_location  = $event_location;
	}
	
	/**
	 *  Initialise le modèle du ticket au format PDF. Les paramètres généraux sont définit au préalable au sein de la classe
	 */
	public function setGenerator(){
		$this->generator->logo = $this->event_logo;
	}
	
	/**
	 *  Générer un unique ticket au format PDF
	 *  
	 *  @param string $user_first_name Le prénom du détenteur du billet
	 *  @param string $user_last_name  Le nom du détenteur du billet
	 *  @param string $event_date      La date de l'événement
	 *  @param string $ticket_type     Le type de ticket (peut-être vide)
	 *  @param string $ticket_price    Le prix du ticket (si vide, gratuit)
	 *  @param string $ticket_buy_date La date d'achat du ticket
	 *  @param string $ticket_code     Le code du ticket (servira à générer le code barre)
	 */
	public function genTicket($user_first_name, $user_last_name, $event_date, $ticket_type, $ticket_price, $ticket_buy_date, $ticket_code){
		if(!empty($this->codes)){
			if(!in_array($ticket_code, $this->codes))
				throw new Exception('Des codes de tickets sont définis mais le ticket actuel n\'en a pas ou le code n\'est pas valide.');
		}
		
		$this->generator->Output();
		
	}
	
	/**
	 *  Générer un ou plusieurs tickets au format PDF à partir d'un tableau. Ne retourne pas d'erreurs si un camps est manquant
	 *  
	 *  @param array $tickets Contient le(s) ticket(s) eux-même dans des tableaux
	 *  
	 *  return array Retourne le nom de chaque ticket généré
	 */
	public function genTickets($tickets){
		$this->setGenerator();
		
		$tickets_file_name = [];
		foreach($tickets as $ticket){
			if(!empty($this->codes)){
				if(!in_array($ticket->ticket_code, $this->codes))
					throw new Exception('Des codes de tickets sont définis mais le ticket actuel n\'en a pas ou le code n\'est pas valide.');
			}
			
			$tickets_file_name[] = rand(10000, 99999);
		}
		
		return $tickets_file_name;
	}
	
	/**
	 *  Importer un/des ticket(s) dans un tableau, issu(s) d'un fichier CSV
	 *  
	 *  @param string $file Chemin du fichier à importer
	 */
	public function importTicket($file){
		$this->checkFile($file);
		
		$tickets = [];
		$i = 1;
		$file = fopen($file, 'r');
		while (($line = fgetcsv($file)) !== FALSE){
			if($i != 1)
		        $tickets[] = $line;
			
			$i++;
		}
		fclose($file);
		
		return $tickets;
	}
	
	/**
	 *  Exporter un/des ticket(s) au format CSV, issu(s) d'un tableau
	 *  
	 *  @param array  $tickets   Tableau contenant le(s) ticket(s)
	 *  @param bool   $head      Permet de définir un en-tête propre ou un en-tête "prêt à importer" (valeur par défaut recommandée)
	 *  @param string $separator Définit le séparateur pour les lignes (valeur par défaut recommandée)
	 */
	public function exportTickets($tickets, $head = true, $separator = ';'){
		header("Content-Type: text/csv; charset=UTF-8");
		header("Content-Disposition: attachment; filename=tickets.csv");
		
		$head = ($head === true) ? $head = ["id", "ticket_code", "user_first_name", "user_last_name", "event_date", "ticket_type", "ticket_price", "ticket_buy_date"] : ["", "Code", "Prénom", "Nom", "Date", "Type", "Prix", "Date d'achat"];
	    
		$cells = [];
		$i = 0;
		foreach($tickets as $ticket){
			$new_ticket = [];
			$new_ticket['id'] = $i;
			$new_ticket = [
			    'id' => $i,
				'ticket_code'     => isset($ticket['ticket_code']) ? $ticket['ticket_code'] : null,
				'user_first_name' => isset($ticket['user_first_name']) ? $ticket['user_first_name'] : null,
				'user_last_name'  => isset($ticket['user_last_name']) ? $ticket['user_last_name'] : null,
				'event_date'      => isset($ticket['event_date']) ? date('d/m/Y H:i:s', strtotime($ticket['event_date'])) : null,
				'ticket_type'     => isset($ticket['ticket_type']) ? $ticket['ticket_type'] : null,
				'ticket_price'    => isset($ticket['ticket_price']) ? $ticket['ticket_price'] : null,
				'ticket_buy_date' => isset($ticket['ticket_buy_date']) ? $ticket['ticket_buy_date'] : null,
			];
			$cells[] = $new_ticket;
			$i++;
		}
		
		echo implode($separator, $head) . "\r\n";

        foreach ($cells as $cell) {
	        echo implode($separator, $cell) . "\r\n";
        }
	}
	
	/**
	 *  Définit des codes de tickets valides. Ces codes seront comparés aux tickets générés afin de valider leurs authenticité
	 *  
	 *  @param array $codes Tableau contenant les codes valides
	 */
	public function setCodes($codes){
		$this->codes = $codes;
	}
	
	/**
	 *  Récupérer la liste des codes valides
	 *  
	 *  @return array Retourne la liste des codes
	 */
	public function getCodes(){
		return $this->codes;
	}
	
	/**
	 *  Télécharger le(s) ticket(s) au format PDF. Si plusieurs tickets sont à télécharger, ils seront regroupés dans un dossier compressé au format ZIP
	 *  
	 *  @param string $file    Chemin vers le(s) fichier(s) PDF à télécharger (si déjà enregistrés)
	 *  @param array  $tickets Ticket(s) provenant d'un tableau à télécharger directement
	 *  
	 *  @see genTickets()
	 */
	public function downloadTicket($file = null, $tickets = null){
		if($file != null){
			$this->checkFile($file);
		}else {
			$file = $this->genTickets($tickets);
		}
		
		if(is_array($file)){
			$this->zip->open('tickets.zip', ZipArchive::CREATE);
			foreach ($file as $tickets) {
			  $zip->addFile($tickets);
			}
			$this->zip->close();
			
			header('Content-Type: application/zip');
			header("Content-disposition: attachment; filename='tickets.zip'");
			header('Content-Length: ' . filesize('tickets.zip'));
			readfile('tickets.zip');
		}else {
			header("Content-type:application/pdf"); 
            header("Content-Disposition:attachment;filename='ticket.pdf'");
            readfile($this->path_tickets . $file . '.pdf');
		}
	}
	
	/**
	 *  Vérifier qu'un fichier existe et que son format est valide selon la demande
	 *  
	 *  @param string $file Chemin vers le fichier
	 *  @param string $type Extension à vérifier
	 */
	private function checkFile($file, $type = 'csv'){
		if(empty($file) && !file_exists($file))
			throw new Exception('Le fichier n\'existe pas.');
		
		if(pathinfo($file, PATHINFO_EXTENSION) != $type)
			throw new Exception("Le fichier à importer n'est pas au format {$type}.");
		
		return true;
	}
}