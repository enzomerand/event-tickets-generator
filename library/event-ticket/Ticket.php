<?php
/**
 *  Ticket Class
 */

namespace EventTicket;

use Endroid\QrCode\QrCode;
use Picqer\Barcode\BarcodeGeneratorPNG;
use \ZipArchive;
 
/**
 *  Cette classe regroupe les fonctionnalitées principales de la librairie
 *  
 *  @author  Nyzo
 *  @version 1.0
 *  @license CC-BY-NC-SA-4.0 Creative Commons Attribution Non Commercial Share Alike 4.0
 */
class Ticket {
	
	private $importer, $generator, $codes, $zip, $qrcode, $barcode;
	
	public $event_logo, $event_name, $event_orga_name, $event_location, $path_tickets = 'tickets';
	
	/**
	 *  Initialiser la classe et charger les librairies. Certains paramètres généraux relatifs aux tickets peuvent être définit
	 */
	public function __construct($event_logo = null, $event_name = null, $event_orga_name = null, $event_location = null){
		$this->generator = new Generator;
		$this->zip       = new ZipArchive;
		$this->qrcode    = new QrCode();
		$this->barcode   = new BarcodeGeneratorPNG();
		
		$this->event_logo      = $event_logo;
		$this->event_name      = $event_name;
		$this->event_orga_name = $event_orga_name;
		$this->event_location  = $event_location;
		
		if(!is_dir(TEMP)) mkdir(TEMP);
		if(!is_dir('tickets')) mkdir('tickets');
	}
	
	/**
	 *  Supprimer le dossier temporaire et son contenu
	 */
	private function cleanTemp(){
		$files = glob(TEMP . '*');
		foreach($files as $file){
		    if(is_file($file))
			    unlink($file);
		}
		rmdir(TEMP);
	}
	
	/**
	 *  Initialiser le modèle du ticket au format PDF. Les paramètres généraux sont définit au préalable au sein de la classe
	 */
	public function setGenerator(){
		$this->generator->event_logo      = $this->event_logo;
		$this->generator->event_name      = $this->event_name;
		$this->generator->event_location  = $this->event_location;
		$this->generator->event_orga_name = $this->event_orga_name;
	}
	
	/**
	 *  Générer un ou plusieurs tickets au format PDF à partir d'un tableau. Ne retourne pas d'erreurs si un camps est manquant
	 *  
	 *  @param array  $tickets         Contient le(s) ticket(s) eux-même dans des tableaux
	 *  @param bool   $multiple_file   Permet d'enregistrer sous plusieurs fichiers chaque billets (pas de rendu)
	 *  @param bool   $display         Rendu direct du PDF (affichage dans la naviguateur en appelant la fonction)
	 *  @param string $ticket_template Template du ticket à utiliser
	 *  
	 *  return array Retourne le code de chaque ticket généré ainsi que l'information si les tickets sont dans un seul fichiers ou plusieurs
	 */
	public function genTickets($tickets, $multiple_file = false, $display = true, $ticket_template = 'BasicTicket'){
		$this->setGenerator();
		
		if(!method_exists($this->generator, $ticket_template))
			throw new \Exception('Le template n\'existe pas.');
		
		if(empty($tickets))
			throw new \Exception('Il n\'y a pas de tickets.');
		
		$tickets_code = [];
		foreach($tickets as $ticket){
			if(empty($ticket['ticket_code']))
			    throw new \Exception('Le ticket n\'a pas de code.');
		
			if(!empty($this->codes)){
				if(!in_array($ticket['ticket_code'], $this->codes))
					throw new \Exception('Des codes de tickets sont définis mais le ticket actuel n\'en a pas ou le code n\'est pas valide.');
			}
			
			$new_ticket = [];
			$new_ticket = [
				'ticket_code'     => $ticket['ticket_code'],
				'user_first_name' => isset($ticket['user_first_name']) ? $ticket['user_first_name'] : 'N/A',
				'user_last_name'  => isset($ticket['user_last_name']) ? $ticket['user_last_name'] : 'N/A',
				'event_date'      => isset($ticket['event_date']) ? date('d/m/Y H:i:s', strtotime($ticket['event_date'])) : 'N/A',
				'ticket_type'     => isset($ticket['ticket_type']) ? $ticket['ticket_type'] : 'N/A',
				'ticket_price'    => isset($ticket['ticket_price']) ? $ticket['ticket_price'] : 'N/A',
				'ticket_buy_date' => isset($ticket['ticket_buy_date']) ? $ticket['ticket_buy_date'] : 'N/A',
			];
			$ticket = $new_ticket;
			
			$this->generator->setInformations($ticket, $this->genQrCode($ticket['ticket_code']), $this->genBarCode($ticket['ticket_code']));
			$this->generator->AddPage();
			$this->generator->$ticket_template();
			
			$tickets_code[] = $ticket['ticket_code'];
			
			if($multiple_file === true){
				$this->generator->Output('F', 'tickets/' . $ticket['ticket_code'] . '.pdf');
				$this->generator = new Generator;
			    $this->setGenerator();
			}
		}
	
	    $tickets_code['multiple_file'] = true;
	    if($multiple_file === false){
			$file_name = 'tickets/tickets' . rand() . '.pdf';
			$this->generator->Output('F', $file_name);
			$tickets_code['multiple_file'] = $file_name;
		}
		
		if($multiple_file === false && $display === true)
			$this->generator->Output();
		
		$this->cleanTemp();
		
		return $tickets_code;
	}
	
	/**
	 *  Générer un QrCode
	 *  
	 *  @param string|int  $ticket_code Code du ticket
	 *  @param string|bool $link        Lien du QrCode (si le QrCode doit retourner vers un lien)
	 *  
	 *  @return string Lien image du QrCode
	 */
	private function genQrCode($ticket_code, $link = false){
		$ticket_code_file = $ticket_code . rand();
		$this->qrcode
			->setText($ticket_code)
			->setSize(200)
			->setPadding(20)
			->setErrorCorrection('high')
			->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
			->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
			->setImageType(QrCode::IMAGE_TYPE_PNG)
		;

		$this->qrcode->save(TEMP . $ticket_code_file . '.png');
		
		return TEMP . $ticket_code_file . '.png';
	}
	
	/**
	 *  Générer un code-barre
	 *  
	 *  @param string|int  $ticket_code Code du ticket
	 *  
	 *  @return string Lien image du code-barre
	 */
	private function genBarCode($ticket_code){
		$ticket_code_file = $ticket_code . rand();
		file_put_contents(TEMP . $ticket_code_file . '.png', $this->barcode->getBarcode($ticket_code, $this->barcode::TYPE_CODE_128));
		
		return TEMP . $ticket_code_file . '.png';
	}
	
	/**
	 *  Importer un/des ticket(s) dans un tableau, issu(s) d'un fichier CSV
	 *  
	 *  @param string|array $file Chemin du fichier à importer
	 */
	public function importTickets($file){
		$this->checkFile($file);
		
		$tickets = [];
		$i = 1;
		if(is_array($file)){
			foreach($file as $tickets){
				$current_file = fopen($tickets, 'r');
				while (($line = fgetcsv($tickets)) !== FALSE){
					if($i != 1) // Pour supprimer l'en-tête
						$tickets[] = $line;
					
					$i++;
				}
				fclose($tickets);
				$i = 1;
			}
		}else {
			$file = fopen($file, 'r');
			while (($line = fgetcsv($file)) !== FALSE){
				if($i != 1) // Pour supprimer l'en-tête
					$tickets[] = $line;
				
				$i++;
			}
			fclose($file);
		}
		
		
		return $tickets;
	}
	
	/**
	 *  Exporter un/des ticket(s) au format CSV, issu(s) d'un tableau
	 *  
	 *  @param array  $tickets   Tableau contenant le(s) ticket(s)
	 *  @param bool   $download  Définit si le fichier doit être directement téléchargé
	 *  @param bool   $save      Définit si le fichier doit être sauvegardé (sans être téléchargé)
	 *  @param bool   $head      Permet de définir un en-tête propre ou un en-tête "prêt à importer" (valeur par défaut recommandée)
	 *  @param string $separator Définit le séparateur pour les lignes (valeur par défaut recommandée)
	 */
	public function exportTickets($tickets, $download = true, $save = false, $head = true, $separator = ';'){
		$filename = 'tickets' . rand(100, 999) . '.csv';
		
		if($download === true){
			header("Content-Type: text/csv; charset=UTF-8");
		    header("Content-Disposition: attachment; filename=" . $filename);
		}
		
		$head = ($head === true) ? $head = ["id", "ticket_code", "user_first_name", "user_last_name", "event_date", "ticket_type", "ticket_price", "ticket_buy_date"] : ["", "Code", "Prénom", "Nom", "Date", "Type", "Prix", "Date d'achat"];
	    
		$cells = [];
		$i = 0;
		foreach($tickets as $ticket){
			if(!empty($this->codes)){
				if(!in_array($ticket['ticket_code'], $this->codes))
					throw new \Exception('Des codes de tickets sont définis mais le ticket actuel n\'en a pas ou le code n\'est pas valide.');
			}
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
		
		if($download === true){
			echo implode($separator, $head) . "\r\n";

			foreach ($cells as $cell)
				echo implode($separator, $cell) . "\r\n";
		}
			
		if($save === true || $download == false){
			file_put_contents($filename, implode($separator, $head));
			
			$fp = fopen($filename, 'w');
			
			fputcsv($fp, $head);

			foreach($cells as $cell)
				fputcsv($fp, $cell);

			fclose($fp);
		}
	}
	
	/**
	 *  Définir des codes de tickets valides. Ces codes seront comparés aux tickets générés afin de valider leurs authenticité
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
	public function downloadTickets($file = null, $tickets = null){
		if($file != null)
			$this->checkFile($file, 'pdf');
		elseif($tickets != null && is_array($tickets)){
			$file = $this->genTickets($tickets);
			$this->checkFile($file, 'pdf');
		}else throw new \Exception('Rien n\'a été envoyé pour le téléchargement');
		
		if(is_array($file)){
			$this->zip->open('tickets.zip', $this->zip::CREATE);
			foreach ($file as $tickets)
			    $this->zip->addFile($tickets);
			$this->zip->close();
			
			header('Content-Type: application/zip');
			header("Content-disposition: attachment; filename='tickets.zip'");
			header('Content-Length: ' . filesize('tickets.zip'));
			readfile('tickets.zip');
		}else {
			header("Content-type:application/pdf"); 
            header("Content-Disposition:attachment;filename='ticket.pdf'");
            readfile('tickets/' . $file . '.pdf');
		}
	}
	
	/**
	 *  Vérifier qu'un fichier existe et que son format est valide selon la demande
	 *  
	 *  @param string|array $file Chemin vers le fichier
	 *  @param string       $type Extension à vérifier
	 *  
	 *  @return true
	 */
	private function checkFile($file, $type = 'csv'){
		if(is_array($file)){
			foreach($file as $ticket){
				if(empty($ticket) && !file_exists('tickets/' . $ticket))
			        throw new \Exception('Le fichier n\'existe pas.');
				
				if(pathinfo('tickets/' . $ticket, PATHINFO_EXTENSION) != $type)
			        throw new \Exception("Le fichier n'est pas au format {$type}.");
			}
		}else {
			if(empty($file) && !file_exists('tickets/' . $file))
			    throw new \Exception('Le fichier n\'existe pas.');
			
			if(pathinfo('tickets/' . $file, PATHINFO_EXTENSION) != $type)
			    throw new \Exception("Le fichier n'est pas au format {$type}.");
		}
		
		return true;
	}
}