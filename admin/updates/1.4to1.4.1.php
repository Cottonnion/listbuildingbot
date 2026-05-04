<?php

global $wpdb;
$updateSql = array();

$lbb_tags = $wpdb->prefix . 'lbb_tags';

if(lbb_check_column_exist($lbb_tags,'name')){
    $updateSql[] = "ALTER TABLE `".$lbb_tags."` CHANGE `name` `name` varchar(255)";
}

