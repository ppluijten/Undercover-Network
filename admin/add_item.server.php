<?php

require_once("../header.php");
require("add_item.common.php");

/*function previewitem($id)
{
    global $content;
    $contentItem = Content::GetContentItem($id);

    $objResponse = new xajaxResponse();

    switch($contentItem['type']) {
        case 1:
            // News
            $innerHTML = "<h1>News: " .  $contentItem['title'] . "</h1>";
            $innerHTML .= "<h3><i>" . $contentItem['author'] . " (" . date('d-m-Y H:i', $contentItem['date']) . ")</i></h3>";
            $innerHTML .= $contentItem['text'];
            break;
        case 2:
            // Article
            $object = Content::GetObject($contentItem['objecttype'], $contentItem['object']);

            $innerHTML = "<h1>Article: " .  $contentItem['title'] . "</h1>";
            $innerHTML .= "<h3><i>" . $contentItem['author'] . " (" . date('d-m-Y H:i', $contentItem['date']) . ")</i></h3>";
            $innerHTML .= "<h4><b>" . $contentItem['description'] . "</b></h4>";
            $innerHTML .= "<h4><b>Object: " . $object['name'] . "</b></h4>";
            $innerHTML .= $contentItem['text'];
            break;
        case 3:
            // Preview
            $object = Content::GetObject($contentItem['objecttype'], $contentItem['object']);

            $innerHTML = "<h1>Preview: " .  $contentItem['title'] . "</h1>";
            $innerHTML .= "<h3><i>" . $contentItem['author'] . " (" . date('d-m-Y H:i', $contentItem['date']) . ")</i></h3>";
            $innerHTML .= "<h4><b>" . $contentItem['description'] . "</b></h4>";
            $innerHTML .= "<h4><b>Object: " . $object['name'] . "</b></h4>";
            $innerHTML .= $contentItem['text'];
            break;
        case 4:
            // Review
            $object = Content::GetObject($contentItem['objecttype'], $contentItem['object']);

            $innerHTML = "<h1>Preview: " .  $contentItem['title'] . "</h1>";
            $innerHTML .= "<h3><i>" . $contentItem['author'] . " (" . date('d-m-Y H:i', $contentItem['date']) . ")</i></h3>";
            $innerHTML .= "<h4><b>" . $contentItem['description'] . "</b></h4>";
            $innerHTML .= "<h4><b>Object: " . $object['name'] . "</b></h4>";
            $innerHTML .= $contentItem['text'];
            //TODO: Afmaken
            break;
    }

    $objResponse->assign("previewdiv", "innerHTML", $innerHTML);
    return $objResponse;
}*/

//TODO: Remove optional
function createitem($type, $title, $description, $text, $conclusion, $rating, $active, $objecttype, $spotlighttype, $games, $companies, $subtype = 0, $platforms = "", $date_online = "2011-11-27 17:28:00", $event = 0)
{
    global $content;
    switch((int) $objecttype) {
        case 1:
            $object = (int) $games;
            break;
        case 2:
            $object = (int) $companies;
            break;
        default:
            $object = 0;
            break;
    }
    $data = array(
        'title' => (string) $title,
        'description' => (string) $description,
        'text' => (string) $text,
        'conclusion' => (string) $conclusion,
        'rating' => (int) $rating,
        'active' => (int) $active,
        'objecttype' => (int) $objecttype,
        'object' => (int) $object,
        'spotlight' => (int) $spotlighttype,
        'subtype' => (int) $subtype,
        'platforms' => (string) $platforms,
        'dateonline' => (string) $date_online,
        'event' => (int) $event,
        'editorid' => 0
    );
    $create = Content::CreateContentItem($type);
    if($create) { $id = (int) $create; $result = Content::EditContentItem($id, $data); } else { $id = 0; $result = FALSE; }
    
    $objResponse = new xajaxResponse();
    if($result === TRUE) {
        $objResponse->assign("resultdiv", "innerHTML", "The item '$title' has been succesfuly created.");
        $objResponse->alert("The item '$title' has been succesfuly created.");
        $objResponse->script("window.location = '../content.php?id=" . $id . "';");
    } else {
        $objResponse->assign("resultdiv", "innerHTML", "The item '$title' could not be created.");
    }
    return $objResponse;
}

$xajax->processRequest();

?>