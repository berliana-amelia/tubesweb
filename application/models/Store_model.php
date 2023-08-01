<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Store_model extends CI_Model {
    
    public function create($formArray) {
        // Menyisipkan data toko/restoran baru ke dalam tabel "restaurants"
        $this->db->insert('restaurants', $formArray);
    }

    public function getStores() {
        // Mendapatkan semua data toko/restoran dari tabel "restaurants"
        $result = $this->db->get('restaurants')->result_array();
        return $result;
    }

    public function getStore($id) {
        // Mendapatkan data toko/restoran berdasarkan ID dari tabel "restaurants"
        $this->db->where('r_id', $id);
        $store = $this->db->get('restaurants')->row_array();
        return $store;
    }

    public function update($id, $formArray) {
        // Memperbarui data toko/restoran berdasarkan ID dalam tabel "restaurants"
        $this->db->where('r_id', $id);
        $this->db->update('restaurants', $formArray);
    } 

    public function delete($id) {
        // Menghapus data toko/restoran berdasarkan ID dari tabel "restaurants"
        $this->db->where('r_id',$id);
        $this->db->delete('restaurants');
    }

    public function countStore() {
        // Menghitung jumlah semua toko/restoran dalam tabel "restaurants"
        $query = $this->db->get('restaurants');
        return $query->num_rows();
    }

    public function getResInfo() {
        // Mendapatkan informasi lebih lengkap tentang toko/restoran dari tabel "restaurants" dan "res_category" dengan melakukan join
        $this->db->select('*');
        $this->db->from('restaurants');
        $this->db->join('res_category','restaurants.c_id = res_category.c_id');
        $result = $this->db->get()->result_array();
        return $result;
    }

}
