<?php
include("sessionStart.php");

$base->deb("sessionID: ".session_id());
echo "Testing connecting a database ..." . (PHP_SAPI !== "cli"?"<br>":"\n");
$debMsg = "try to load class cSQL";
$sqlObj = $base->getSQL();
if(is_object($sqlObj)) {
    $base->deb($debMsg." -> OK");
    $sqlObj->makeNewConn("local");
    $sqlObj->makeQuery("select * from t_hosting");
}
else { $base->deb($debMsg." -> failed"); }

// start tracing
$trace = debug_backtrace();

echo "Now Testing building a html-div ...".(PHP_SAPI !== "cli"?"<br>":"\n");
$html = $base->getHTML();
$debMsg ="tried to load class cHTML";
// first test check class loading with information
if(is_object($html)) { $base->deb($debMsg." -> OK"); }
else { $base->deb($debMsg." -> failed"); }

// try to load html-element-class
$debMsg ="tried to load class cHTMLElem";
$htmlElem = $html->getHTMLElem();
if(is_object($htmlElem)) { $base->deb($debMsg." -> OK"); }
else { $base->deb($debMsg." -> failed"); }

$result  = $htmlElem->genElem(array("type"=>"div",   "style"=>"border:2px ridge silver;width:200px;height:200px;", "content"=>"TEST"))."\n";
$result .= $htmlElem->genElem(array("type"=>"image", "style"=>"border:2px ridge silver;width:200px;height:200px;", "src"=>"images/test.png"))."\n";

// try to load html-form-class
$debMsg ="tried to load class cHTMLForm";
$htmlForm = $html->getHTMLForm();
if(is_object($html)) { $base->deb($debMsg." -> OK"); }
else { $base->deb($debMsg." -> failed"); }

// lets try to build some input-fields
$base->deb("try to create checkbox");
$htmlForm->genInput(array("type"=>"checkbox", "name"=>"test", "id"=>"testId"));
$htmlForm->genInput(array("type"=>"number", "name"=>"testNr", "id"=>"testNrId"));
/*
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
*/
//$addToPage = array("css"=>$jqueryUI->getCSSTag("/css"),
//                   "script"=>array($jquery->getScriptTag("/lib/js"),$jqueryUI->getScriptTag("/lib/js")));
// render the output
$result .= $html->renderContent($addToPage);
// end and display tracing
print_r($trace);
echo $result . (PHP_SAPI !== "cli"?"<br>":"\n");
echo "<pre>"; print_r($_SESSION); echo "</pre>";
?>