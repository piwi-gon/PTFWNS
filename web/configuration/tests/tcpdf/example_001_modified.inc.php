<?php

include("../sessionStart.php");

// load base-class
require_once("cBase.inc.php");
$base = new cBase();
if(!is_object($base)) { echo "No base - exiting..." . (PHP_SAPI !== "cli"?"<br>":"\n"); exit(); }

// trying to get an external object
$tcpdf  = $base->getExtension("tcpdf");
$debMsg = "tried to load external class tcpdf";
// first test check class loading with information
if(is_object($tcpdf)) { $base->deb($debMsg." -> OK", "CLS", 2);     }
else                  { $base->deb($debMsg." -> failed", "CLS", 2); echo "NO TCPDF-Lib found<br>";exit;}

// now try to build a pdf
$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set default font subsetting mode
$tcpdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$tcpdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$tcpdf->AddPage();

// set text shadow effect
$tcpdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD
<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;

// Print text using writeHTMLCell()
$tcpdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$fName = "/tmp/example_001_modified.pdf";
@$tcpdf->Output($fName, 'F');
header('Content-Type: application/pdf');
if (headers_sent()) {
    $this->Error('Some data has already been output to browser, can\'t send PDF file');
}
//header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
header('Pragma: public');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Content-Disposition: inline; filename="'.basename($name).'"');
$fileContent = file_get_contents($fName);
unlink($fName);
echo $fileContent;
exit;
?>