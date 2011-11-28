<?php

require_once "../header.php";
require "edit_item.common.php";
$template = new Template("edit_item", "Undercover-Gaming :: Edit Item", "../");

$id = (int) $_GET['id'];
$contentItem = $content->GetContentItem($id);
if($contentItem) {
    // Content item was found
    $objectid = (int) $contentItem['object'];
    $objecttype = (int) $contentItem['objecttype'];
    $spotlighttype = (int) $contentItem['spotlight'];

    $template->SetVariable("xajaxJavascript", $xajax->printJavascript());
    $template->SetVariable("body_onload", "xajax_previewitem($id); set_type($objecttype); set_sl_type($spotlighttype);");
    $template->SetVariable("id", $id);
    $template->SetVariable("item_title", $contentItem['title']);
    $template->SetVariable("item_description", $contentItem['description']);
    $template->SetVariable("text_orig", $contentItem['text_orig']);
    $template->SetVariable("active", $contentItem['active']);
    $template->SetVariable("objecttype", $objecttype);
    $template->SetVariable("checked_0", $objecttype == 0 ? 'checked ' : '');
    $template->SetVariable("checked_1", $objecttype == 1 ? 'checked ' : '');
    $template->SetVariable("checked_2", $objecttype == 2 ? 'checked ' : '');
    $template->SetVariable("spotlighttype", $spotlighttype);
    $template->SetVariable("checked_sl_0", $spotlighttype == 0 ? 'checked ' : '');
    $template->SetVariable("checked_sl_1", $spotlighttype == 1 ? 'checked ' : '');
    $template->SetVariable("checked_sl_2", $spotlighttype == 2 ? 'checked ' : '');

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

    if($contentItem['type'] == 4) {
        $review_extra = '';
        $template->SetVariable("item_conclusion", $contentItem['conclusion']);
        $template->SetVariable("item_rating", $contentItem['rating']);
    } else {
        $template->SetVariable("hide_review", "style='display:none;'");
    }

    //TODO: Image upload + c_image

    /* TODO: Overige velden
     * c_sub_type
     * c_platforms
     * c_event
     * c_date_online
     * c_editor_id
     */
} else {
    // Could not find the item
    $template->SetVariable("item_title", 'Item not found');
}

$template->Output();

?>