<?php

require_once("header.php");

// Company Info data
$companyinfoTemplate = new Template("companyinfo", "", "", true);

$prevars['templates']['companyinfo'] = $companyinfoTemplate->ReturnOutput();

?>