<?php

$settings_array = array(
    "content_news_sort" => "c_date DESC",
    "content_reviews_sort" => "c_date DESC",
    "content_articles_sort" => "c_date DESC",
    "content_previews_sort" => "c_date DESC",
    "content_other_sort" => "c_date DESC",
    "vbulletin_db_prefix" => "VB",
    "content_games_sort" => "g_title ASC",
    "content_companies_sort" => "c_name ASC",
    "genres_sort" => "g_name ASC"
);

Settings::SetSettings($settings_array);

?>