<?php
/**
 *  Generator Class
 */

namespace EventTicket;
 
/**
 *  Cette classe permet de générer le(s) ticket(s) au format PDF
 *  Des fonctions peuvent être créées, chaque fonction représente un template
 *  
 *  @author  Nyzo
 *  @version 1.0
 *  @license CC-BY-NC-SA-4.0 Creative Commons Attribution Non Commercial Share Alike 4.0
 */
class Generator extends \FPDF {
	
	public $event_logo, $event_name, $font = 'Arial';
	
	private $infos, $qr_code, $barcode;
	
	/**
	 *  Définir les informations uniques et différentes pour chaque tickets, qui s'afficheront sur le ticket
	 *  
	 *  @param array  $infos   Contient les informations, formattées
	 *  @param string $qr_code Le lien image du QrCode 
	 *  @param string $barcode Le lien image du code barre 
	 */
	public function setInformations($infos, $qr_code, $barcode){
		$this->infos   = (object) $infos;
		$this->qr_code = $qr_code;
		$this->barcode = $barcode;
	}
	
	/**
	 *  Créer le PDF selon des paramètres
	 *  Template basique (par défaut)
	 */
	public function BasicTicket() {
		$this->SetFillColor(192);
		$this->Rect(0, 40, 1000, 80, 'F');
		
		$this->Image($this->event_logo, 80, 13, 50);
		
		$this->Image($this->qr_code, 140, 50, 45);
		$this->Image($this->barcode, 140, 100, 45);
		$this->SetFont($this->font, '', 10);
		$this->Text(140, 115, $this->infos->ticket_code);
		
		
		$this->SetFont($this->font, 'B', 15);
		$this->Cell(80);
	}
}