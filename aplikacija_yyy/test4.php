<?php

// Autoload files using the Composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// namespace\class za vključitev
//use mojpak\Greetings;


/****
*       odločitev : fpdi za odpiranje obstoječega pdf; naložimo kot podlogo
*                   tcpdf za pisanje po obstoječem dokumentu
*
*              tdpcfFpdi naredi vse potrebno
*
*         na osnovi predloge zgenerira nov pdf, ga ne shrani na datotečni sistem temveč vrne
*         če potrebuješ shranjevanje na strežniku (verjetno ne), pa spremeni
*
*        --> nek mazohist je bil zelo prijazen, pa je že določil pozicije polj, ki se morajo
*            polniti iz baze; trenutno so tu konstante
*/


use setasign\Fpdi;

//----------------------------------------------- vse spodaj razen Fpdi že vključi avtoloader 
//require_once('tcpdf/tcpdf.php');
//require_once('fpdi2/src/autoload.php');


//$loader = new \Example\Psr4AutoloaderClass;
//$loader->register();
//$loader->addNamespace('setasign\Fpdi', 'path/to/src/');


/*
//use setasign\Fpdi\Fpdi;
// or for usage with TCPDF:
use setasign\Fpdi\Tcpdf\Fpdi;

// or for usage with tFPDF:
// use setasign\Fpdi\Tfpdf\Fpdi;

// setup the autoload function
require_once('vendor/autoload.php');
*/


class Pdf extends Fpdi\TcpdfFpdi
{
    /**
     * "Remembers" the template id of the imported page
     */
    protected $tplId;

    /**
     * Draw an imported PDF logo on every page
     */
    function Header()
    {
        if (is_null($this->tplId)) {
            //$this->setSourceFile('logo.pdf');
			$this->setSourceFile('SeminarskaMapaRacSM.pdf');
            $this->tplId = $this->importPage(1);
        }
        //$size = $this->useImportedPage($this->tplId, 130, 5, 60);

        $this->SetFont('freesans', 'B', 20);
        $this->SetTextColor(0);
        $this->SetXY(PDF_MARGIN_LEFT, 5);
        //$this->Cell(0, $size['height'], 'TCPDF and FPDI');
    }

    function Footer()
    {
        // emtpy method body
    }
}

/*

// initiate PDF
$pdf = new Pdf();
$pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(true, 40);

// add a page
$pdf->AddPage();

// get external file content
//$utf8text = file_get_contents('tcpdf/examples/data/utf8test.txt', true);
$utf8text = file_get_contents('utf8test.txt', true);

$pdf->SetFont('freeserif', '', 12);
// now write some text above the imported page
$pdf->Write(15, $utf8text);

$pdf->Output();

*/

// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// initiate FPDI
//$pdf = new Fpdi();
$pdf = new Fpdi\TcpdfFpdi(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// get the page count
//$pageCount = $pdf->setSourceFile('Laboratory-Report.pdf');
$pageCount = $pdf->setSourceFile('SeminarskaMapaRacSM.pdf');
// iterate through all pages
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    // import a page
    $templateId = $pdf->importPage($pageNo);

    $pdf->AddPage();
    // use the imported page and adjust the page size
    $pdf->useTemplate($templateId, ['adjustPageSize' => true]);

    //$pdf->SetFont('Helvetica');
    //$pdf->SetXY(50, 50);
    //$pdf->Write(8, 'A complete document imported with FPDI'.$pageNo);
	
	
	//call page number function ...
	//processPagenr_1();
	
	$function_name = 'processPagenr_'.$pageNo;
	$function_name();
	
}

// Output the new PDF
$pdf->Output();            


/**
     
    // Parse pdf file and build necessary objects.
    $parser = new \Smalot\PdfParser\Parser();
    $pdf    = $parser->parseFile('abcd.pdf');
     
    // Retrieve all pages from the pdf file.
    $pages  = $pdf->getPages();
     
    // Loop over each page to extract text.
    foreach ($pages as $page) {
         echo nl2br($page->getText());
		 //echo $page->getText();
		echo '<br />';
    }


	$skuArr = preg_split('/\r\n|\r|\n/', $page->getText());
	
	echo '<pre>';
	print_r($skuArr);
	echo '</pre>';
	

	$file = 'people.txt';

    file_put_contents($file, $page->getText());

	**/
	
//------------------------------------------------------------------------------


/**
*        stran 1 R49-1006 
*/
function processPagenr_1(){
	global $pdf;                   // move to method inside class ! - no need for global
	
	//$pdf->SetFont('Helvetica','',12); // $this->SetFont('helvetica', '', 14);
	$pdf->SetFont('freeserif','',12);
	$pdf->SetXY(54, 48);  //(horiz,vert)
	//$pdf->Write(0, 'Računalništvo in informatika');
	
	//$pdf->Ln(5);
    //$pdf->Cell(45, 0, 'Računalništvo in informatika', 1, 1, 'C', 0, '', 1);
	$pdf->Cell(45, 0, 'Računalništvo', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(117, 48);  $pdf->Cell(33, 0, '1991/31', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetXY(79, 61);  $pdf->Cell(110, 0, 'Ime Priimek Kandidata', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(79, 69);  $pdf->Cell(110, 0, 'Ime Priimek Mentorja', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetXY(81, 83);  $pdf->Cell(108, 0, 'Naslov seminarske 1', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(31, 92);  $pdf->Cell(158, 0, 'Naslov seminarske 2', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetXY(91, 101);  $pdf->Cell(98, 0, 'Datum prijave naslova', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetXY(54, 109);  $pdf->Cell(48, 0, 'Podpis mentorja', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(132, 109);  $pdf->Cell(57, 0, 'Podpis kandidata', 1, 1, 'C', 0, '', 1);
	
	
	$pdf->SetXY(81, 131);  $pdf->Cell(108, 0, 'Naslov seminarske 2-1', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(31, 140);  $pdf->Cell(158, 0, 'Naslov seminarske 2-2', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetXY(78, 149);  $pdf->Cell(111, 0, 'Ime in priimek mentorja-2', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetXY(54, 157);  $pdf->Cell(48, 0, 'Podpis mentorja-1', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(132, 157);  $pdf->Cell(57, 0, 'Podpis kandidata-1', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetFont('freeserif','',8);
	$pdf->SetXY(44, 166);
	$pdf->MultiCell(145, 15, '[JUSTIFY] '."opombe\nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);
	$pdf->SetFont('freeserif','',12);
	
	$pdf->SetXY(123, 185);  $pdf->Cell(66, 0, 'Naslov potrdila', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(117, 195);  $pdf->Cell(72, 0, 'Oddana dne', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(44, 204);  $pdf->Cell(72, 0, 'kandidat', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(55, 217);  $pdf->Cell(59, 0, 'Podpis kandidata-3', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(141, 217);  $pdf->Cell(48, 0, 'Podpis mentorja-3', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(98, 230);  $pdf->Cell(91, 0, 'Ocena semin naloge', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(145, 242);  $pdf->Cell(44, 0, 'po ugovoru', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(163, 248);  $pdf->Cell(26, 0, 'po pritožbi', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(73, 254);  $pdf->Cell(116, 0, 'če izboljšuje', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetFont('freeserif','',8);
	$pdf->SetXY(44, 261);
	$pdf->MultiCell(145, 15, '[JUSTIFY] '."opombe\nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);
	$pdf->SetFont('freeserif','',12);
	
}

/*****************************
*   
*   kronologija razgovorov s kandidatom - zabeležke
*      v 1.0                                             	- done               
*
*/
function processPagenr_2(){

    global $pdf; 
	
	$pdf->SetFont('freeserif','',10);
	
	$pdf->SetXY(30, 21);  //(horiz,vert)
		
	$pdf->MultiCell(160, 242, '[JUSTIFY] '."opombe\nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);
	$pdf->SetFont('freeserif','',12);
	 
}

/*****************************
*   
*   konzultacije 1,2
*      v 1.0                                              	- done
*
*/
function processPagenr_3(){
		global $pdf; 
		
		
	$pdf->SetXY(30, 33);
	$pdf->MultiCell(160, 80, '[JUSTIFY] '."opombe - konzult 1\nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);	
		
			
	$pdf->SetFont('freeserif','',12);
	$pdf->SetXY(41, 119);  //(horiz,vert)
	
	 $pdf->Cell(62, 0, '1 kon datum', 1, 1, 'C', 0, '', 1);
	
	 $pdf->SetXY(54, 128);  $pdf->Cell(49, 0, 'podp ment 1 k', 1, 1, 'C', 0, '', 1);
	 $pdf->SetXY(132, 128);  $pdf->Cell(57, 0, 'podp kand 1 k', 1, 1, 'C', 0, '', 1);
	 
	 
	 
	 
	 $pdf->SetXY(30, 163);
	 $pdf->MultiCell(160, 62, '[JUSTIFY] '."opombe - konzult -2- \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);	
	 
	 
	 $pdf->SetXY(41, 232);  $pdf->Cell(62, 0, '2kon datum', 1, 1, 'C', 0, '', 1);
	 $pdf->SetXY(54, 240);  $pdf->Cell(49, 0, 'podp ment 1 k', 1, 1, 'C', 0, '', 1);
	 $pdf->SetXY(132, 240);  $pdf->Cell(57, 0, 'podp kand 1 k', 1, 1, 'C', 0, '', 1);

}

function processPagenr_4(){
	
	// povozi obstoječe besedilo s pravokotnikom, ki ima belo polnilo !
	
	
	
}

/*******************
*   stran 5 - dodatno preverjanje znan. pred oc. sem. naloge
*          v 1.0    										- done
*/
function processPagenr_5(){
	
	global $pdf; 	
	$pdf->SetFont('freeserif','',12);
	
	
	 $pdf->SetFont('freeserif','',10);
     $pdf->SetXY(30, 30);
	 $pdf->MultiCell(160, 135, '[JUSTIFY] '."zabeležke mentorja \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);	 
	 $pdf->SetFont('freeserif','',12);
	
	 
	$pdf->SetXY(41, 176); $pdf->Cell(55, 0, 'datum', 1, 1, 'C', 0, '', 1);	
	$pdf->SetXY(64, 185);$pdf->Cell(54, 0, 'ime priimek mentorja', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(145, 185);$pdf->Cell(45, 0, 'podpis mentorja', 1, 1, 'C', 0, '', 1);	
	
	//--------------  komisija
	$pdf->SetXY(62, 210);$pdf->Cell(128, 0, 'ime in pri ucitelja komis 1', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(62, 218);$pdf->Cell(128, 0, 'podpis uc komiss 1', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(62, 227);$pdf->Cell(128, 0, 'ime in pri ucitelja komis 2', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(62, 235);$pdf->Cell(128, 0, 'podpis uc komiss 2', 1, 1, 'C', 0, '', 1);
	
}

/*******************
*   stran 6 - izboljševanje ocene sem naloge
*          v 1.0    										- done
*/
function processPagenr_6(){
	
	global $pdf; 
	
	$pdf->SetFont('freeserif','',12);
	$pdf->SetXY(126, 26);  //(horiz,vert)
	$pdf->Cell(61, 0, 'datum prijave poprave', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetXY(37, 34);$pdf->Cell(30, 0, 'leto', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(83, 34);$pdf->Cell(35, 0, 'izp. rok', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetXY(64, 43);$pdf->Cell(44, 0, 'ime in pri mentorja', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(133, 43);$pdf->Cell(55, 0, 'podpis mentorja', 1, 1, 'C', 0, '', 1);
	
	 $pdf->SetFont('freeserif','',10);
     $pdf->SetXY(30, 68);
	 $pdf->MultiCell(160, 101, '[JUSTIFY] '."zabeležke mentorja \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);	 
	 $pdf->SetFont('freeserif','',12);
	
	$pdf->SetXY(116, 176);$pdf->Cell(74, 0, 'oddana dne', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(44, 184);$pdf->Cell(73, 0, 'kandidat', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(55, 197);$pdf->Cell(59, 0, 'podpis kand', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(140,197);$pdf->Cell(47, 0, 'podpis mentorja', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(97, 205);$pdf->Cell(75, 0, 'nova ocena', 1, 1, 'C', 0, '', 1);
	
	
	 $pdf->SetFont('freeserif','',10);
     $pdf->SetXY(30, 221);
	 $pdf->MultiCell(160, 28, '[JUSTIFY] '."komentar k oceni \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);	 
	 
	 $pdf->SetXY(45, 251);
	 $pdf->MultiCell(145, 15, '[JUSTIFY] '."opombe \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);	
	 
	 
	 $pdf->SetFont('freeserif','',12);
	
	
	
	$pdf->SetXY(57, 267);$pdf->Cell(58, 0, 'podpis mentorja', 1, 1, 'C', 0, '', 1);
	
}


/*******************
*   stran 7 - dispozicija seminarske naloge
*          v 1.0    										- done
*/
function processPagenr_7(){
	
	global $pdf; 
	
	$pdf->SetFont('freeserif','',12);
	$pdf->SetXY(85, 31);  //(horiz,vert)
	$pdf->Cell(105, 0, 'ime in priimek kandidata - tiskano', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetXY(42, 41);$pdf->Cell(148, 0, 'naslov -1', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(30, 49);$pdf->Cell(160, 0, 'naslov - 2', 1, 1, 'C', 0, '', 1);
	
	
	$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    $pdf->Circle(31,70,3);			$pdf->Circle(62,70,3);
	$pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
	
	
	 $pdf->SetFont('freeserif','',10);
     $pdf->SetXY(30, 85);
	 $pdf->MultiCell(160, 28, '[JUSTIFY] '."cilji in prob. \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);	
	 
     $pdf->SetXY(30, 127);
	 $pdf->MultiCell(160, 28, '[JUSTIFY] '."izhodišča. \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);	


     $pdf->SetXY(30, 173);
	 $pdf->MultiCell(160, 28, '[JUSTIFY] '."metodologija \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);
	 
     $pdf->SetXY(30, 218);
	 $pdf->MultiCell(160, 39, '[JUSTIFY] '."literatura \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);
	 
	 
	 $pdf->SetFont('freeserif','',12);
	
	
	
	$pdf->SetXY(42, 264);$pdf->Cell(56, 0, 'datum', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(132, 264);$pdf->Cell(58, 0, 'podpis kandidata', 1, 1, 'C', 0, '', 1);
	
	
}


/*******************
*   stran 8 - dispozicija pri spremembi naslova seminarske naloge
*                            v 1.0										- done
*
*/
function processPagenr_8(){
	global $pdf; 
	
	$pdf->SetFont('freeserif','',12);
	$pdf->SetXY(85, 31);  //(horiz,vert)
	$pdf->Cell(105, 0, 'ime in priimek kandidata - tiskano', 1, 1, 'C', 0, '', 1);
	
	$pdf->SetXY(42, 41);$pdf->Cell(148, 0, 'naslov -1', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(30, 49);$pdf->Cell(160, 0, 'naslov - 2', 1, 1, 'C', 0, '', 1);
	
	
	$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    $pdf->Circle(31,74,3);			$pdf->Circle(62,74,3);
	$pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
	
	
	$pdf->SetFont('freeserif','',10);
     $pdf->SetXY(30, 89);
	 $pdf->MultiCell(160, 28, '[JUSTIFY] '."cilji in prob. \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);	
	 
	 $pdf->SetFont('freeserif','',10);
     $pdf->SetXY(30, 131);
	 $pdf->MultiCell(160, 28, '[JUSTIFY] '."izhodišča. \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);	

	 $pdf->SetFont('freeserif','',10);
     $pdf->SetXY(30, 177);
	 $pdf->MultiCell(160, 28, '[JUSTIFY] '."metodologija \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);
	 
	 $pdf->SetFont('freeserif','',10);
     $pdf->SetXY(30, 222);
	 $pdf->MultiCell(160, 39, '[JUSTIFY] '."literatura \nopombe2\nopombe3"."\n", 1, 'J', 0, 3, '' ,'', true);
	
	
	
	$pdf->SetXY(42, 268);$pdf->Cell(56, 0, 'datum', 1, 1, 'C', 0, '', 1);
	$pdf->SetXY(132, 268);$pdf->Cell(58, 0, 'podpis kandidata', 1, 1, 'C', 0, '', 1);
}

function processPagenr_9(){
}

function processPagenr_10(){
}
