<?php
include("sessionStart.php");
//echo "<pre>"; print_r($_SESSION); echo "</pre>";
// trying to get an external object
$phpExcel = $base->getExtension("PHPExcel");
$debMsg   = "tried to load external class PHPExcel";
// first test check class loading with information
if(is_object($phpExcel)) { $base->deb($debMsg." -> OK", "CLS", 2);     }
else                     { $base->deb($debMsg." -> failed", "CLS", 2); }

// trying to get another external object
$phpExcel = $base->getExtension("libchart");
$debMsg   = "tried to load external class libChart";
// first test check class loading with information
if(is_object($phpExcel)) { $base->deb($debMsg." -> OK", "CLS", 2);     }
else                     { $base->deb($debMsg." -> failed", "CLS", 2); }

$jquery   = $base->getExtension("cJQuery");
$debMsg   = "tried to load external class jquery";
// first test check class loading with information
if(is_object($jquery)) { $base->deb($debMsg." -> OK", "CLS", 2);     }
else                   { $base->deb($debMsg." -> failed", "CLS", 2); }

$jqueryUI = $base->getExtension("cJQueryUI");
$debMsg   = "tried to load external class jqueryui";
// first test check class loading with information
if(is_object($jqueryUI)) { $base->deb($debMsg." -> OK", "CLS", 2);     }
else                     { $base->deb($debMsg." -> failed", "CLS", 2); }

$imgHome   = "images/16x16/legend.png";
$imgLetter = "images/16x16/emails.png";
$imgPacket = "images/16x16/package.png";
$imgPrint  = "images/16x16/printer.png";
$imgTell   = "images/16x16/envelope.png";
$imgLogout = "images/16x16/lock_open.png";
$imgPower  = "images/16x16/control_power.png";
// load HTML-Superclass
$html = $base->getHTML();
$debMsg ="tried to load class cHTML";
// first test check class loading with information
if(is_object($html)) { $base->deb($debMsg." -> OK");     }
else                 { $base->deb($debMsg." -> failed"); }

$addToPage = array("css"=>array($jqueryUI->getCSSTag(dirname(__FILE__)."/css"), "<link rel=\"stylesheet\" href=\"css/base.css\">\n"),
                   "script"=>array($jquery->getScriptTag(dirname(__FILE__)."/lib/js"), $jqueryUI->getScriptTag(dirname(__FILE__)."/lib/js")));
// render the output
// at this time - only the start
echo $html->render($addToPage);
?>
<br>
<button class="button" onClick="$('#pdfViewContainerId').attr('src', 'tests/tcpdf/example_001_modified.inc.php?PHPSESSID=<?php echo session_id(); ?>');">
	View PDF
</button>
<br>
<!-- // echo "<button onClick="$('#pdfViewContainerId').load('tcpdf/test.php?PHPSESSID=".session_id()."');">View PDF</button>\n  -->
<iframe id="pdfViewContainerId" style="width:500px; height: 400px; border:2px ridge silver;overflow:auto;"></iframe>
<br>
<button class="button" onClick="$('#versionCheckId').load('tests/versionCheck.php?PHPSESSID=<?php echo session_id(); ?>&package=<?php echo urlencode("cJQuery"); ?>');">
	checkVersion<br>jQuery
</button>
<br><br>
<button class="button" onClick="$('#versionCheckId').load('tests/versionCheck.php?PHPSESSID=<?php echo session_id(); ?>&package=<?php echo urlencode("cJQueryUI"); ?>');">
	checkVersion<br>jQueryUI
</button>
<br><br>
<button class="button" onClick="$('#versionCheckId').load('tests/versionCheck.php?PHPSESSID=<?php echo session_id(); ?>&package=<?php echo urlencode("all"); ?>');">
	checkVersion<br>for all
</button>
<br>
<!-- // display the result in  -->
<div id="versionCheckId" style="width:500px; height: 200px; border:2px ridge silver;overflow:auto;"></div>
<?php
$excelImageLocation = "images/excelImg3.png";
$onClick="alert('TEST');";
?>
<div class="ui-widget-header ui-corner-all" style="width:30px;padding:10px;" href="#" onClick="<?php echo $onClick; ?>">
	<img src="<?php echo $excelImageLocation; ?>" border="0">
</div>
<div id="startlist">
	<div class="ui-widget-header ui-corner-all" style="width:30px;padding:10px;float:left;" onClick="<?php echo $onClick; ?>">
		<img src="<?php echo $imgHome; ?>" border="0">
	</div>
	<div class="ui-widget-header ui-corner-all" style="width:30px;padding:10px;float:left;" onClick="<?php echo $onClick; ?>">
		<img src="<?php echo $imgLetter; ?>" border="0">
	</div>
	<div class="ui-widget-header ui-corner-all" style="width:30px;padding:10px;float:left;" onClick="<?php echo $onClick; ?>">
		<img src="<?php echo $imgPacket; ?>" border="0">
	</div>
	<div class="ui-widget-header ui-corner-all" style="width:30px;padding:10px;float:left;" onClick="<?php echo $onClick; ?>">
		<img src="<?php echo $imgPrint; ?>" border="0">
	</div>
	<div class="ui-widget-header ui-corner-all" style="width:30px;padding:10px;float:left;" onClick="<?php echo $onClick; ?>">
		<img src="<?php echo $imgTell; ?>" border="0">
		</div>
	<div class="ui-widget-header ui-corner-all" style="width:30px;padding:10px;float:left;" onClick="<?php echo $onClick; ?>">
		<img src="<?php echo $imgLogout; ?>" border="0">
	</div>
	<div class="ui-widget-header ui-corner-all" style="width:30px;padding:10px;float:left;" onClick="<?php echo $onClick; ?>">
		<img src="<?php echo $imgPower; ?>" border="0">
	</div>
</div><br>
<br><br><br>\n<form action="upload.php" method="POST" enctype="multipart/form-data">
<label for="uploadFile">File:</label><input type="file" name="uploadFile"><br>
<label for="uploadVersion">Version:</label><input type="text" name="uploadVersion"><br>
<input type="submit" value="send">
</form>
<?php
exit();
?>