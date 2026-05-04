<?php

global $wpdb;
$updateSql = array();

$bb_ai_assistant = $wpdb->prefix . 'lbb_ai_assistant';

if(!lbb_check_column_exist($bb_ai_assistant,'ai_file_id')){
    $updateSql[] = "ALTER TABLE `".$bb_ai_assistant."` ADD `ai_file_id` varchar(255)";
}

$lbb_faq_table = $wpdb->prefix . 'lbb_faq_master';

$charset_collate = $wpdb->get_charset_collate();

$table_schema = "id int(11) NOT NULL AUTO_INCREMENT,
question varchar(255) NOT NULL,
answer longtext,
PRIMARY KEY (id)";

$updateSql[] = "CREATE TABLE IF NOT EXISTS $lbb_faq_table ($table_schema) $charset_collate;";