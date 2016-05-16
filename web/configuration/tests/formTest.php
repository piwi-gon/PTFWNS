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

$htmlForm->startForm(array("action"=>"", "name"=>"formIdent", "id"=>"formIdentifier"));
$htmlTable->startTable(array("align"=>"center"));
// start first table-row
$htmlTable->startRow(array("width"=>"60%"));
$htmlTable->startCol(array("width"=>"30%"));
$htmlElem->addText("TEST 1");
$htmlTable->endCol();
$htmlTable->startCol();
// build some elements
$base->deb("try to create checkbox");
$htmlForm->genInput(array("type"=>"checkbox", "name"=>"test",    "id"=>"testId",    "required"=>true));
$htmlTable->endCol();
$htmlTable->endRow();
// start second table-row
$htmlTable->startRow();
$htmlTable->startCol();
$htmlElem->addText("TEST 2");
$htmlTable->endCol();
$htmlTable->startCol();
$htmlForm->genInput(array("type"=>"number",   "name"=>"testNr",  "id"=>"testNrId",  "required"=>true));
$htmlTable->endCol();
$htmlTable->endRow();
// start third table-row
// with colspan = 2 and right-alignment
$htmlTable->startRow();
$htmlTable->startCol(array("colspan"=>"2", "align"=>"right"));
$htmlForm->genInput(array("type"=>"button",   "name"=>"testBtn", "id"=>"testBtnId", "value"=>"Abschicken", "onClick"=>"sendFormData();reloadDebugMessages();"));
$htmlTable->endCol();
$htmlTable->endRow();
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
if(count($trace)>0) {
    print_r($trace);
}
echo $result . (PHP_SAPI !== "cli"?"<br>":"\n");
?>