<?php
$page = "pidb";

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') .'/../config/appConfig.php';
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') .'/../vendor/autoload.php';
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') .'/../config/bootstrap.php';

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Rolostil');
$pdf->SetTitle('ROLOSTIL - Dokument');
$pdf->SetSubject('Rolostil');
$pdf->SetKeywords('Rolostil, PDF, document');

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
// $pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// Print a table

// add a page
$pdf->AddPage();

// generisanje potrebnih objekata
$pidb = new \Roloffice\Controller\PidbController();
$article = new \Roloffice\Controller\ArticleController();

$pidb_id = $_GET['pidb_id'];
$pidb_data = $pidb->getPidb($pidb_id);

if( $pidb_data['tip_id'] == 2) $pidb_name = "Otpremnica - račun";

$html = '
<style type="text/css">table { padding-top: 5px; padding-bottom: 5px; }</style>

<table border="0">
  <tr>
    <td width="685px" colspan="3"><h1>ROLOSTIL szr</h1></td>
  </tr>
  <tr>
    <td width="340px" colspan="2">
    Vojvode Živojina Mišića 237<br />
    21400 Bačka Palanka<br />
    PIB: 100754526, MB: 55060100<br /> 
    ž.r. 160-438797-72, Banca Intesa<br />
    ž.r. 330-11001058-98, Credit Agricole</td>
  </tr>
  <tr>
    <td colspan="3"><h2>'.$pidb_name.' broj: '.str_pad($pidb_data['y_id'], 4, "0", STR_PAD_LEFT).' - '.date('m', strtotime($pidb_data['date'])).'</h2></td>
  </tr>
  <tr>
    <td colspan="3">Datum i mesto izdavanja: '.date('d M Y', strtotime($pidb_data['date'])).'.g. Bačka Palanka</td>
  </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '
<table border="1">
  <tr>
    <td width="30px" align="center">red.<br />br.</td>
    <td width="190px" align="center">naziv proizvoda</td>
    <td width="35px" align="center">jed.<br />mere</td>
    <td width="53px" align="center">kol.</td>
    <td width="70px" align="center">cena po<br />jed. mere</td>
    <td width="37px" align="center">rabat<br />%</td>
    <td width="80px" align="center">poreska<br />osnovica</td>
    <td width="37px" align="center">PDV<br />%</td>
    <td width="70px" align="center">iznos<br />PDV-a</td>
    <td width="80px" align="center">ukupno</td>
  </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

$count = 0;
$total_tax_base = 0;
$total_tax_amount = 0;
$total = 0;
$total_eur = 0;
$articles_on_pidb = $pidb->getArticlesOnPidb($pidb_id);

foreach ($articles_on_pidb as $article_on_pidb):
    
    $propertys = $article_on_pidb['propertys'];
    $property_temp = '';
    $property_counter = 0;
    foreach ($propertys as $property):
      $property_counter ++;
      $property_name = $property['property_name'];
      $property_quantity = number_format($property['property_quantity'], 2, ",", ".");
      $property_temp = $property_temp . ( $property_counter==2 ? 'x' : '' ) .$property_quantity . 'cm';
    endforeach;
    
    $count++;
    
    $html = '
    <style type="text/css"> table{ padding: 0px; margin: 0px; }</style>
    <table border="0">
      <tr>
        <td width="30px" align="center">' .$count. '</td>
        <td width="190px">' .$article_on_pidb['name'] . '<span style="font-size: 7">' . ( $article_on_pidb['note'] == "" ? "" : ', '.$article_on_pidb['note'] ) . '</span>'
          . '<br />' .$property_temp. ' ' .$article_on_pidb['pieces']. ' kom </td>
        <td align="center" width="35px">' .$article_on_pidb['unit_name']. '</td>
        <td width="53px" align="right">'.number_format($article_on_pidb['quantity'], 2, ",", "."). '</td>
        <td width="70px" align="right">' .number_format($article_on_pidb['price']*$pidb->getKurs(), 2, ",", "."). '</td>
        <td width="37px" align="right">' .number_format($article_on_pidb['discounts'], 2, ",", "."). '</td>
        <td width="80px" align="right">' .number_format($article_on_pidb['tax_base']*$pidb->getKurs(), 2, ",", "."). '</td>
        <td width="37px" align="right">' .number_format($article_on_pidb['tax'], 2, ",", ".").'</td>
        <td width="70px" align="right">' .number_format($article_on_pidb['tax_amount']*$pidb->getKurs(), 2, ",", "."). '</td>
        <td width="80px" align="right">' .number_format($article_on_pidb['sub_total']*$pidb->getKurs(), 2, ",", "."). '</td>
      </tr>
    </table>
    ';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    
    $total_tax_base = $total_tax_base + $article_on_pidb['tax_base'];
    $total_tax_amount = $total_tax_amount + $article_on_pidb['tax_amount'];
    $total = $total_tax_base + $total_tax_amount;
    
endforeach;

$html = '
<style type="text/css">table {	padding: 0px; margin: 0px; }</style>

<table><tr><td width="685px" colspan="10" style="border-bottom-width: inherit;"></td></tr></table>

<table border="0">
  <tr>
    <td colspan="3" width="270px"></td>
    <td colspan="2" width="135px" style="border-bottom-width: inherit;">ukupno poreska osnovica</td>
    <td colspan="2" width="100px" align="right" style="border-bottom-width: inherit;">'.number_format($total_tax_base*$pidb->getKurs(), 2, ",", ".").'</td>
    <td colspan="2" width="105px"></td><td width="80px"></td>
  </tr>
  <tr>
    <td colspan="3"></td>
    <td colspan="4" style="border-bottom-width: inherit;">ukupno iznos PDV-a</td>
    <td colspan="2" align="right" style="border-bottom-width: inherit;">'.number_format($total_tax_amount*$pidb->getKurs(), 2, ",", ".").'</td>
    <td></td>
  </tr>
  <tr style="font-weight:bold;">
    <td colspan="3"></td>
    <td colspan="5" style="border-bottom-width: inherit;">UKUPNO ZA UPLATU</td>
    <td colspan="2" align="right" style="border-bottom-width: inherit;">'.number_format($total*$pidb->getKurs(), 2, ",", ".").'</td>
  </tr>
  <tr>
    <td colspan="3"></td>
    <td colspan="5"></td>
    <td colspan="2" align="right">( &#8364; '.number_format($total, 2, ",", ".").' )</td>
  </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

$html = ''.($pidb_data['tip_id'] == 2 ? "" : '
<style type="text/css">table { padding: 0px; margin: 0px; }</style>
<table>
  <tr><td width="105px">Slovima: </td><td width="580px" style="background-color: #E0E0E0;"></td></tr>
  <tr><td>Način plaćanja: </td>       <td style="background-color: #E0E0E0;">Virmanom - nalogom za prenos</td></tr>
  <tr><td>Rok plaćanja: </td>         <td style="background-color: #E0E0E0;"></td></tr>
  <tr><td>Poziv na broj: </td>        <td style="background-color: #E0E0E0;">'.str_pad($pidb_data['y_id'], 3, "0", STR_PAD_LEFT).' - '.date('m', strtotime($pidb_data['date'])).'</td></tr>
  <tr><td></td></tr>
</table>
').'
<table border="1">
  <tr><td width="685px">Napomena:<br />'.nl2br($pidb_data['note']).'</td></tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

if($pidb_data['tip_id'] == 2){
  $html = '
  <table>
    <tr><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td></tr>
    <tr><td>______________________</td><td></td><td align="right">______________________</td></tr>
    <tr><td>robu izdao</td><td></td><td align="right">robu primio</td></tr>
    <tr><td></td><td></td><td></td></tr>
    <tr><td></td><td></td><td></td></tr>
    <tr><td align="center"></td><td></td><td align="center"></td></tr>
  </table>
  ';

  $pdf->writeHTML($html, true, false, true, false, '');
}else{
  $html = '
  <table>
    <tr><td></td><td></td></tr>
    <tr><td></td><td></td></tr>
    <tr><td align="right"></td><td align="center">___________________________<br />odgovorno lice</td></tr>
  </table>
  ';
  $pdf->writeHTML($html, true, false, true, false, '');
}

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output( $pidb_name .'_'. str_pad($pidb_data['y_id'], 4, "0", STR_PAD_LEFT) .'-'. date('m', strtotime($pidb_data['date'])) .'.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
?>
