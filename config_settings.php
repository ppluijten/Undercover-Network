<?php

$settings_array = array(
    "content_news_sort" => "c_date DESC",
    "content_reviews_sort" => "c_date DESC",
    "content_articles_sort" => "c_date DESC",
    "content_previews_sort" => "c_date DESC",
    "content_other_sort" => "c_date DESC",
    "vbulletin_db_prefix" => "VB"
);

Settings::SetSettings($settings_array);

?>