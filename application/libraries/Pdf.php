<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Dompdf\Dompdf;
class Pdf {
 
    public function __construct() {
    	// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml('hello world');

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'landscape');

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream();
    }
 
} 