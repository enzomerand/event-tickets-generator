<?php
	
	define('ROOT', dirname(__DIR__) . '/event-ticket/');
	define('TEMP', ROOT . 'temp/');

    require('../library/fpdf/fpdf.php');
	require('../library/php-barcode-generator/src/BarcodeGenerator.php');
    require('../library/php-barcode-generator/src/BarcodeGeneratorPNG.php');
	require('../library/QrCode/src/QrCode.php');
    require('../library/event-ticket/Ticket.php');
	require('../library/event-ticket/Generator.php');
	require('../library/event-ticket/Error.php');