<?php

require_once("../header.php");
require("edit_item.common.php");

/**
 * Return the html string for the given data
 *
 * @param int $type the type of item
 * @param string $title the title of the item
 * @param string $author the author of the item
 * @param int $date the date of the item
 * @param string $text the text of the item
 * @param string $description the description of the item [OPTIONAL, default = ""]
 * @param int $objecttype the type of object [OPTIONAL, default = 0]
 * @param int $object the id of the object [OPTIONAL, default = 0]
 * @return string the html string containing all data
 */
function previewitem_html($type, $title, $author, $date, $text, $description = "", $objecttype = 0, $object = 0) {
    switch($type) {
        case 1:
            // News
            $innerHTML = "<h1>News: " .  $title . "</h1>";
            $innerHTML .= "<h3><i>" . $author . " (" . date('d-m-Y H:i', $date) . ")</i></h3>";
            $innerHTML .= $text;
            break;
        case 2:
            // Article
            if($objecttype > 0 && $object > 0) {
                $object = Content::GetObject((int) $objecttype, (int) $object);
            } else {
                return false;
            }

            $innerHTML = "<h1>Article: " .  $title . "</h1>";
            $innerHTML .= "<h3><i>" . $author . " (" . date('d-m-Y H:i', $date) . ")</i></h3>";
            $innerHTML .= "<h4><b>" . $description . "</b></h4>";
            $innerHTML .= "<h4><b>Object: " . $object['name'] . "</b></h4>";
            $innerHTML .= $text;
            break;
        case 3:
            // Preview
            if($objecttype > 0 && $object > 0) {
                $object = Content::GetObject((int) $objecttype, (int) $object);
            } else {
                return false;
            }

            $innerHTML = "<h1>Preview: " .  $title . "</h1>";
            $innerHTML .= "<h3><i>" . $author . " (" . date('d-m-Y H:i', $date) . ")</i></h3>";
            $innerHTML .= "<h4><b>" . $description . "</b></h4>";
            $innerHTML .= "<h4><b>Object: " . $object['name'] . "</b></h4>";
            $innerHTML .= $text;
            break;
        case 4:
            // Review
            if($objecttype > 0 && $object > 0) {
                $object = Content::GetObject((int) $objecttype, (int) $object);
            } else {
                return false;
            }

            $innerHTML = "<h1>Preview: " .  $title . "</h1>";
            $innerHTML .= "<h3><i>" . $author . " (" . date('d-m-Y H:i', $date) . ")</i></h3>";
            $innerHTML .= "<h4><b>" . $description . "</b></h4>";
            $innerHTML .= "<h4><b>Object: " . $object['name'] . "</b></h4>";
            $innerHTML .= $text;
            //TODO: Afmaken
            break;
        default:
            return false;
    }
    
    return $innerHTML;
}

/**
 * Preview an item from the database
 *
 * @param int $id the id of the item
 * @return xajaxResponse the xajax response object
 */
function previewitem($id) {
    $contentItem = Content::GetContentItem($id);

    $objResponse = new xajaxResponse();

    $innerHTML = previewitem_html($contentItem['type'], $contentItem['title'], $contentItem['author'],
            $contentItem['date'], $contentItem['text'], $contentItem['description'], $contentItem['objecttype'], $contentItem['object']);

    $objResponse->assign("previewdiv", "innerHTML", $innerHTML);
    return $objResponse;
}

/**
 * Preview an item directly, without interference of the database
 *
 * @param int $type the type of item
 * @param string $title the title of the item
 * @param string $author the author of the item
 * @param string $text the text of the item
 * @param string $description the description of the item
 * @param int $objecttype the type of object
 * @param int $games the id of the selected game
 * @param int $companies the id of the selected object
 * @return xajaxResponse the xajax response object
 */
function previewitem_direct($type, $title, $author, $text, $description, $objecttype, $games, $companies) {
    $objResponse = new xajaxResponse();

    // Fetch the current date and check what kind of object we are dealing with
    $date = time();
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

    // Fetch the html for this data
    $innerHTML = previewitem_html($type, $title, $author, $date, $text, $description, $objecttype, $object);

    $objResponse->assign("previewdiv", "innerHTML", $innerHTML);
    return $objResponse;
}

//TODO: Remove optional
function edititem($id, $title, $description, $text, $conclusion, $rating, $active, $objecttype, $spotlighttype, $games, $companies, $subtype = 0, $platforms = "", $date_online = "2011-11-27 17:28:00", $event = 0, $editor = 0) {
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
        'editorid' => (int) $editor
    );
    $result = Content::EditContentItem($id, $data);

    $objResponse = new xajaxResponse();
    if($result === TRUE) {
        $objResponse->assign("resultdiv", "innerHTML", "The item '$title' has been succesfuly updated.");
        $objResponse->script("xajax_previewitem($id);");
    } else {
        $objResponse->assign("resultdiv", "innerHTML", "The item '$title' could not be updated.");
    }
    return $objResponse;
}

$xajax->processRequest();

?>