<?php

require_once "../header.php";
require_once "usercontrol.php";
$template = new Template("admin_index", "Undercover-Gaming :: Administrator", "../");

$adminpagecount = Content::GetPageCount(0, 20);

$page = (int) $_GET['page'];
$page = min($page, $adminpagecount);
$page = max($page, 1);

$content_html = "";
$adminArray = Content::GetContentItems(0, $page, 20);
foreach($adminArray as $contentItem) {
    switch($contentItem['type']) {
        case 1:
            // News
            $contenttype = "Nieuws";
            break;
        case 2:
            // Article
            $contenttype = "Artikel";
            break;
        case 3:
            // Preview
            $contenttype = "Impressie";
            break;
        case 4:
            // Review
            $contenttype = "Recensie";
            break;
        default:
            $contenttype = "";
            break;
    }
    $content_html .= "<div class='entryline'>
        <div class='datum'>" . date('d-m', $contentItem['date']) . "</div>
        <div class='platform'>" . Content::GetPlatformTag($contentItem['tag']) . "</div>
        <div class='type'>" . $contenttype . "</div>
        <div class='title'><a href='edit_item.php?id=" . $contentItem['id'] . "'>" . $contentItem['title'] . "</a></div>
        <div class='reactie'>(" . $contentItem['comments'] . ")</div>
    </div>";
}

$template->SetVariable("adminitems", $content_html);

if($page > 1) {
    $template->SetVariable("previous_page", "<a href='index.php?page=" . ($page - 1) . "'><<</a>");
} else {
    $template->SetVariable("previous_page", "&nbsp;");
}

$template->SetVariable("current_page", $page);

if($page < $adminpagecount) {
    $template->SetVariable("next_page", "<a href='index.php?page=" . ($page + 1) . "'>>></a>");
}

$template->Output();

?>