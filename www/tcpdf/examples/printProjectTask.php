<?php
$page = "project";
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Rolostil');
$pdf->SetTitle('ROLOSTIL - Radni nalog');
$pdf->SetSubject('Rolostil');
$pdf->SetKeywords('Rolostil, PDF,radni nalog');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

// add a page
$pdf->AddPage();


// potreban je konfiguracioni fajl
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/../app/config/conf.php';

// potrebna je klasa koja vrši konekciju na bazu na bazu
// require $root . '/../app/classes/DB.class.php';
// require $root . '/../app/classes/Conf.class.php';

// potrebna je klasa Client
// require $root . '/../src/client/classes/Client.php';

// potrebna je klasa Contact
// require $root . '/../src/client/classes/Contact.php';

// potrebna je klasa Task
// require $root . '/../src/task/classes/Task.php';

// potrebna je klasa Project
// require $root . '/../src/project/classes/Project.php';



// generisanje potrebnih objekata
    $conf = new Conf();
    $client = new Client();
    $contact = new Contact();
    // $task = new Task();
    $project = new Project();
    $date = date('d M Y');

$project_id = $_GET['project_id'];

$project_data = $project->getProject($project_id);
$client_data = $client->getClient($project_data['client_id']);

    $contacts = $contact->getContactsById($project_data['client_id']);
    
    $phone1 = "";
    $phone1_note = "";
    $phone2 = "";
    $phone2_note = "";
    
    if (!empty($contacts)) {
        
        $count = 1;
        foreach ($contacts as $contact):
            
            if (isset($contact['number']) AND $count == 1 ){ 
                $phone1 = $contact['number'];
                $phone1_note = $contact['note'];
            }
           
            if (isset($contact['number']) AND $count == 2 ){ 
                $phone2 = $contact['number'];
                $phone2_note = $contact['note'];
            }
         
           $count++; 
        endforeach;
        
    }
    



$html = '
<style type="text/css">table {padding: 3px 10px 3px 10px; }</style>

<table border="0">
  <tr><td width="150px"><h3>RADNI NALOG </h3> </td><td width="400px">za projekat #'.str_pad($project_data['pr_id'], 4, "0", STR_PAD_LEFT).'</td><td>'.date('d-M-Y', strtotime($project_data['date'])).'</td></tr>
</table>

<table border="0">
  <tr><td width="80px">klijent:</td> <td width="auto">'.$client_data['name'] . ($client_data['name_note']<>""?', '.$client_data['name_note']:"").'</td></tr>
  <tr><td>adresa:</td>               <td>'.$client_data['street_name'].' '.$client_data['home_number'].', '.$client_data['city_name'].', '.$client_data['state_name'].', '.$client_data['adress_note'].'</td></tr>
  <tr><td></td>                      <td>' .$phone1. ', ' .$phone1_note. '</td></tr>
  ' .( $phone2=="" ? "" : '<tr><td></td><td>' .$phone2. ', ' .$phone2_note. '</td></tr>' ). '
  
</table>

<table border="1">
  <tr><td>'.$project_data['title'].'</td></tr>
  
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');



// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('nalog_' .$client_data['name']. '.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
