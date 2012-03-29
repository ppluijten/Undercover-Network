<?php

if(isset($_GET)) {
    // GET variables were used
    if(isset($_GET['referer'])) {
        // A referer URL was set
        //TODO: Make this a safe url
        $referer = $_GET['referer'];
    } else {
        // No referer was set
        $referer = "http://www.undercover-network.nl/index.php";
    }

    if(isset($_GET['target'])) {
        // A target was set
        //TODO: Make this a safe url
        $target = $_GET['target'];
    } else {
        // No target was set
        $target = "http://www.undercover-network.nl/index.php";
    }
} else {
    // No GET variables were used
    $referer = "http://www.undercover-network.nl/index.php";
    $target = "http://www.undercover-network.nl/index.php";
}

?>

<iframe id="loadingframe" src="<?php echo $target; ?>" style="display: none;"></iframe>

<script type="text/javascript">
    document.getElementById('loadingframe').onload = function(){
       parent.location.replace('<?php echo $referer; ?>');
    };
</script>

<img alt="Loading... " src="images/ajax-loader.gif" /> Loading...