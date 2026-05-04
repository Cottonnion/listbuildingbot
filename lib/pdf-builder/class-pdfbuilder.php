<?php

class PDFContentManager {
    private $table_name;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'lbb_pdfbuilder';
    }

    public function insert($args) {
        $defaults = array(
            'name' => '',
            'content' => '',
            'other_options' => '',
            'date' => '',
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

    public function loadById($pdfbuilder_id) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE id = %d",
            $pdfbuilder_id
        );
        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function update($args,$lbb_pdfbuilder_id) {
        $defaults = array(
            'name' => '',
            'content' => '',
            'other_options' => '',
            'date' => date('Y-m-d H:m:s'),
        );

        $data = wp_parse_args($args, $defaults);
        $this->wpdb->update($this->table_name, $data, array('id'=>$lbb_pdfbuilder_id));
        if ($this->wpdb->last_error) {
            return false; // Insertion failed
        }
        return $lbb_pdfbuilder_id;
    }

    public function deleteById($pdfbuilder_id) {
        $this->wpdb->delete($this->table_name, array('id'=>$pdfbuilder_id));
        return $pdfbuilder_id;
    }
}
