<?php

require_once "header.php";
require_once "usercontrol.php";
$template = new Template("previews", "Undercover-Gaming :: Previews");

$previewpagecount = $content->GetPageCount(3, 20);

$page = (int) $_GET['page'];
$page = min($page, $previewpagecount);
$page = max($page, 1);

$content_html = "";
$previewArray = $content->GetContentItems(3, $page, 20);
foreach($previewArray as $previewItem) {
    $content_html .= "<div class='entryline'>
        <div class='datum'>" . date('d-m', $previewItem['date']) . "</div>
        <div class='platform'>" . $content->GetPlatformTag($previewItem['tag']) . "</div>
        <div class='title'><a href='content.php?id=" . $previewItem['id'] . "'>" . $previewItem['title'] . "</a></div>
        <div class='reactie'>(" . $previewItem['comments'] . ")</div>
    </div>";
}

$template->SetVariable("previewitems", $content_html);

if($page > 1) {
    $template->SetVariable("previous_page", "<a href='previews.php?page=" . ($page - 1) . "'><<</a>");
} else {
    $template->SetVariable("previous_page", "&nbsp;");
}

$template->SetVariable("current_page", $page);

if($page < $previewpagecount) {
    $template->SetVariable("next_page", "<a href='previews.php?page=" . ($page + 1) . "'>>></a>");
}

$template->Output();

?>