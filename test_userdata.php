<?php

/*$_COOKIE = array (
  'PHPSESSID' => 'a9745ca4d084ca5ecd149464c9c3b1d2',
  'bb_sessionhash' => '282af89e466231645cc9fe23b635ebfe',
  'bb_lastvisit' => '1332882280',
  'bb_lastactivity' => '0',
  'bb_userid' => '589',
  'bb_password' => 'fa028bcd806cebf2a6b75d0bce4dd6ed',
);*/

require_once "header.php";

echo "<pre>" . var_export(User::getUserDataArray(), 1) . "</pre>";
echo "<pre>" . var_export($_COOKIE, 1) . "</pre>";
echo "<img src='http://www.undercover-gaming.nl/forum/image.php?u=" . User::getUserId() . "'>";

?>