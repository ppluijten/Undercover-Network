<?php

require_once "header.php";

echo "<pre>" . var_export(User::getUserDataArray(), 1) . "</pre>";
echo "<pre>" . var_export($_COOKIE, 1) . "</pre>";
echo "<img src='http://www.undercover-gaming.nl/forum/image.php?u=" . User::getUserId() . "'>";

?>