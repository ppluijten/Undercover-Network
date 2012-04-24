<?php

require_once "header.php";
require_once "usercontrol.php";
$template = new Template("index", "Undercover-Gaming :: Frontpage");

$news_html = "";
$newsArray = Content::GetContentItems(1, 0, 10);
foreach($newsArray as $newsItem) {
    $news_html .= "<div class='entryline'>
        <div class='datum'>" . date('d-m', $newsItem['date']) . "</div>
        <div class='platform'>" . Content::GetPlatformTag($newsItem['tag']) . "</div>
        <div class='title'><a href='content.php?id=" . $newsItem['id'] . "'>" . $newsItem['title'] . "</a></div>
        <div class='reactie'>(" . $newsItem['comments'] . ")</div>
    </div>";
}

$covered_html = "";
$coveredArray = Content::GetCoveredItems(10);
foreach($coveredArray as $coveredItem) {
    switch($coveredItem['type']) {
        case 1:
            // News
            $coveredtype = "Nieuws";
            break;
        case 2:
            // Article
            $coveredtype = "Artikel";
        case 3:
            // Preview
            $coveredtype = "Impressie";
            break;
        case 4:
            // Review
            $coveredtype = "Recensie";
            break;
        default:
            $coveredtype = "";
            break;
    }
    $covered_html .= "<div class='entryline'>
        <div class='datum'>" . date('d-m', $coveredItem['date']) . "</div>
        <div class='platform'>" . Content::GetPlatformTag($coveredItem['tag']) . "</div>
        <div class='type'>" . $coveredtype . "</div>
        <div class='title'><a href='content.php?id=" . $coveredItem['id'] . "'>" . $coveredItem['title'] . "</a></div>
        <div class='reactie'>(" . $coveredItem['comments'] . ")</div>
    </div>";
}

$template->SetVariable("newsitems", $news_html);
$template->SetVariable("covereditems", $covered_html);

$template->Output();

?>