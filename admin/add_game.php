<?php

require_once "../header.php";
require "add_item.common.php";
$template = new Template("add_game", "Undercover-Gaming :: Add Game", "../");

$template->SetVariable("xajaxJavascript", $xajax->printJavascript());
$template->SetVariable("body_onload", "");

$genres_html = "";
$genres = Content::GetGenres();
foreach($genres as $genreid => $genrename) {
    $genres_html .= "<option id='option_$genreid' value='$genreid'>$genrename</option>";
}
$template->SetVariable("genres", $genres_html);

$platforms_html = "";
$platforms = Content::GetPlatforms();
foreach($platforms as $platformid => $platformname) {
    if($platforms_html != "") {
        $platforms_html .= "<tr><td>&nbsp;</td>";
    }
    $platforms_html .= "<td><input type='checkbox' name='platforms' id='platforms' value='$platformid' /> $platformname</td></tr>";
}
$template->SetVariable("platforms", $platforms_html);

$template->Output();

?>