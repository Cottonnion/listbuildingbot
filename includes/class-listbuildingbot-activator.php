<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wickedcoolplugins.com
 * @since      1.0.0
 *
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Listbuildingbot
 * @subpackage Listbuildingbot/includes
 * @author     Veena Prashanth <veena@digitalaccesspass.com>
 */
class Listbuildingbot_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;
		$lbb_contacts = $wpdb->prefix.'lbb_contacts';
		$lbb_contactmeta = $wpdb->prefix.'lbb_contactmeta';
		$lbb_conversations = $wpdb->prefix.'lbb_conversations';
		$lbb_customfields = $wpdb->prefix.'lbb_customfields';
		$lbb_messages = $wpdb->prefix.'lbb_messages';
		$lbb_messagemeta = $wpdb->prefix.'lbb_messagemeta';
		$lbb_outcomes = $wpdb->prefix.'lbb_outcomes';
		$lbb_pdfbuilder = $wpdb->prefix.'lbb_pdfbuilder';
		$lbb_tags = $wpdb->prefix.'lbb_tags';
		$lbb_ai_assistant = $wpdb->prefix.'lbb_ai_assistant';
		$lbb_ai_assistant_mapping = $wpdb->prefix.'lbb_ai_assistant_mapping';
		$lbb_faq_master = $wpdb->prefix.'lbb_faq_master';

		$charset_collate = $wpdb->get_charset_collate();

		$tables = array(
			$lbb_contacts => "
				id bigint(22) NOT NULL AUTO_INCREMENT,
				user_id int(11) NOT NULL DEFAULT '0',
				conversation_id int(11) NOT NULL,
				firstname varchar(255) NOT NULL,
				lastname varchar(255) NOT NULL,
				email varchar(255) NOT NULL,
				phone varchar(15) NOT NULL,
				status int(1) NOT NULL,
				created_date date NOT NULL,
				PRIMARY KEY (id),
				KEY conversation_id (conversation_id),
				KEY email (email),
				KEY status (status)
			",
			$lbb_contactmeta => "
				id bigint(22) NOT NULL AUTO_INCREMENT,
				contact_id bigint(22) NOT NULL,
				field_name varchar(255) NOT NULL,
				field_value text,
				PRIMARY KEY (id),
				KEY contact_id (contact_id),
				KEY field_name (field_name)
			",
			$lbb_conversations => "
				conversation_id bigint(22) NOT NULL AUTO_INCREMENT,
				user_id bigint(22) NOT NULL,
				start_time datetime NOT NULL,
				end_time datetime NOT NULL,
				ip_address varchar(45) NOT NULL,
				last_action_id int(11) NOT NULL,
				firebase_id varchar(255) NOT NULL,
				status char(1) NOT NULL,
				chatflow_id int(11) NOT NULL,
				is_closed int(1) NOT NULL,
				extra_info text,
				PRIMARY KEY (conversation_id)
			",
			$lbb_customfields => "
				id int(11) NOT NULL AUTO_INCREMENT,
				name varchar(20) DEFAULT NULL,
				label varchar(255) DEFAULT NULL,
				field_type varchar(255) DEFAULT NULL,
				required char(1) NOT NULL DEFAULT 'N',
				PRIMARY KEY (id)
			",
			$lbb_messages => "
				message_id bigint(22) NOT NULL AUTO_INCREMENT,
				conversation_id bigint(22) NOT NULL,
				user_id varchar(255) NOT NULL,
				agent_id bigint(22) NOT NULL DEFAULT '0',
				message_text text NOT NULL,
				message_meta text NOT NULL,
				sent_time datetime NOT NULL,
				is_bot_response int(1) NOT NULL,
				action_id int(22) NOT NULL,
				tags text NOT NULL,
				outcomes text NOT NULL,
				points float NOT NULL,
				is_read int(1) NOT NULL DEFAULT '0',
				PRIMARY KEY (message_id),
				KEY conversation_id (conversation_id),
				KEY action_id (action_id),
				KEY is_read (is_read)
			",
			$lbb_messagemeta => "
				id bigint(22) NOT NULL AUTO_INCREMENT,
				message_id bigint(22) NOT NULL,
				meta_key varchar(255) NOT NULL,
				meta_value text NOT NULL,
				PRIMARY KEY (id),
				KEY message_id (message_id),
				KEY meta_key (meta_key)
			",
			$lbb_outcomes => "
				id int(11) NOT NULL AUTO_INCREMENT,
				chatflow_id int(11) NOT NULL,
				name varchar(255) NOT NULL,
				content longtext DEFAULT NULL,
				outcome_image varchar(255) DEFAULT NULL,
				PRIMARY KEY (id)
			",
			$lbb_pdfbuilder => "
				id int(11) NOT NULL AUTO_INCREMENT,
				name varchar(255) NOT NULL,
				content longtext,
				other_options longtext,
				date datetime NOT NULL,
				PRIMARY KEY (id)
			",
			$lbb_tags => "
				id int(11) NOT NULL AUTO_INCREMENT,
				name varchar(20) NOT NULL,
				description longtext,
				PRIMARY KEY (id)
			",
			$lbb_ai_assistant => "
				id int(11) NOT NULL AUTO_INCREMENT,
				source varchar(255) NOT NULL,
				content longtext,
				title text DEFAULT NULL,
				description longtext,
				type varchar(20) DEFAULT NULL,
				ai_file_id varchar(255) DEFAULT NULL,
				PRIMARY KEY (id)
			",
			$lbb_ai_assistant_mapping => "
				id int(11) NOT NULL AUTO_INCREMENT,
				chat_id int(11) DEFAULT NULL,
				assi_id int(11) DEFAULT NULL,
				PRIMARY KEY (id)
			",
			$lbb_faq_master => "
				id int(11) NOT NULL AUTO_INCREMENT,
				question varchar(255) NOT NULL,
				answer longtext,
				PRIMARY KEY (id)
			"
		);
		
		foreach ($tables as $table_name => $table_schema) {
			$sql = "CREATE TABLE IF NOT EXISTS $table_name ($table_schema) $charset_collate;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		LBBDoInstall();
	}

}
