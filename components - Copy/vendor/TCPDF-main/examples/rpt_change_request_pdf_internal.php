<?php
global $DBCR;
//============================================================+
// File name   : example_048.php
// Begin       : 2009-03-20
// Last Update : 2013-05-14
//
// Description : Example 048 for TCPDF class
//               HTML tables and table headers
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: HTML tables and table headers
 * @author Nicola Asuni
 * @since 2009-03-20
 */
//

// Regional Setting
date_default_timezone_set("Asia/Jakarta");

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{
    //Page header
    public function Header()
    {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = K_PATH_IMAGES . 'cr.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('FORM CHANGE REQUEST');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// add a page
$pdf->AddPage();

// // set font
// $pdf->SetFont('helvetica', '', 8);

// // Document Number
// $pdf->Write(0, 'Form-PS-04 Rev.07', '', 0, 'R', true, 0, false, false, 0);

// // set font
// $pdf->SetFont('helvetica', 'B', 14);

// // Text SERVICE BUDGET FORM
// $pdf->Write(0, 'FORM CHANGE REQUEST', '', 0, 'C', true, 0, false, false, 0);

// set font
$pdf->SetFont('helvetica', '', 8);

// // -- set new background ---

// // get the current page break margin
// $bMargin = $pdf->getBreakMargin();
// // get current auto-page-break mode
// $auto_page_break = $pdf->getAutoPageBreak();
// // disable auto-page-break
// $pdf->SetAutoPageBreak(false, 0);
// // set bacground image
// $img_file = K_PATH_IMAGES.'image_demo.jpg';
// $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
// // restore auto-page-break status
// $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// // set the starting point for the page content
// $pdf->setPageMark();

// -----------------------------------------------------------------------------

include("../../../../applications/connections/connections.php");
if ($_GET['type'] == "IT" || $_GET['type'] == "Service Budget") {
    echo "<script>alert('Mohon klik logo PDF yang satu lagi');</script>";
}
$database = "sa_ps_service_budgets";
$DBSB = new mysqli($hostname, $username, $password, $database);
$database = "sa_md_hcm";
$DBHCM = new mysqli($hostname, $username, $password, $database);
$database = "sa_change_request";
$DBCR = new mysqli($hostname, $username, $password, $database);

$mysql = "SELECT * FROM `sa_general_informations` WHERE `project_code`='" . $_GET['project_code'] . "' AND `cr_no`='" . $_GET['cr_no'] . "' ORDER BY gi_id DESC";
$qproject = mysqli_query($DBCR, $mysql) or die($DBCR->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBCR->error . "<br/>");
$dproject = mysqli_fetch_assoc($qproject);

$mysql1 = "SELECT * FROM `sa_change_cost_plans` WHERE `cr_no`='" . $_GET['cr_no'] . "'  ORDER BY ccp_id DESC";
$qproject1 = mysqli_query($DBCR, $mysql1) or die($DBCR->errno . "-" . "[open_db][mysqli_query][" . $mysql1 . "] - " . $DBCR->error . "<br/>");
$dproject1 = mysqli_fetch_assoc($qproject1);
$tproject1 = mysqli_num_rows($qproject1);

$mysql2 = "SELECT * FROM `sa_assesments` WHERE `cr_no`='" . $_GET['cr_no'] . "' ORDER BY assesment_id DESC";
$qproject2 = mysqli_query($DBCR, $mysql2) or die($DBCR->errno . "-" . "[open_db][mysqli_query][" . $mysql2 . "] - " . $DBCR->error . "<br/>");
$dproject2 = mysqli_fetch_assoc($qproject2);

$mysql3 = "SELECT * FROM `sa_implementation_plans` WHERE `cr_no`='" . $_GET['cr_no'] . "' ORDER BY ip_id DESC";
$qproject3 = mysqli_query($DBCR, $mysql3) or die($DBCR->errno . "-" . "[open_db][mysqli_query][" . $mysql3 . "] - " . $DBCR->error . "<br/>");
$dproject3 = mysqli_fetch_assoc($qproject3);

$mysql4 = "SELECT * FROM `sa_detail_plans` WHERE `cr_no`='" . $_GET['cr_no'] . "' ORDER BY ip_id DESC";
$qproject4 = mysqli_query($DBCR, $mysql4) or die($DBCR->errno . "-" . "[open_db][mysqli_query][" . $mysql4 . "] - " . $DBCR->error . "<br/>");
$dproject4 = mysqli_fetch_assoc($qproject4);
$tproject4 = mysqli_num_rows($qproject4);
if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance") {
    $tbl = '
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <!-------------------------->
    <!-- General Information -->
    <!-------------------------->
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td width="100%">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #aaa; background-color:#8B0000; color:white;"><b>General Information</b></td>
                </tr>
                <tr>
                    <td>
                <table cellspacing="0" cellpadding="0" border="0" width="97%">
                <tr>
                    <th colspan="2" style=" text-align: center; background-color:#eaeaea; border-top: 0.2em solid #aaa; border-bottom: 0.2em solid #aaa">Project Information</th>
                </tr>
                <tr>
                    <td>Nama Project</td>
                    <td>' . $dproject['project_name'] . '</td>
                </tr>
                <tr>
                    <td>No.KP</td>
                    <td>' . $dproject['project_code'] . '</td>
                </tr>
                <tr>
                    <td>SO Number</td>
                    <td>';

    $mysql = 'SELECT * FROM sa_general_informations WHERE so_number="' . $_GET['so_number'] . '"';
    $qsbnumber = mysqli_query($DBCR, $mysql) or die($DBCR->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBCR->error . "<br/>");
    $dsbnumber = mysqli_fetch_assoc($qsbnumber);
    $tsbnumber = mysqli_num_rows($qsbnumber);

    if ($tsbnumber > 0) {
        $tbl .= $dsbnumber['so_number'];
    } else {
        $tbl .= '';
    }

    $tbl .= '
                    </td>
                </tr>
                <tr>
                    <td>Nama Customer</td>
                    <td>' . $dproject['customer'] . '</td>
                </tr>
                <tr>
                    <td>Type of Service</td>
                    <td>' . $dproject['type_of_service'] . '</td>
                </tr>
                <tr>
                    <td>Project Manager</td>
                    <td>' . $dproject['project_manager'] . '</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                </table>
                    </td>
                    <td width="4%"></td>
                    <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="99%">
                <tr>
                    <th colspan="2" style=" text-align: center; background-color:#eaeaea; border-top: 0.2em solid #aaa; border-bottom: 0.2em solid #aaa">CR Information</th>
                </tr>
                <tr>
                    <td>Change Request No</td>
                    <td>' . $dproject['cr_no'] . '</td>
                </tr>
                <tr>
                    <td>Change Request Date</td>
                    <td>' . $dproject['request_date'] . '</td>
                </tr>
                <tr>
                    <td>Requested By</td>
                    <td>' . $dproject['requested_by_email'] . '</td>
                </tr>
                <tr>
                    <td>Classification</td>
                    <td>' . $dproject['classification'] . '</td>
                </tr>
                <tr>
                    <td>Impact</td>
                    <td>' . $dproject['impact_it'] . '</td>
                </tr>
                <tr>
                    <td>Scope of Change</td>
                    <td>' . $dproject['scope_of_change'] . '</td>
                </tr>
                <tr>
                    <td>Description of Reason for Change</td>
                    <td>' . $dproject['reason'] . '</td>
                </tr>
                <tr>
                    <td>Description of Change Impact</td>
                    <td>' . $dproject['impact'] . '</td>
                </tr>
                <tr>
                    <td>Printed by</td>
                    <td>' . $dproject['requested_by_email'] . '</td>
                </tr>
                <tr>
                    <td>Printed Date</td>
                    <td>' . date("d M Y H:i:s") . '</td>
                </tr>
                </table>
                </td>
                </tr>    
                <tr>
                        <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td colspan="2" style="border-bottom:1px solid #aaa; background-color:#8B0000; color:white;"><b>Change Cost Plan</b></td>
            </tr>
            <tr>
                <td>Cost Type</td>
                <td>' . $dproject1['cost_type'] . '</td>
            </tr>
            <tr>
                <td>Non-Chargeable Cost Responsibility of</td>
                <td>' . $dproject1['responsibility'] . '</td>
            </tr>
            <tr>
                <td>Sales Name</td>
                <td>' . $dproject1['sales_name'] . '</td>
            </tr>
            <tr>
                <td>Nomor PO</td>
                <td>' . $dproject1['nomor_po'] . '</td>
            </tr>
            <tr>
                <td>Change Reason</td>
                <td>' . $dproject1['change_reason'] . '</td>
            </tr>
            <tr>
                <td>Detail Reason</td>
                <td>' . $dproject1['detail_reason'] . '</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
            <td colspan="4" style="border-bottom:1px solid #aaa; color:black;"><b>Mandays</b></td>
            </tr>
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <th style="background-color:#eaeaea; border-top: 0.2em solid #aaa; border-bottom: 0.2em solid #aaa">Type of Resource</th>
                    <th style="background-color:#eaeaea; border-top: 0.2em solid #aaa; border-bottom: 0.2em solid #aaa">Total Mandays</th>
                    <th style="background-color:#eaeaea; border-top: 0.2em solid #aaa; border-bottom: 0.2em solid #aaa">Nilai Mandays</th>
                </tr>
                ';

    $mysqlman = "SELECT * FROM `sa_mandays` WHERE `cr_no`='" . $_GET['cr_no'] . "'  ORDER BY mandays_id DESC";
    $qprojectman = mysqli_query($DBCR, $mysqlman) or die($DBCR->errno . "-" . "[open_db][mysqli_query][" . $mysqlman . "] - " . $DBCR->error . "<br/>");
    $dprojectman = mysqli_fetch_assoc($qprojectman);
    $tprojectman = mysqli_num_rows($qprojectman);

    if ($tprojectman > 0) {
        do {
            $tbl .= '
                <tr>
                    <td style="border-bottom:1px solid #aaa; background-color:#E0FFFF;">' . $dprojectman['type_of_resources'] . '</td>
                    <td style="border-bottom:1px solid #aaa; background-color:#E0FFFF;">' . $dprojectman['mandays_total'] . '</td>
                    <td style="border-bottom:1px solid #aaa; background-color:#E0FFFF;">' . $dprojectman['mandays_value'] . '</td>
                </tr>
                ';
        } while ($dprojectman = mysqli_fetch_assoc($qprojectman));
    } else {
        $tbl .= '
                <tr>
                    <td style="border-bottom:1px solid #aaa; background-color:#E0FFFF;">-</td>
                    <td style="border-bottom:1px solid #aaa; background-color:#E0FFFF;">-</td>
                    <td style="border-bottom:1px solid #aaa; background-color:#E0FFFF;">-</td>
                </tr>
                        ';
    }
    $tbl .= '
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
                <tr>
                    <td colspan="4" style="border-bottom:1px solid #aaa; color:black;"><b>Others</b></td>
                </tr>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <th style="background-color:#eaeaea; border-top: 0.2em solid #aaa; border-bottom: 0.2em solid #aaa">Item</th>
                    <th style="background-color:#eaeaea; border-top: 0.2em solid #aaa; border-bottom: 0.2em solid #aaa">Rp. </th>
                </tr>';

    $mysqlwork = 'SELECT * FROM sa_financial_others WHERE cr_no="' . $_GET['cr_no'] . '"';
    $qwork = mysqli_query($DBCR, $mysqlwork) or die($DBCR->errno . "-" . "[open_db][mysqli_query][" . $mysqlwork . "] - " . $DBCR->error . "<br/>");
    $dwork = mysqli_fetch_assoc($qwork);
    $twork = mysqli_num_rows($qwork);

    if ($twork > 0) {
        do {
            $tbl .= '
            <tr>
                <td style="border-bottom:1px solid #aaa; background-color:#E0FFFF;">' . $dwork['item'] . '</td>
                <td style="border-bottom:1px solid #aaa; background-color:#E0FFFF;">' . $dwork['value'] . '</td>
            </tr>
            ';
        } while ($dwork = mysqli_fetch_assoc($qwork));
    } else {
        $tbl .= '
            <tr>
                <td style="border-bottom:1px solid #aaa; background-color:#E0FFFF;">-</td>
                <td style="border-bottom:1px solid #aaa; background-color:#E0FFFF;">-</td>
            </tr>
                    ';
    }
    $tbl .= '
                </table>
                <tr>
                    <td>&nbsp;</td>
                </tr>
        </table>


            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #aaa; background-color:#8B0000; color:white;"><b>Change Request Closing</b></td>
                </tr>
                <tr>
                    <td>Hasil Akhir</td>
                    <td>';

    $mysqlprere = 'SELECT * FROM sa_change_request_closing WHERE cr_no="' . $_GET['cr_no'] . '"';
    $qprere = mysqli_query($DBCR, $mysqlprere) or die($DBCR->errno . "-" . "[open_db][mysqli_query][" . $mysqlprere . "] - " . $DBCR->error . "<br/>");
    $dprere = mysqli_fetch_assoc($qprere);
    $tprere = mysqli_num_rows($qprere);

    if ($tprere > 0) {
        $tbl .= $dprere['hasil_akhir'];
    } else {
        $tbl .= '-';
    }
    $tbl .= '</td>
                </tr>    
                <tr>
                    <td>Status Closing</td>
                    <td>' . $dproject['change_request_status'] . '</td>
                </tr>
                <tr>
                    <td>Reason</td>
                    <td>';

    $mysqlprere = 'SELECT * FROM sa_change_request_closing WHERE cr_no="' . $_GET['cr_no'] . '"';
    $qprere = mysqli_query($DBCR, $mysqlprere) or die($DBCR->errno . "-" . "[open_db][mysqli_query][" . $mysqlprere . "] - " . $DBCR->error . "<br/>");
    $dprere = mysqli_fetch_assoc($qprere);
    $tprere = mysqli_num_rows($qprere);

    if ($tprere > 0) {
        $tbl .= $dprere['reason_rejected'];
    } else {
        $tbl .= '-';
    }
    $tbl .= '
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
';

    $pdf->writeHTML($tbl, true, false, false, false, '');

    $so_number = str_replace("/", "", $dproject['so_number']);
    //Close and output PDF document
    $filename = 'CR-' . $dproject['project_code'] . '-' . $so_number . '-' . Date('YmdGis') . '.pdf';
    $pdf->Output($filename, 'I');
}
//============================================================+
// END OF FILE
//============================================================+
