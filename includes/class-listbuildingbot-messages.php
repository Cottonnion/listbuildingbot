<?php

class MessageManager {
    private $table_name;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'lbb_messages';
        $this->conversation_table = $wpdb->prefix . 'lbb_conversations';
        $this->table_tag_name = $wpdb->prefix . 'lbb_tags';
        $this->table_outcome_name = $wpdb->prefix . 'lbb_outcomes';
        $this->contacts_table = $wpdb->prefix . 'lbb_contacts';
        $this->contacts_meta_table = $wpdb->prefix . 'lbb_contactmeta';
    }

    public function insertMessage($args) {
        $defaults = array(
            'conversation_id' => 0,
            'user_id' => '',
            'message_text' => '',
            'sent_time' => current_time('mysql'),
            'is_bot_response' => 0,
        );

        $data = wp_parse_args($args, $defaults);

        $this->wpdb->insert($this->table_name, $data);

        if ($this->wpdb->last_error) {
            return false; // Insertion failed
        }

        return $this->wpdb->insert_id; // Return the inserted row ID (message_id)
    }

    public function getMessagesByConversationId($conversation_id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE conversation_id = %d",
            $conversation_id
        );

        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function getTagsByUserId($user_id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE user_id = %d",
            $user_id
        );

        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function getMessagesByConversationIdWithOffset($conversation_id, $chat_per_request, $offset) {

        $limit = ($chat_per_request != -1) ? ' LIMIT '.$chat_per_request.' OFFSET '.$offset : '';

        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE conversation_id = %d ORDER BY message_id DESC". $limit, $conversation_id);

        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function getMessagesByConversationIdWithoutOffset($conversation_id) {

        

        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE conversation_id = %d ORDER BY message_id DESC", $conversation_id);

        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function getLastBotMessageByConversationId($conversation_id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE conversation_id = %d ORDER BY sent_time DESC LIMIT 1",
            $conversation_id
        );

        $row = $this->wpdb->get_row($query, ARRAY_A);

        if(!empty($row)){
            return $row['is_bot_response'];
        }

        return 0;
    }

    public function getMessageFromConversationByAction($conversationId, $actionId){
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE conversation_id = %d AND action_id = %d ORDER BY sent_time DESC LIMIT 1",
            $conversationId, $actionId
        );
    
        $lastEmail = $this->wpdb->get_row($query, ARRAY_A);
        if(!empty($lastEmail)){
            return $lastEmail['message_text'];
        }

        return '';
    }  

    public function getMessageFromConversationByActions($conversationId, $actionIds){
        $query = "SELECT * FROM $this->table_name WHERE conversation_id = ".$conversationId." AND action_id IN(".$actionIds.") ORDER BY sent_time DESC LIMIT 1";
    
        $lastEmail = $this->wpdb->get_row($query, ARRAY_A);
        if(!empty($lastEmail)){
            return $lastEmail['message_text'];
        }

        return '';
    }  
    
    public function getTagsByConversation($conversationId){

        $query = $this->wpdb->prepare(
            "SELECT GROUP_CONCAT(t.name) AS tagname FROM {$this->table_tag_name} t INNER JOIN {$this->table_name} m ON FIND_IN_SET(t.id, m.tags) WHERE m.conversation_id = %d",
            $conversationId
        );
    
        $result = $this->wpdb->get_row($query);
        if(!empty($result)){
            return $result->tagname;
        }

        return '';
    }

    public function getOutcomesByConversation($conversationId){

        $query = $this->wpdb->prepare(
            "SELECT GROUP_CONCAT(t.name) AS outcomename FROM {$this->table_outcome_name} t INNER JOIN {$this->table_name} m ON FIND_IN_SET(t.id, m.outcomes) WHERE m.conversation_id = %d",
            $conversationId
        );
    
        $result = $this->wpdb->get_row($query);
        if(!empty($result)){
            return $result->outcomename;
        }


        return '';
    }

    public function loadUserChatList($chat_per_request, $offset,$user_id,$filter = array()) {

        $limit = ($chat_per_request != -1) ? ' LIMIT '.$chat_per_request.' OFFSET '.$offset : '';

        $where = 'WHERE m.conversation_id = c.conversation_id';

        //$where = ($user_id) ? ' WHERE user_id = '.$user_id : '';
        
        if(isset($filter['mode'])){
            if($filter['mode'] == 'A'){
                $where .= " AND status IN ('A', 'T')";
            }else{
                $where .= " AND status = '" . $filter['mode']."'";
            }
        }

        /*$query = "SELECT *,
        (SELECT firebase_id 
        FROM {$this->conversation_table} 
        WHERE {$this->conversation_table}.conversation_id = {$this->table_name}.conversation_id) AS firebase_id FROM {$this->table_name}  ".$where." GROUP BY conversation_id ORDER BY sent_time DESC $limit";*/

       $query = "SELECT m.*,c.status,c.firebase_id FROM {$this->table_name} AS m, {$this->conversation_table} AS c ".$where." AND `is_published`=1 GROUP BY m.conversation_id ORDER BY m.sent_time DESC $limit";
        
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function loadAll() {
        $query = "SELECT * FROM {$this->table_name}";

        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function deleteByConversationId($conversationId){
        $message_query = "DELETE FROM {$this->table_name} WHERE conversation_id = $conversationId";
        $this->wpdb->query($message_query);
        
        $conversations_query = "DELETE FROM {$this->conversation_table} WHERE conversation_id = $conversationId";
        $this->wpdb->query($conversations_query);

        $query = $this->wpdb->prepare("SELECT id FROM {$this->contacts_table} WHERE conversation_id = %d",$conversationId);

        $contact = $this->wpdb->get_row($query, ARRAY_A);
        $contact_id = $contact['id'];
        $contacts_query = "DELETE FROM {$this->contacts_table} WHERE conversation_id = $conversationId";
        $this->wpdb->query($contacts_query);

        $contacts_meta_query = "DELETE FROM {$this->contacts_meta_table} WHERE contact_id = $contact_id";
        $this->wpdb->query($contacts_meta_query);


    }
}
