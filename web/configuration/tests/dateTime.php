<?php
include("sessionStart.php");

$date = $base->getDate();
$debMsg ="tried to load class cDate";
// first test check class loading with information
if(is_object($date)) { $base->deb($debMsg." -> OK", "CLS"); }
else                 { $base->deb($debMsg." -> failed", "CLS"); }
// set actual Date
$date->setDate();
echo "Actual Date: " . $date->getDate() . (PHP_SAPI !== "cli"?"<br>":"\n");

// setting Date '01.01.1971'
$date->setDate("01.01.1971");
echo "Set Date: " . $date->getDate() . (PHP_SAPI !== "cli"?"<br>":"\n");

// load HTML-Superclass
$html = $base->getHTML();
$debMsg ="tried to load class cHTML";
// first test check class loading with information
if(is_object($html)) { $base->deb($debMsg." -> OK", "CLS");     }
else                 { $base->deb($debMsg." -> failed", "CLS"); }

// add javascript and css
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

$addToPage = array("css"=>array($jqueryUI->getCSSTag(dirname(__FILE__)."/css"), "<link rel=\"stylesheet\" href=\"css/base.css\">\n"),
                   "script"=>array($jquery->getScriptTag(dirname(__FILE__)."/lib/js"), $jqueryUI->getScriptTag(dirname(__FILE__)."/lib/js")));
// render the output
$result .= $html->render($addToPage);

echo $result . (PHP_SAPI !== "cli"?"<br>":"\n");

exit();
?>