<?php

class FaqManager {
    private $table_name;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'lbb_faq_master';
    }

    public function insert($args) {
        $defaults = array(
            'question' => '',
            'answer' => '',
        );

        $data = wp_parse_args($args, $defaults);

        $this->wpdb->insert($this->table_name, $data);
        
        if ($this->wpdb->last_error) {
            return false; // Insertion failed
        }

        return $this->wpdb->insert_id; // Return the inserted row ID (message_id)
    }

    public function loadAll() {
        $query = "SELECT * FROM {$this->table_name}";
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
            'question' => '',
            'answer' => '',
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
}