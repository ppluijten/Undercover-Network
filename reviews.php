<?php

require_once "header.php";
require_once "usercontrol.php";
$template = new Template("reviews", "Undercover-Gaming :: Reviews");

$reviewpagecount = Content::GetPageCount(4, 20);

$page = (int) $_GET['page'];
$page = min($page, $reviewpagecount);
$page = max($page, 1);

$content_html = "";
$reviewArray = Content::GetContentItems(4, $page, 20);
foreach($reviewArray as $reviewItem) {
    $content_html .= "<div class='entryline'>
        <div class='datum'>" . date('d-m', $reviewItem['date']) . "</div>
        <div class='platform'>" . Content::GetPlatformTag($reviewItem['tag']) . "</div>
        <div class='title'><a href='content.php?id=" . $reviewItem['id'] . "'>" . $reviewItem['title'] . "</a></div>
        <div class='reactie'>(" . $reviewItem['comments'] . ")</div>
    </div>";
}

$template->SetVariable("reviewitems", $content_html);

if($page > 1) {
    $template->SetVariable("previous_page", "<a href='reviews.php?page=" . ($page - 1) . "'><<</a>");
} else {
    $template->SetVariable("previous_page", "&nbsp;");
}

$template->SetVariable("current_page", $page);

if($page < $reviewpagecount) {
    $template->SetVariable("next_page", "<a href='reviews.php?page=" . ($page + 1) . "'>>></a>");
}

$template->Output();

?>