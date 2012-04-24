<?php

require_once "header.php";
require_once "usercontrol.php";

$id = (int) $_GET['id'];
$contentItem = Content::GetContentItem($id);

$object = Content::GetObject($contentItem['objecttype'], $contentItem['object']);
$object_id = $object['id'];
$object_type = $object['type'];
if((int) $object_id > 0) {
    //TODO: Company info toevoegen
    switch($object_type) {
        case 'game':
            $game_title = $object['title'];
            $game_platforms = Content::GetPlatformTags(explode('|', $object['platforms']));
            $game_image = Content::GetImage($object['image']);
            $game_genre = Content::GetGenre($object['genre']);
            $game_publisher = Content::GetCompany($object['publisher']);
            $game_publisher = $game_publisher['name'];
            $game_developer = Content::GetCompany($object['developer']);
            $game_developer = $game_developer['name'];
            $game_website = "<a href='" . $object['website'] . "'>Klik hier</a>";
            $game_multiplayer = $object['multiplayer'];
            $game_release = date('d-m-Y', $object['release']);
            $game_rating = Content::GetGameRating($object_id);
            $game_rating_image = Content::GetRatingImage($game_rating);
            $prevars['templates']['game_title'] = $game_title;
            $prevars['templates']['game_platforms'] = $game_platforms;
            $prevars['templates']['game_image'] = $game_image;
            $prevars['templates']['game_genre'] = $game_genre;
            $prevars['templates']['game_publisher'] = $game_publisher;
            $prevars['templates']['game_developer'] = $game_developer;
            $prevars['templates']['game_website'] = $game_website;
            $prevars['templates']['game_multiplayer'] = $game_multiplayer;
            $prevars['templates']['game_release'] = $game_release;
            $prevars['templates']['game_rating_image'] = $game_rating_image;
            require_once "gameinfo.php";
            break;
        case 'company':
            break;
    }
}

$template = new Template("content");

$content_html = "";
//$content_html .= "<h1>Data</h1>";
//$content_html .= "<pre>" . var_export($contentItem, 1) . "</pre>";
//$content_html .= "<br />";

$item_title = $contentItem['title'];

$item_author = $contentItem['author'];
$item_author_date = date('d-m-Y H:i', $contentItem['date']);

switch($contentItem['type']) {
    case 1:
        // News
        $content_html .= $contentItem['text'];

        $type_image = '<img src="layout/layout_left/news_title.png" alt="" class="title" style="margin-left: 55px;">';
        break;
    case 2:
        // Article
        $content_html .= "<h4><b>" . $contentItem['description'] . "</b></h4>";
        $content_html .= $contentItem['text'];

        $type_image = '<img src="layout/layout_left/article_title.png" alt="" class="title" style="margin-left: 55px;">';
        break;
    case 3:
        // Preview
        $content_html .= "<h4><b>" . $contentItem['description'] . "</b></h4>";
        $content_html .= $contentItem['text'];

        $type_image = '<img src="layout/layout_left/impressie_title.png" alt="" class="title" style="margin-left: 55px;">';
        break;
    case 4:
        // Review
        $content_html .= "<h4><b>" . $contentItem['description'] . "</b></h4>";
        $content_html .= $contentItem['text'];

        $conclusion = '<div id="covered">
	      <div class="top_covered"><img src="layout/layout_left/conclusie_title.png" alt="" class="title" style="margin-left: 45px;" ></div>
	      <div class="middle"> Ookal oogt het spel in het eerste opzicht niet meteen goed, weet ik haast zeker dat ik deze titel toch in huis haal. Het wordt uitgebracht in september voor de Playstation 3, de Xbox 360, de Wii en Nintendo 3DS. Ik ga het de komende twee maanden nog in de gaten houden, hopend op wat uitgebreider nieuws. </div>
	      <div class="bottom_covered"></div>
        </div>';
        $template->SetVariable("item_conclusion", $conclusion);
        
        $type_image = '<img src="layout/layout_left/recensie_title.png" alt="" class="title" style="margin-left: 55px;">';
        //TODO: Afmaken, vooral score er nog in
        break;
}

$template->SetVariable("title", "Undercover-Gaming :: Content");
$template->SetVariable("item_title", $item_title);
$template->SetVariable("item_author", $item_author);
$template->SetVariable("item_author_date", $item_author_date);
$template->SetVariable("type_image", $type_image);
$template->SetVariable("body", $content_html);

$template->Output();

?>