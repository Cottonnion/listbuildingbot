<?php

class ConversationManager {
    private $table_name;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'lbb_conversations';
    }

    public function getAllConversation() {
        $query = "SELECT * FROM {$this->table_name}";
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function getConversationById($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE conversation_id = '".$id."'";
        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function isConversationEnd($id) {
        $query = "SELECT count(*) as count FROM {$this->table_name} WHERE conversation_id = '".$id."' AND is_closed = 1";
        
        return $this->wpdb->get_var($query);
    }

    public function getAllUniqueConversation() {
        $query = "SELECT DISTINCT user_id FROM {$this->table_name} ORDER BY end_time desc";
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function getConversationByIP($ip, $type = '', $chatflow_id = '') {
        $type_w = !empty($type) ? " AND status = '".$type."'" : "'A'";
        $chatflow_w = !empty($chatflow_id) ? " AND chatflow_id = '".$chatflow_id."'" : "''";
        $query = "SELECT * FROM {$this->table_name} WHERE ip_address = '".$ip."' ".$type_w.$chatflow_w." ORDER BY conversation_id DESC LIMIT 1";
        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function setUserFirebaseId($firebaseId, $conversation_id) {
        global $wpdb;

        $data = array('firebase_id' => $firebaseId);
        $where = array('conversation_id' => $conversation_id);
        $updated = $wpdb->update( $this->table_name, $data, $where );

        if ($updated) {
            return 1;
        } else {
            return 0;
        }
    }

    public function setLiveConversation($conversation_id) {
        global $wpdb;

        $data = array('status' => 'L');
        $where = array('conversation_id' => $conversation_id);
        $updated = $wpdb->update( $this->table_name, $data, $where );

        
        if ($updated) {
            return 1;
        } else {
            return 0;
        }
    }

    public function setPublishedConversation($conversation_id) {
        global $wpdb;

        $data = array('is_published' => '1');
        $where = array('conversation_id' => $conversation_id);
        $updated = $wpdb->update( $this->table_name, $data, $where );

        
        if ($updated) {
            return 1;
        } else {
            return 0;
        }
    }

    public function setLogicBotConversation($conversation_id) {
        global $wpdb;

        $data = array('status' => '');
        $where = array('conversation_id' => $conversation_id);
        $updated = $wpdb->update( $this->table_name, $data, $where );

        if ($updated) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function setTrainedBotConversation($conversation_id) {
        global $wpdb;

        $data = array('status' => 'T');
        $where = array('conversation_id' => $conversation_id);
        $updated = $wpdb->update( $this->table_name, $data, $where );

        if ($updated) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function endConversation($conversation_id) {
        global $wpdb;

        $data = array('end_time' => gmdate('Y-m-d H:i:s'),'is_closed' => 1);
        $where = array('conversation_id' => $conversation_id);
        $updated = $wpdb->update( $this->table_name, $data, $where );

        if ($updated) {
            return 1;
        } else {
            return 0;
        }
    }
}