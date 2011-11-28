<?php

class Content {

    function GetPageCount($type, $pageLimit, $spotlight = -1) {
        global $db;

        if((int) $type > 0) {
            $and_type = "AND     c_type = '" . (int) $db->EscapeString($type) . "'";
        } else {
            $and_type = "";
        }

        if((int) $spotlight > -1) {
            $and_spotlight = "AND     c_spotlight = '" . (int) $db->EscapeString($spotlight) . "'";
        } else {
            $and_spotlight = "";
        }

        $getitem = "
            SELECT  count(*) as amount
            FROM    ug_content
            WHERE   c_active = '1'
            " . $and_type . "
            " . $and_spotlight . "
            AND     DATE_FORMAT(c_date_online, '%d-%m-%Y %H:%i') <= '" . date('d-m-Y H:i') . "'";
        $sqlitem = $db->GetQuery($getitem);

        $item = $db->GetArray($sqlitem);
        $amount = (int) $item['amount'];
        $amount = (int) ceil((int) $amount / (int) $pageLimit);

        return $amount;
    }

    function GetCoveredItems($pageLimit, $detailed = FALSE) {
        return $this->GetContentItems(0, 0, $pageLimit, $detailed, 2);
    }

    function GetContentItems($type = 0, $pageNumber = 0, $pageLimit = 0, $detailed = FALSE, $spotlight = -1) {
        //TODO: Deze functie afmaken
        
        global $db;
        global $settings;

        $itemArray = array();
        $itemOffset = (int) (($pageNumber - 1) * $pageLimit);
        $limit = ((int) $pageLimit != 0) ? (((int) $pageNumber > 0) ? "LIMIT " . (int) $itemOffset . ", " . (int) $pageLimit : "LIMIT " . (int) $pageLimit) : '';

        $order = "";
        switch($type) {
            case 1:
                // News
                if($settings->SettingExists("content_news_sort")) {
                    $order = "ORDER BY " . (string) $settings->GetSetting("content_news_sort");
                }
                break;
            case 2:
                // Article
                if($settings->SettingExists("content_articles_sort")) {
                    $order = "ORDER BY " . (string) $settings->GetSetting("content_articles_sort");
                }
                break;
            case 3:
                // Preview
                if($settings->SettingExists("content_previews_sort")) {
                    $order = "ORDER BY " . (string) $settings->GetSetting("content_previews_sort");
                }
                break;
            case 4:
                // Review
                if($settings->SettingExists("content_reviews_sort")) {
                    $order = "ORDER BY " . (string) $settings->GetSetting("content_reviews_sort");
                }
                break;
            default:
                // Other or none
                if($settings->SettingExists("content_other_sort")) {
                    $order = "ORDER BY " . (string) $settings->GetSetting("content_other_sort");
                }
                break;
        }

        //TODO: Sub-types: column 6, rubric 7, screen 8, trailer 8
        
        if((int) $type > 0) {
            $and_type = "AND     c_type = '" . (int) $db->EscapeString($type) . "'";
        } else {
            $and_type = "";
        }

        if((int) $spotlight > -1) {
            $and_spotlight = "AND     c_spotlight = '" . (int) $db->EscapeString($spotlight) . "'";
        } else {
            $and_spotlight = "";
        }

        $getitem = "
            SELECT  *
            FROM    ug_content
            WHERE   c_active = '1'
            " . $and_type . "
            " . $and_spotlight . "
            AND     DATE_FORMAT(c_date_online, '%d-%m-%Y %H:%i') <= '" . date('d-m-Y H:i') . "'
            " . $db->EscapeString($order) . "
            " . $db->EscapeString($limit);
        $sqlitem = $db->GetQuery($getitem);

        /*$getitem = 	"
            SELECT 	*
            FROM 	".TABLE_CONTENT."
            WHERE 	c_type 			=	'".item."'
            AND 	c_active 		= 	'".TRUE."'
            AND 	c_date_online	<= 	'".$dateTime."'
            ORDER BY ".$config->item['orderBy']."
            ".$config->item['orderByType']."
            ".$limit;*/

        while($item = $db->GetArray($sqlitem)) {

                $getComments = "
                    SELECT 	*
                    FROM 	ug_comments
                    WHERE 	comments_cid = '" . (int) $item['c_id'] . "'";
                $sqlComments = $db->GetQuery($getComments);
                $itemComments = $db->GetNumRows($sqlComments);

                /*$itemDateTime = explode(' ', $item['c_date']);
                $itemDateTime2 = explode('-', $itemDateTime[0]);
                $itemDate = $itemDateTime2[2].'/'.$itemDateTime2[1];*/

                $itemDate = strtotime($item['c_date']);

                /*$todayDateTime = explode(' ', $dateTime);
                $todayDateTime2 = explode('-', $todayDateTime[0]);
                if ($itemDateTime2 == $todayDateTime2)
                {
                        $itemTime = explode(':', $itemDateTime[1]);
                        $itemDate = $itemTime[0].':'.$itemTime[1];
                }*/

                //$itemTag = $ug->GetPlatformTag($item['c_platforms']);
                $itemTag = $item['c_platforms'];

                if($detailed)
                {
                        $getAuthor	= "
                            SELECT 	*
                            FROM	ug_crew
                            WHERE	crew_id = '" . (int) $item['c_author_id'] . "'
                            LIMIT	1";
                        $sqlAuthor = $db->GetQuery($getAuthor);

                        $author = $db->GetArray($sqlAuthor);
                        $itemAuthor = $author['crew_name'];

                        $itemText = $this->ConvertBB($item['c_text']);
                }

                $itemEntry = array(
                    'id'        => (int) $item['c_id'],
                    'type'      => (int) $item['c_type'],
                    'title'     => (string) stripslashes($item['c_title']),
                    'tag'       => (string) $itemTag,
                    'date'      => (int) $itemDate,
                    'comments'  => (int) $itemComments,
                    'author'    => ($detailed) ? (string) $itemAuthor : '',
                    'text'      => ($detailed) ? (string) $itemText : ''
                );

                array_push($itemArray, $itemEntry);
        }

        return $itemArray;
    }

    function GetContentItem($id) {
        global $db;
        
        if((int) $id <= 0) { return false; }

        // Get the content data
        $getContentItem = "
            SELECT  *
            FROM    ug_content
            WHERE   c_id = '" . (int) $db->EscapeString($id) . "'
            LIMIT   1";
        $sqlContentItem = $db->GetQuery($getContentItem);
        $content = $db->GetArray($sqlContentItem);

        if(!$content) { return false; }

        // Get the author
        $getAuthor = "
            SELECT 	crew_name
            FROM	ug_crew
            WHERE	crew_id = '" . (int) $content['c_author_id'] . "'
            LIMIT	1";
        $sqlAuthor = $db->GetQuery($getAuthor);
        $author = $db->GetArray($sqlAuthor);
        $contentAuthor = $author['crew_name'];
        
        // Get the comments
        $getComments = "
            SELECT 	comments_id
            FROM 	ug_comments
            WHERE 	comments_cid = '" . (int) $news['c_id'] . "'";
        $sqlComments = $db->GetQuery($getComments);
        $contentComments = $db->GetNumRows($sqlComments);

        // Get the remaining data
        $contentDate = strtotime($content['c_date']);
        $contentTag = $this->GetPlatformTag($content['c_platforms']);
        $contentTags = $this->GetPlatformTags(explode('|', $content['c_platforms']));
        $contentText = $this->ConvertBB(trim($content['c_text']));

        // Return the data
        $newsEntry = array(
            'id'            => (int) $content['c_id'],
            'type'          => (int) $content['c_type'],
            'subtype'       => (int) $content['c_sub_type'],
            'active'        => (int) $content['c_active'],
            'title'         => (string) stripslashes(trim($content['c_title'])),
            'description'   => (string) stripslashes(trim($content['c_description'])),
            'conclusion'    => (string) stripslashes(trim($content['c_review_conclusion'])),
            'tag'           => (string) $contentTag,
            'tags'          => (string) $contentTags,
            'author'        => (string) $contentAuthor,
            'date'          => (int) $contentDate,
            'comments'      => (int) $contentComments,
            'rating'        => (int) $content['c_review_rating'],
            'object'        => (int) $content['c_obj_id'],
            'objecttype'    => (int) $content['c_obj_type'],
            'spotlight'     => (int) $content['c_spotlight'],
            'text'          => (string) $contentText,
            'text_orig'     => (string) stripslashes(trim($content['c_text']))
        );
        
        return $newsEntry;
    }

    function EditContentItem($id, $data) {
        global $db;

        $id = (int) $id;
        $title = (string) $data['title'];
        $description = (string) $data['description'];
        $conclusion = (string) $data['conclusion'];
        $rating = (int) $data['rating'];
        $active = (int) $data['active'];
        $objecttype = (int) $data['objecttype'];
        $object = (int) $data['object'];
        $text = (string) $data['text'];
        $spotlight = (int) $data['spotlight'];
        $subtype = (int) $data['subtype'];
        $platforms = (string) $data['platforms'];
        $date_online = (string) $data['dateonline'];
        $event = (int) $data['event'];
        $editor_id = (int) $data['editorid'];

        // Get the content data
        $editContentItem = "
            UPDATE  ug_content
            SET     c_title = '" . (string) $db->EscapeString($title) . "',
                    c_text = '" . (string) $db->EscapeString($text) . "',
                    c_description = '" . (string) $db->EscapeString($description) . "',
                    c_review_conclusion = '" . (string) $db->EscapeString($conclusion) . "',
                    c_review_rating = '" . (string) $db->EscapeString($rating) . "',
                    c_active = '" . (string) $db->EscapeString($active) . "',
                    c_obj_type = '" . (string) $db->EscapeString($objecttype) . "',
                    c_obj_id = '" . (string) $db->EscapeString($object) . "',
                    c_spotlight = '" . (string) $db->EscapeString($spotlight) . "',
                    c_sub_type = '" . (string) $db->EscapeString($subtype) . "',
                    c_platforms = '" . (string) $db->EscapeString($platforms) . "',
                    c_date_online = '" . (string) $db->EscapeString($date_online) . "',
                    c_event = '" . (string) $db->EscapeString($event) . "',
                    c_editor_id = '" . (string) $db->EscapeString($editor_id) . "'
            WHERE   c_id = '" . (int) $db->EscapeString($id) . "'
            LIMIT   1";
        $sqlContentItem = $db->GetQuery($editContentItem);
        if(!$sqlContentItem) { return FALSE; }

        return TRUE;
    }

    function CreateContentItem($type) {
        global $db;

        $type = (int) $type;

        //TODO: De platform en date_online data ergens anders verwerken
        // Get the content data
        $createContentItem = "INSERT INTO ug_content (c_type, c_platforms, c_date, c_date_online) VALUES ('" . (int) $db->EscapeString($type) . "', 'main', '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "')";
        $sqlContentItem = $db->GetQuery($createContentItem);
        if(!$sqlContentItem) { return FALSE; }
        $insertid = (int) $db->GetInsertId();

        return $insertid;
    }

    /*function GetItemType($id) {
        global $db;
        
        $getItemType = "
            SELECT  c_type
            FROM    ug_content
            WHERE   c_id = '" . (int) $db->EscapeString($id) . "'
            LIMIT   1";
        $sqlItemType = $db->GetQuery($getItemType);

        $typeArray = $db->GetArray($sqlItemType);
        $type = (int) $typeArray['c_type'];

        return $type;
    }*/

    function ConvertBB($bericht) {
        /* Init */
        $bericht = stripslashes($bericht);
        $bericht = nl2br($bericht);

        /* Images */
        $bericht = str_replace("[img]","<img src=\"http://",$bericht);
        $bericht = str_replace("[/img]","\">",$bericht);

        /* Links */
        $bericht = eregi_replace("\[url\]www.([^\[]*)","<a href=\"http://www.\\1\" target=_blank>www.\\1", $bericht);
        $bericht = eregi_replace("\[url\]([^\[]*)","<a href=\"\\1\" target=_blank>\\1", $bericht);
        $bericht = eregi_replace("(\[url=)([A-Za-z0-9_~&=;\?:%@#./\-]+[A-Za-z0-9/])(\])", "<a href=\"\\2\" target=_blank>", $bericht);
        $bericht = eregi_replace("(\[/url\])", "</a>", $bericht);
        $bericht = str_replace("http://http://", "http://", $bericht);

        /* Bold, Italic, Underlined */
        $bericht = str_replace("[b]","<b>",$bericht);
        $bericht = str_replace("[/b]","</b>",$bericht);
        $bericht = str_replace("[i]","<i>",$bericht);
        $bericht = str_replace("[/i]","</i>",$bericht);
        $bericht = str_replace("[u]","<u>",$bericht);
        $bericht = str_replace("[/u]","</u>",$bericht);

        /* Headings */
        $bericht = str_replace("[h1]","<h1>",$bericht);
        $bericht = str_replace("[/h1]","</h1>",$bericht);
        $bericht = str_replace("[h2]","<h2>",$bericht);
        $bericht = str_replace("[/h2]","</h2>",$bericht);
        $bericht = str_replace("[h3]","<h3>",$bericht);
        $bericht = str_replace("[/h3]","</h3>",$bericht);

        /* Font */
        $bericht = eregi_replace("(\[color=)([A-Za-z0-9#]*)(\])", "<font color=\"\\2\">", $bericht);
        $bericht = eregi_replace("(\[color=)([\"])([A-Za-z0-9#]*)([\"])(\])", "<font color=\"\\3\">", $bericht);
        $bericht = eregi_replace("(\[/color\])", "</font>", $bericht);
        $bericht = eregi_replace("(\[size=)([0-9]*)(\])", "<font size=\"\\2\">", $bericht);
        $bericht = eregi_replace("(\[size=)([\"])([0-9]*)([\"])(\])", "<font size=\"\\3\">", $bericht);
        $bericht = eregi_replace("(\[/size\])", "</font>", $bericht);

        /* Aligning */
        $bericht = str_replace("[center]","<div align=\"center\">",$bericht);
        $bericht = str_replace("[/center]","</div>",$bericht);
        $bericht = str_replace("[left]","<div align=\"left\">",$bericht);
        $bericht = str_replace("[/left]","</div>",$bericht);
        $bericht = str_replace("[right]","<div align=\"right\">",$bericht);
        $bericht = str_replace("[/right]","</div>",$bericht);

        /* Screenshots */
        $bericht = eregi_replace("(\[screen\])([0-9]*)(\[/screen\])", "<a href=\"http://www.undercover-gaming.nl/media.php?p=screenshot&id=\\2\"><img style=\"border: solid #222222 1px; width: 564px; margin: 3px 3px 3px 3px;\" src=\"http://www.undercover-gaming.nl/screenshot.php?id=\\2\" alt=\"\" /></a>", $bericht);
        $bericht = eregi_replace("(\[screen split=3\])([0-9]*)(\[/screen\])", "<a href=\"http://www.undercover-gaming.nl/media.php?p=screenshot&id=\\2\"><img style=\"border: solid #222222 1px; width: 186px; height: 140px; margin: 3px 3px 3px 3px; float: left;\" src=\"http://www.undercover-gaming.nl/screenshot.php?id=\\2\" alt=\"\" /></a>", $bericht);
        $bericht = eregi_replace("(\[screen split=2\])([0-9]*)(\[/screen\])", "<a href=\"http://www.undercover-gaming.nl/media.php?p=screenshot&id=\\2\"><img style=\"border: solid #222222 1px; width: 279px; height: 209px; margin: 3px 3px 3px 3px; float: left;\" src=\"http://www.undercover-gaming.nl/screenshot.php?id=\\2\" alt=\"\" /></a>", $bericht);

        /* Quotes */
        $bericht = str_replace("[quote]","<div style=\"width: 90%; margin-left: 5%; font-size: 8pt;\">Quote:<hr color=\"#999999\">",$bericht);
        $bericht = str_replace("[/quote]","<hr color=\"#999999\"></div>",$bericht);

        /* Code */
        $bericht = str_replace("[code]","<table border=\"0\" width=\"90%\"><tr><td width=\"10\">&nbsp;</td><td><font size=\"1\">Code:</font></td></tr><tr><td width=\"10\">&nbsp;</td><td><hr color=\"#999999\">",$bericht);
        $bericht = str_replace("[/code]","<hr color=\"#999999\"></td></tr></table>",$bericht);

        /* List */
        //$bericht = eregi_replace("(\[list=)([A-Za-z0-9])(\])(.*)((\[/list\])", "<ol type=\"\\2\">\\4</ol>", $bericht);
        //$bericht = eregi_replace("(\[list=)([\"])([A-Za-z0-9])([\"])(\])", "<ol type=\"\\3\">", $bericht);
        //$bericht = eregi_replace("(\[/list\])", "</ol>", $bericht);
        //$bericht = eregi_replace("(\[list\])(.*)(\[/list\])", "<ul>lijst</ul>", $bericht);
        //$bericht = eregi_replace("(\[/list\])", "</ul>", $bericht);
        //$bericht = eregi_replace("(\[\*\])([A-Za-z0-9]*)(<br />)", "<li>\\2</li>", $bericht);

        return $bericht;
    }

    function GetPlatformTags($platforms) {
        global $db;

        $platformTag = "";
        foreach($platforms as $platformId) {
            $getPlatform = "
                SELECT 	*
                FROM	ug_platforms
                WHERE	p_id = '" . (int) $db->EscapeString($platformId) . "'
                LIMIT 1";
            $sqlPlatform = $db->GetQuery($getPlatform);
            $rowPlatform = $db->GetArray($sqlPlatform);
            $platformTag .= '<div class="category category_' . strtolower($rowPlatform['p_short']) . '"></div>';
        }

        if($platformTag == "")
        {
            $platformTag = '<div class="category category_main"></div>';
        }

        return $platformTag;
    }

    function GetPlatformTag($platforms) {
        global $db;

        if(in_array('main', explode('|', $platforms)))
        {
            $platformTag = "main";
        }
        elseif(in_array('multi', explode('|', $platforms)))
        {
            $platformTag = "multi";
        }
        else
        {
            $platforms = "(" . str_replace("|", ",", $platforms) . ")";
            $getPlatformTags = "SELECT * FROM ug_platforms WHERE p_id IN " . $db->EscapeString($platforms);
            $sqlPlatformTags = $db->GetQuery($getPlatformTags);

            switch($db->GetNumRows($sqlPlatformTags))
            {
                case 0:
                    $platformTag = "main";
                    break;
                case 1:
                    $platformTags = $db->GetArray($sqlPlatformTags);
                    $platformTag = strtolower($platformTags['p_short']);
                    break;
                default:
                    $platformTag = "multi";
                    break;
            }
        }
        $platformTag = '<div class="category category_' . $platformTag . '"></div>';
        return $platformTag;
    }

    function GetObject($type, $id) {
        switch($type) {
            case 1:
                // Game
                $object = $this->GetGame($id);
                break;
            case 2:
                // Company
                $object = $this->GetCompany($id);
                break;
            default:
                // None
                $object = array();
                break;
        }

        return $object;
    }

    function GetGames() {
        global $db;
        $games = array();

        $getGames = "SELECT g_id, g_title FROM ug_games";
        $sqlGames = $db->GetQuery($getGames);
        while($game = $db->GetArray($sqlGames)) {
            $games[(int) $game['g_id']] = (string) $game['g_title'];
        }

        return $games;
    }

    function GetCompanies() {
        global $db;
        $companies = array();

        $getCompanies = "SELECT c_id, c_name FROM ug_companies";
        $sqlCompanies = $db->GetQuery($getCompanies);
        while($company = $db->GetArray($sqlCompanies)) {
            $companies[(int) $company['c_id']] = (string) $company['c_name'];
        }

        return $companies;
    }

    function GetGame($id) {
        global $db;
        
        $getGame = "SELECT g_dev_id, g_pub_id, g_platforms, g_genre, g_title, g_description, g_website, g_multiplayer, g_image, g_release FROM ug_games WHERE g_id = '" . (int) $db->EscapeString($id) . "'";
        $sqlGame = $db->GetQuery($getGame);
        $game = $db->GetArray($sqlGame);
        
        return array(
            'id' => (int) $id,
            'type' => (string) 'game',
            'developer' => (int) $game['g_dev_id'],
            'publisher' => (int) $game['g_pub_id'],
            'platforms' => (string) trim($game['g_platforms']),
            'genre' => (int) $game['g_genre'],
            'title' => (string) stripslashes(trim($game['g_title'])),
            'description' => (string) stripslashes(trim($game['g_description'])),
            'website' => (string) stripslashes(trim($game['g_website'])),
            'multiplayer' => (int) $game['g_multiplayer'],
            'image' => (int) $game['g_image'],
            'release' => (int) strtotime($game['g_release'])
        );
    }

    function GetCompany($id) {
        global $db;

        $getCompany = "SELECT c_type, c_name, c_description FROM ug_companies WHERE c_id = '" . (int) $db->EscapeString($id) . "'";
        $sqlCompany = $db->GetQuery($getCompany);
        $company = $db->GetArray($sqlCompany);

        return array(
            'id' => (int) $id,
            'type' => (string) 'company',
            'name' => (string) stripslashes(trim($company['c_name'])),
            'description' => (string) stripslashes(trim($company['c_description']))
        );
    }

    function GetImage($id) {
        global $db;
        
        if((int) $id <= 0) { return "<img style='width: 110px; height: 126px;' src='images/avatar.jpg' class='avatar'>"; }
        $id = (int) $id;

        $getImage = "SELECT i_filename, i_location FROM ug_images WHERE i_id = '" . (int) $db->EscapeString($id) . "' LIMIT 1";
        $sqlImage = $db->GetQuery($getImage);
        $image = $db->GetArray($sqlImage);
        $imagefilename = $image['i_filename'];
        $imagelocation = $image['i_location'];

        if(file_exists("media/$imagelocation/$imagefilename")) {
            $imagefile = "<img style='width: 110px;' src='media/$imagelocation/$imagefilename'>";
        } else {
            $imagefile = "<img style='width: 110px; height: 126px;' src='images/avatar.jpg' class='avatar'>";
        }

        return $imagefile;
    }

    function GetGenre($id) {
        global $db;

        if((int) $id <= 0) { return "-"; }
        $id = (int) $id;

        $getGenre = "SELECT g_name FROM ug_genres WHERE g_id = '" . (int) $db->EscapeString($id) . "' LIMIT 1";
        $sqlGenre = $db->GetQuery($getGenre);
        $genre = $db->GetArray($sqlGenre);
        $genre = (string) $genre['g_name'];

        return $genre;
    }

    function GetGameRating($id) {
        //TODO: Deze functie maken
        return (int) 90;
    }

    function GetRatingImage($rating) {
        if((int) $rating < 0) { return "<img src='layout/rating/no.png' class='gameinfo_cijfer'>"; }
        if((int) $rating > 100) { return "<img src='layout/rating/no.png' class='gameinfo_cijfer'>"; }
        $rating = (int) $rating;

        $rating_d = $rating / 10;
        $full = (int) floor($rating_d);
        $part = ($rating_d > $full) ? "5" : "";

        $image = "<img src='layout/rating/$full$part.png' class='gameinfo_cijfer'>";

        return $image;
    }
    
}

?>