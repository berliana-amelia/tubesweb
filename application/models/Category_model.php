<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Category_model extends CI_Model {
    
    public function create_cat($cat) {
        // Menambahkan kategori baru ke dalam tabel "res_category"
        $this->db->insert('res_category', $cat);
    }

    public function getCategory() {
        // Mendapatkan semua kategori dari tabel "res_category"
        $cats_result = $this->db->get('res_category')->result_array();
        return $cats_result;
    }

    public function getCat($id) {
        // Mendapatkan kategori berdasarkan ID dari tabel "res_category"
        $this->db->where('c_id', $id);
        $cat = $this->db->get('res_category')->row_array();
        return $cat;
    }

    public function countCategory() {
        // Menghitung jumlah kategori yang ada dalam tabel "res_category"
        $query = $this->db->get('res_category');
        return $query->num_rows();
    }

    public function update($id, $cat) {
        // Memperbarui kategori berdasarkan ID dalam tabel "res_category"
        $this->db->where('c_id', $id);
        $this->db->update('res_category', $cat);
    }

    public function delete($id) {
        // Menghapus kategori berdasarkan ID dari tabel "res_category"
        $this->db->where('c_id', $id);
        $this->db->delete('res_category');
    }

}


