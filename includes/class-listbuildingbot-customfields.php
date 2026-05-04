<?php

class CustomFieldManager {
    private $table_name;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'lbb_customfields';
    }

    public function insertcustomfield($args) {
        $defaults = array(
            'name' => '',
            'label' => '',
            'field_type' => '',
            'required' => 'N',
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

    public function loadById($customfield_id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE id = %d",
            $customfield_id
        );
        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function update($args,$lbb_customfield_id) {
        $defaults = array(
            'name' => '',
            'label' => '',
            'field_type' => '',
            'required' => 'N',
        );

        $data = wp_parse_args($args, $defaults);
        $this->wpdb->update($this->table_name, $data, array('id'=>$lbb_customfield_id));
        if ($this->wpdb->last_error) {
            return false; // Insertion failed
        }
        return $lbb_customfield_id;
    }

    public function deleteById($customfield_id) {
        $this->wpdb->delete($this->table_name, array('id'=>$customfield_id));
        return $customfield_id;
    }
}
