<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class User_model extends CI_Model {
    
    public function create($formArray) {
        // Menyisipkan data pengguna baru ke dalam tabel "users"
        $this->db->insert('users', $formArray);
    }

    public function getByUsername($username) {
        // Mendapatkan data pengguna berdasarkan nama pengguna dari tabel "users"
        $this->db->where('username', $username);
        $mainuser = $this->db->get('users')->row_array();
        return $mainuser;
    }

    public function getUsers() {
        // Mendapatkan semua data pengguna dari tabel "users"
        $result = $this->db->get('users')->result_array();
        return $result;
    }

    public function getUser($id) {
        // Mendapatkan data pengguna berdasarkan ID dari tabel "users"
        $this->db->where('u_id', $id);
        $user = $this->db->get('users')->row_array();
        return $user;
    }

    public function update($id, $formArray) {
        // Memperbarui data pengguna berdasarkan ID dalam tabel "users"
        $this->db->where('u_id', $id);
        $this->db->update('users', $formArray);
    }

    public function delete($id) {
        // Menghapus data pengguna berdasarkan ID dari tabel "users"
        $this->db->where('u_id', $id);
        $this->db->delete('users');
    }

    public function countUser() {
        // Menghitung jumlah semua pengguna dalam tabel "users"
        $query = $this->db->get('users');
        return $query->num_rows();
    }

}
