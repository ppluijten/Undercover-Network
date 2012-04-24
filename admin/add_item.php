<?php

require_once "../header.php";
require "add_item.common.php";
$template = new Template("add_item", "Undercover-Gaming :: Add Item", "../");

$template->SetVariable("xajaxJavascript", $xajax->printJavascript());
$template->SetVariable("body_onload", "set_type(0); set_sl_type(0);");

$games_html = "";
$games = Content::GetGames();
foreach($games as $gameid => $gamename) {
    $games_html .= "<option id='option_$gameid' value='$gameid'" . ($gameid == $objectid ? " checked='1'" : "") . ">$gamename</option>";
}

$template->SetVariable("games", $games_html);

$companies_html = "";
$companies = Content::GetCompanies();
foreach($companies as $companyid => $companyname) {
    $companies_html .= "<option id='option_$companyid' value='$companyid'" . ($companyid == $objectid ? " checked='1'" : "") . ">$companyname</option>";
}

$template->SetVariable("companies", $companies_html);

//TODO: Image upload + c_image

/* TODO: Overige velden
 * c_sub_type
 * c_platforms
 * c_date_online
 * c_event
 */

$template->Output();

?>