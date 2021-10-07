<?php

namespace App\Models;

class Vault_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'vault';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $vault_table = $this->db->prefixTable('vault');
        $where = "";
        $Client_id = get_array_value($options, "client_id");
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $vault_table.id=$id";
        } elseif ($Client_id) {
            $where .= " AND $vault_table.client_id=$Client_id";
        }

        $sql = "SELECT $vault_table.*
        FROM $vault_table
        WHERE $vault_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
