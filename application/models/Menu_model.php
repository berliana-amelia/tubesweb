<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Menu_model extends CI_Model {
    
    public function create($formArray) {
        // Menambahkan data menu baru ke dalam tabel "dishesh"
        $this->db->insert('dishesh', $formArray);
    }

    public function getMenu() {
        // Mendapatkan semua menu dari tabel "dishesh"
        $result = $this->db->get('dishesh')->result_array();
        return $result;
    }

    public function getSingleDish($id) {
        // Mendapatkan data menu berdasarkan ID dari tabel "dishesh"
        $this->db->where('d_id', $id);
        $dish = $this->db->get('dishesh')->row_array();
        return $dish;
    }

    public function update($id, $formArray) {
        // Memperbarui data menu berdasarkan ID dalam tabel "dishesh"
        $this->db->where('d_id', $id);
        $this->db->update('dishesh', $formArray);
    } 

    public function delete($id) {
        // Menghapus data menu berdasarkan ID dari tabel "dishesh"
        $this->db->where('d_id',$id);
        $this->db->delete('dishesh');
    }

    public function countDish() {
        // Menghitung jumlah menu yang ada dalam tabel "dishesh"
        $query = $this->db->get('dishesh');
        return $query->num_rows();
    }

    public function getDishesh($id) {
        // Mendapatkan data menu berdasarkan ID restoran dari tabel "dishesh"
        $this->db->where('r_id', $id);
        $dish = $this->db->get('dishesh')->result_array();
        return $dish;
    }
}
