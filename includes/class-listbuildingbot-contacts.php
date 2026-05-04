<?php



class LBB_Contacts {
    private $table_name;
    private $contactmeta;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'lbb_contacts';
        $this->contactmeta = $wpdb->prefix . 'lbb_contactmeta';
    }

    // Insert a new contact record based on args
    public function insertContact($args) {
        global $wpdb;
        $defaults = array(
            'conversation_id' => 0,
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'status' => '0'
        );

        $args = wp_parse_args($args, $defaults);
        $wpdb->insert(
            $this->table_name,
            $args
        );
        return $wpdb->insert_id;
    }

    public function getContactByConversationId($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE conversation_id = '".$id."'";
        return $this->wpdb->get_row($query, ARRAY_A);
    }

    public function loadAllByStatus($status) {
        $query = "SELECT * FROM {$this->table_name} WHERE status = $status";
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function loadAllByEmail($email) {
        $query = "SELECT * FROM {$this->table_name} WHERE email = '$email'";
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    // Update an existing contact record by ID based on args
    public function updateContact($id, $args, $where = array()) {
        global $wpdb;
        $where = empty($where) ? array('id' => $id) : $where;
        return $wpdb->update(
            $this->table_name,
            $args,
            $where
        );
    }

    // Delete a contact record by ID
    public function deleteContact($id) {
        global $wpdb;
        return $wpdb->delete(
            $this->table_name,
            array('id' => $id)
        );
    }

    // Get contact ID by conversation_id
    public function getContactId($conversation_id) {
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT id FROM {$this->table_name} WHERE conversation_id = %d",
            $conversation_id
        );
        $contact_id = $wpdb->get_var($query);
        return $contact_id;
    }

    
    public function getEmail($id) {
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT email FROM {$this->table_name} WHERE id = %d",
            $id
        );
        $email = $wpdb->get_var($query);
        
        return $email;
    }

   
    public function getName($id) {
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT firstname FROM {$this->table_name} WHERE id = %d",
            $id
        );
        $name = $wpdb->get_var($query);
        return $name;
    }

    public function isExistsMeta($contact_id, $field_id) {
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT count(*) AS count FROM {$this->contactmeta} WHERE contact_id = %d AND field_name = %s",
            $contact_id,
            $field_id
        );
        $count = $wpdb->get_var($query);
        return $count;
    }

    // Insert a custom field for a contact
    public function getMeta($contact_id, $field_name) {
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT field_value FROM {$this->contactmeta} WHERE contact_id = %d AND field_name = %s",
            $contact_id,
            $field_name
        );
        $value = $wpdb->get_var($query);
        return $value;
    }

    public function loadContactmetaByContactId($contact_id) {
        $query = "SELECT * FROM {$this->contactmeta} WHERE contact_id = '$contact_id'";
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    // Insert a custom field for a contact
    public function insertMeta($contact_id, $field_name, $field_value) {
        global $wpdb;
        $wpdb->insert(
            $this->contactmeta,
            array(
                'contact_id' => $contact_id,
                'field_name' => $field_name,
                'field_value' => $field_value,
            )
        );
        return $wpdb->insert_id;
    }

    // Update a custom field for a contact by field ID
    public function updateMeta($contact_id, $field_name, $field_value, $check = false) {
        global $wpdb;
        if($this->isExistsMeta($contact_id, $field_name)){
            return $wpdb->update(
                $this->contactmeta,
                array(
                    'field_value' => $field_value
                ),
                array('contact_id' => $contact_id, 'field_name' => $field_name)
            );
        }else{
            $this->insertMeta($contact_id, $field_name, $field_value);
        }
    }

    // Delete a custom field by field ID
    public function deleteMeta($field_id) {
        global $wpdb;
        return $wpdb->delete(
            $this->contactmeta,
            array('id' => $field_id)
        );
    }
}