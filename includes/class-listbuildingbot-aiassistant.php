<?php

class AiassistantManager {
    private $table_name;
    private $assistant_mapping;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'lbb_ai_assistant';
        $this->assistant_mapping = $wpdb->prefix . 'lbb_ai_assistant_mapping';
    }

    public function insert($args) {
        $defaults = array(
            'source' => '',
            'content' => '',
            'title' => '',
            'description' => '',
            'type' => '',
            'ai_file_id' => '',
        );

        $data = wp_parse_args($args, $defaults);

        //echo '<pre>';print_r($data);
        //echo $this->table_name;
        $this->wpdb->insert($this->table_name, $data);
        
        if ($this->wpdb->last_error) {
            return false; // Insertion failed
        }

        return $this->wpdb->insert_id; // Return the inserted row ID (message_id)
    }

    // Insert a  for a assistant mapping
    public function insertMapping($assi_id, $chat_id) {
        global $wpdb;
        $wpdb->insert(
            $this->assistant_mapping,
            array(
                'chat_id' => $chat_id,
                'assi_id' => $assi_id,
            )
        );
        return $wpdb->insert_id;
    }

    public function loadAll() {
        $query = "SELECT * FROM {$this->table_name}";
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function loadByMapping($chat_id, $type) {
        $query = "SELECT {$this->assistant_mapping}.*, {$this->table_name}.* 
        FROM
            {$this->assistant_mapping}
        JOIN 
            {$this->table_name} ON {$this->assistant_mapping}.assi_id = {$this->table_name}.id WHERE {$this->assistant_mapping}.chat_id = $chat_id AND {$this->table_name}.type = '".$type."'";

        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function loadById($id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE id = %d",
            $id
        );
        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function update($args,$id) {
        $defaults = array(
            'source' => '',
            'content' => '',
            'title' => '',
            'description' => '',
            'type' => '',
            'ai_file_id' => '',
        );

        $data = wp_parse_args($args, $defaults);
        $this->wpdb->update($this->table_name, $data, array('id'=>$id));
        if ($this->wpdb->last_error) {
            return false; // Insertion failed
        }
        return $id;
    }

    public function deleteById($id) {
        $this->wpdb->delete($this->table_name, array('id'=>$id));
        return $id;
    }

    public function deleteByFileId($ai_file_id, $chat_id) {
        $query = $this->wpdb->prepare(
            "SELECT id FROM {$this->table_name} WHERE ai_file_id = '".$ai_file_id."'");
        $get_id = $this->wpdb->get_row($query, ARRAY_A);
        $get_id = $get_id['id'];
        $this->wpdb->delete($this->assistant_mapping, array('assi_id'=>$get_id, 'chat_id'=> $chat_id));
        $this->wpdb->delete($this->table_name, array('ai_file_id'=>$ai_file_id));
        return $id;
    }
}