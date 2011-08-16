<?php

require_once("header.php");

// Game Info data
$releasestemplate = new Template("releases", "", "", true);

$prevars['templates']['releases'] = $releasestemplate->ReturnOutput();

?>