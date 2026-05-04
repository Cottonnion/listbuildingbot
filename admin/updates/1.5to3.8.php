<?php

global $wpdb;
$updateSql = array();

$lbb_conversations = $wpdb->prefix . 'lbb_conversations';

if(!lbb_check_column_exist($lbb_tags,'is_published')){
    $updateSql[] = "ALTER TABLE `".$lbb_conversations."` ADD `is_published` INT(1) DEFAULT 1;";
}