<?php

global $wpdb;
$updateSql = array();

$lbb_messages = $wpdb->prefix . 'lbb_messages';

if(!lbb_check_column_exist($lbb_tags,'notification_delivered')){
    $updateSql[] = "ALTER TABLE `".$lbb_messages."` ADD `notification_delivered` TEXT NOT NULL AFTER `is_read`";
}