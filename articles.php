<?php

require_once "header.php";
require_once "usercontrol.php";
$template = new Template("articles", "Undercover-Gaming :: Artikelen");

$articlepagecount = Content::GetPageCount(2, 20);

$page = (int) $_GET['page'];
$page = min($page, $articlepagecount);
$page = max($page, 1);

$content_html = "";
$articlesArray = Content::GetContentItems(2, $page, 20);
foreach($articlesArray as $articleItem) {
    $content_html .= "<div class='entryline'>
        <div class='datum'>" . date('d-m', $articleItem['date']) . "</div>
        <div class='platform'>" . Content::GetPlatformTag($articleItem['tag']) . "</div>
        <div class='title'><a href='content.php?id=" . $articleItem['id'] . "'>" . $articleItem['title'] . "</a></div>
        <div class='reactie'>(" . $articleItem['comments'] . ")</div>
    </div>";
}

$template->SetVariable("articleitems", $content_html);

if($page > 1) {
    $template->SetVariable("previous_page", "<a href='articles.php?page=" . ($page - 1) . "'><<</a>");
} else {
    $template->SetVariable("previous_page", "&nbsp;");
}

$template->SetVariable("current_page", $page);

if($page < $articlepagecount) {
    $template->SetVariable("next_page", "<a href='articles.php?page=" . ($page + 1) . "'>>></a>");
}

$template->Output();

?>