<?php
include("sessionStart.php");
// load HTML-Superclass
$html = $base->getHTML();
$debMsg ="tried to load class cHTML";
// first test check class loading with information
if(is_object($html)) { $base->deb($debMsg." -> OK");     }
else                 { $base->deb($debMsg." -> failed"); }

// load HTML-Elem-Subclass for generating elements
$htmlElem = $html->getHTMLElem();
$debMsg ="tried to load class cHTMLElem";
// first test check class loading with information
if(is_object($htmlElem)) { $base->deb($debMsg." -> OK");     }
else                     { $base->deb($debMsg." -> failed"); }

// load HTML-Form-Subclass for generating forms
$htmlForm = $html->getHTMLForm();
$debMsg ="tried to load class cHTMLForm";
// first test check class loading with information
if(is_object($html)) { $base->deb($debMsg." -> OK");     }
else                 { $base->deb($debMsg." -> failed"); }

// load HTML-Table-Subclass for generating forms
$htmlTable= $html->getHTMLTable();
$debMsg ="tried to load class cHTMLTable";
// first test check class loading with information
if(is_object($html)) { $base->deb($debMsg." -> OK");     }
else                 { $base->deb($debMsg." -> failed"); }

// start tracing
$trace = debug_backtrace();

$htmlForm->startForm(array("action"=>"", "name"=>"TEST"));
$htmlTable->startTable(array("align"=>"center"));

// adding some text-element
$htmlElem->genElem(array("type" => "text", "value" => "TEST 1"), true);
$htmlElem->genElem(array("type" => "text", "value" => "TEST 2"), true);

// its possible to add en input-element too
$htmlForm->genInput(array("type"=>"number",   "name"=>"testNr",  "id"=>"testNrId",  "required"=>true), true);

$htmlTable->buildRow(array("row"=>array("style"=>"width:400px;"), "col"=>array("width"=>array("30%", "40%", "30%"))));

$htmlTable->endTable();
$htmlForm->endForm();

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

// end and display tracing
if(count($trace)>0) { print_r($trace); }
echo $result . (PHP_SAPI !== "cli"?"<br>":"\n");
?>