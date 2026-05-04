<?php

class OutcomesManager {
    private $table_name;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'lbb_outcomes';
    }

    public function insert($args) {
        $defaults = array(
            'name' => '',
            'content' => '',
            'chatflow_id' => '',
            'outcome_image' => '',
        );
        
        $data = wp_parse_args($args, $defaults);

        $this->wpdb->insert($this->table_name, $data);

        if ($this->wpdb->last_error) {
            return false; // Insertion failed
        }

        return $this->wpdb->insert_id; // Return the inserted row ID (message_id)
    }

    public function loadAll() {
        $query = "SELECT * FROM {$this->table_name} ORDER BY id DESC";
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function loadById($id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE id = %d",
            $id
        );
        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function loadByName($name,$chatflow_id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE chatflow_id = %d AND name = %s",
            $chatflow_id,$name
        );
        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function loadAllByChatflow() {
        $query = "SELECT id, chatflow_id, GROUP_CONCAT(name ORDER BY name ASC SEPARATOR ', ') AS names
          FROM {$this->table_name}
          GROUP BY chatflow_id ORDER BY chatflow_id DESC";
        
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function loadFirst($chatflow_id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE chatflow_id = %d",
            $chatflow_id
        );
        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function loadByIds($ids) {
        if (!is_array($ids)) {
            return []; // Return an empty array if $ids is not an array
        }
    
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE id IN ({$placeholders})",
            $ids
        );
        
    
        return $this->wpdb->get_results($query, ARRAY_A);
    }
    public function loadByChatflowId($id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE chatflow_id = %d",
            $id
        );
        return $this->wpdb->get_results($query, ARRAY_A);
    }
    

    public function update($args,$lbb_id) {
        $defaults = array(
            'name' => '',
            'content' => '',
            'chatflow_id' => '',
            'outcome_image' => '',
        );

        $data = wp_parse_args($args, $defaults);
        $this->wpdb->update($this->table_name, $data, array('id'=>$lbb_id));
        if ($this->wpdb->last_error) {
            return false; // Insertion failed
        }
        return $lbb_id;
    }

    public function deleteById($id) {
        $this->wpdb->delete($this->table_name, array('id'=>$id));
        return $id;
    }

    public function deleteByChatflowId($chatflow_id) {
        $this->wpdb->delete($this->table_name, array('chatflow_id'=>$chatflow_id));
        return $chatflow_id;
    }
}
