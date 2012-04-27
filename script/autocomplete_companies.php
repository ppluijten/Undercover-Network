<?php

header("content-type: text/javascript");

require_once "../header.php";

?>

$(function() {
    var availableTagsDeveloper = [

<?php

$developers = Content::GetDevelopers();
foreach($developers as $developername) {
    echo "\"$developername\",\n";
}

?>

        ];
    $("#developer").autocomplete({source: availableTagsDeveloper});
    
    var availableTagsPublisher = [
    
<?php

$publishers = Content::GetPublishers();
foreach($publishers as $publishername) {
    echo "\"$publishername\",\n";
}

?>

        ];
    $("#publisher").autocomplete({source: availableTagsPublisher});
});