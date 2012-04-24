<?php

require_once "header.php";
require_once "usercontrol.php";
$template = new Template("news", "Undercover-Gaming :: News");

$newspagecount = Content::GetPageCount(1, 20);

$page = (int) $_GET['page'];
$page = min($page, $newspagecount);
$page = max($page, 1);

$content_html = "";
$newsArray = Content::GetContentItems(1, $page, 20);
foreach($newsArray as $newsItem) {
    $content_html .= "<div class='entryline'>
        <div class='datum'>" . date('d-m', $newsItem['date']) . "</div>
        <div class='platform'>" . Content::GetPlatformTag($newsItem['tag']) . "</div>
        <div class='title'><a href='content.php?id=" . $newsItem['id'] . "'>" . $newsItem['title'] . "</a></div>
        <div class='reactie'>(" . $newsItem['comments'] . ")</div>
    </div>";
}

$template->SetVariable("newsitems", $content_html);

if($page > 1) {
    $template->SetVariable("previous_page", "<a href='news.php?page=" . ($page - 1) . "'><<</a>");
} else {
    $template->SetVariable("previous_page", "&nbsp;");
}

$template->SetVariable("current_page", $page);

if($page < $newspagecount) {
    $template->SetVariable("next_page", "<a href='news.php?page=" . ($page + 1) . "'>>></a>");
}

$template->Output();

?>