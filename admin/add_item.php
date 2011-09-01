<?php

require_once "../header.php";
require "add_item.common.php";
$template = new Template("add_item", "Undercover-Gaming :: Add Item", "../");

$template->SetVariable("xajaxJavascript", $xajax->printJavascript());
$template->SetVariable("body_onload", "set_type(0);");

$games_html = "";
$games = $content->GetGames();
foreach($games as $gameid => $gamename) {
    $games_html .= "<option id='option_$gameid' value='$gameid'" . ($gameid == $objectid ? " checked='1'" : "") . ">$gamename</option>";
}

$template->SetVariable("games", $games_html);

$companies_html = "";
$companies = $content->GetCompanies();
foreach($companies as $companyid => $companyname) {
    $companies_html .= "<option id='option_$companyid' value='$companyid'" . ($companyid == $objectid ? " checked='1'" : "") . ">$companyname</option>";
}

$template->SetVariable("companies", $companies_html);

/*TODO: c_sub_type
c_platforms
c_date
c_spotlight = 0,1,2
c_date_online
c_image
c_event
c_editor_id*/

$template->Output();

?>