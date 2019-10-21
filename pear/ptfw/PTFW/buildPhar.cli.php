<?php
ini_set("phar.readonly", 0);
$phar = new Phar("base.phar", 0, "base.phar");
$phar->startBuffering();
$phar->setSignatureAlgorithm(Phar::SHA256);
$phar->setStub("<?php
Phar::mapPhar();
include 'phar://base.phar/index.php';
__HALT_COMPILER();
?>");
$phar->buildFromDirectory(dirname(__FILE__)."/v1.0.0");
$phar->compress(Phar::GZ);
// $phar->setStub($phar->createDefaultStub('modules/soap/current/client/simpleTestFritzBox.cli.php'));
$phar->stopBuffering();
echo "Phar-Archive created!\n";
?>