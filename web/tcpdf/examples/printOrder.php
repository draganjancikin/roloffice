<?php
$page = "nabavka";

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
$pdf->SetTitle('ROLOSTIL - Narudzbenica');
$pdf->SetSubject('Rolostil');
$pdf->SetKeywords('Rolostil, PDF, narudzbenica');

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
$order = new \Roloffice\Controller\OrderController();
$material = new \Roloffice\Controller\MaterialController();
    
$order_id = $_GET['order_id'];
$order_data = $order->getOrder($order_id);

$supplier_data = $entityManager->find('Roloffice\Entity\Client', $order_data['supplier_id']);
$supplier_country = $entityManager->find('\Roloffice\Entity\Country', $supplier_data->getCountry());
$supplier_city = $entityManager->find('\Roloffice\Entity\City', $supplier_data->getCity());
$supplier_street = $entityManager->find('\Roloffice\Entity\Street', $supplier_data->getStreet());

$supplier_contacts = $supplier_data->getContacts();

$contact_item[0] = "";
$contact_item[1] = "";

$i=0;
foreach ($supplier_contacts as $supplier_contact) {
    if($i < 2 && NULL !== $supplier_contact->getBody()) {
        $contact_item[$i] = $supplier_contact->getBody();
    } else {
        $contact_item[$i] = "";
    }
    $i++;
}

$html = '
<style type="text/css">table { padding-top: 5px; padding-bottom: 5px; }</style>

<table border="0">
  <tr>
    <td width="685px" colspan="3"><h1>ROLOSTIL szr</h1></td>
  </tr>
  <tr>
    <td width="340px" colspan="2">Vojvode Živojina Mišića 237<br />21400 Bačka Palanka<br />PIB: 100754526<br />MB: 5060100<br />žr. 220-127736-34, Procredit bank</td>
    
    <td width="350px">Dobavljač:<br />'.$supplier_data->getName().'<br />'.$supplier_street->getName().' '.$supplier_data->getHomeNumber().'<br />'.$supplier_city->getName().', '.$supplier_country->getName().'<br />'.$contact_item[0].', '.$contact_item[1].'</td>
  </tr>
  <tr>
    <td colspan="3"><h2>Narudžbenica: '.str_pad($order_data['o_id'], 3, "0", STR_PAD_LEFT).' - '.date('m', strtotime($order_data['date'])).'</h2></td>
  </tr>
  <tr>
    <td colspan="3">Datum i mesto: '.date('d M Y', strtotime($order_data['date'])).'.g. Bačka Palanka</td>
  </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '
<table border="1">
  <tr>
    <td width="35px">red.<br />broj</td>
    <th width="100px" align="center">šifra</th>
    <td width="440px" align="center">naziv proizvoda</td>
    <td width="45px" align="center">jed.<br />mere</td>
    <td width="65px" align="center">količina</td>
  </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

$count = 0;
$total_tax_base = 0;
$total_tax_amount = 0;
$total = 0;
$total_eur = 0;
$materials_on_order = $order->getMaterialsOnOrder($order_id);

foreach ($materials_on_order as $material_on_order):
    
    $propertys = $material_on_order['propertys'];
    $property_temp = '';
    $property_counter = 0;
    foreach ($propertys as $property):
        $property_counter ++;
        $property_name = $property['property_name'];
        $property_quantity = number_format($property['property_quantity'], 1, ",", ".");
        $property_temp = $property_temp . ( $property_counter==2 ? ' x ' : '' ) .$property_quantity . 'cm';
        // old $property_temp = $property_temp . ', ' .$property_name . ' ' .$property_quantity . ' cm';
        // $property_temp = $property_temp . ' x' .$property_quantity . ' cm';
    endforeach;
    
    $count++;
    
    $html = '
    <style type="text/css"> table{ padding: 0px; margin: 0px; }</style>
    <table border="0" style="font-size:14px">
      <tr>
        <td width="35px" align="center">'.$count.'</td>
        <td width="100px" class="center">' . $material_on_order['code'] . '</td>

        <td width="440px">'.$material_on_order['name']
            . ( $material_on_order['note'] == "" ? "" : ', ' .$material_on_order['note'] )
            .'<br />'.$property_temp. ' - ' .$material_on_order['pieces']. (($material_on_order['unit_name'] == "set" OR $material_on_order['unit_name'] == "par" ) ? " " .$material_on_order['unit_name'] : " kom " ).  '</td>
        <td align="center" width="45px">'.$material_on_order['unit_name'].'</td>
        <td width="65px" align="right">'.number_format($material_on_order['quantity'], 2, ",", ".").'</td>
        
      </tr>
    </table>
    ';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    
    $total_tax_base = $total_tax_base + $material_on_order['tax_base'];
    $total_tax_amount = $total_tax_amount + $material_on_order['tax_amount'];
    $total = $total_tax_base + $total_tax_amount;
    
endforeach;

$html = '
<style type="text/css"> table { padding: 0px; margin: 0px; }</style>
<table border="1">
  <tr>
    <td width="690px">Napomena:<br />'.nl2br($order_data['note']).'</td>
  </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
// $pdf->Output('narudzbenica.pdf', 'FI');

switch ($order_data['supplier_id']) {
    case 12:
        $folder = "ALUMIL NS/";
        break;
    case 126:
        $folder = "ALUPLAST/";
        break;
    case 1:
        $folder = "ALUROLL BG/";
        break;
    case 150:
        $folder = "AURA DEKOR/";
        break;
    case 3:
        $folder = "EKV/";
        break;
    case 289:
        $folder = "ENTUZIAST/";
        break;
    case 86:
        $folder = "FEROLNOR/";
        break;
    case 10:
        $folder = "GU/";
        break;
    case 20:
        $folder = "HELISA/";
        break;
    case 7:
        $folder = "INFOMARKET/";
        break;
    case 9:
        $folder = "LALIC LINE/";
        break;
    case 844:
        $folder = "LIBELA/";
        break;
    case 110:
        $folder = "MIGRO/";
        break;
    case 18:
        $folder = "MIREX/";
        break;
    case 725:
        $folder = "PORTA ROYAL/";
        break;
    case 19:
        $folder = "PRIVREDNO DRUSTVO METRO/";
        break;
    case 58:
        $folder = "PROFINE/";
        break;
    case 774:
        $folder = "ROLLPLAST/";
        break;
    case 16:
        $folder = "ROLOEXPRES/";
        break;
    case 4:
        $folder = "ROLOPLAST/";
        break;
    case 15:
        $folder = "ROLO REMONT/";
        break;
    case 125:
        $folder = "ROLOSTIL plus/";
        break;
    case 84:
        $folder = "ROLO-TIM/";
        break;
    case 5:
        $folder = "ROLO-TIM NS/";
        break;
    case 648:
        $folder = "SI-LINE/";
        break;
    case 57:
        $folder = "STAKLORAM plus/";
        break;
    case 113:
        $folder = "STUBLINA/";
        break;
    case 81:
        $folder = "TEHNI/";
        break;
    case 116:
        $folder = "TEHNOMARKET/";
        break;
    case 131:
        $folder = "TOMOVIC PLAST/";
        break;
    case 526:
        $folder = "VABIS/";
        break;
    case 91:
        $folder = "WURTH/";
        break;
    default:
        $folder = $supplier_data->getName() . ' - ';
}

$pdf->Output('D:/ROLOSTIL/PORUDZBINE/' .$folder.str_pad($order_data['o_id'], 3, "0", STR_PAD_LEFT). '-' .date('m', strtotime($order_data['date'])). ' - ' .date('d M', strtotime($order_data['date'])). '.pdf', 'FI');

//============================================================+
// END OF FILE                                                
//============================================================+
