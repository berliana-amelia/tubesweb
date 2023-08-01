<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Order_model extends CI_Model {

    public function getOrders() {
        // Mendapatkan semua pesanan dari tabel "user_orders" dan diurutkan berdasarkan ID secara menurun
        $this->db->order_by('o_id','DESC');
        $result = $this->db->get('user_orders')->result_array();
        return $result;
    }

    public function getOrder($id) {
        // Mendapatkan data pesanan berdasarkan ID dari tabel "user_orders"
        $this->db->where('o_id', $id);
        $result = $this->db->get('user_orders')->row_array();
        return $result;
    }

    public function getUserOrder($id) {
        // Mendapatkan pesanan dari seorang pengguna berdasarkan ID pengguna dari tabel "user_orders" dan diurutkan berdasarkan ID secara menurun
        $this->db->where('u_id', $id);
        $this->db->order_by('o_id','DESC');
        $result = $this->db->get('user_orders')->result_array();
        return $result;
    }

    public function update($id, $status) {
        // Memperbarui status pesanan berdasarkan ID dalam tabel "user_orders"
        $this->db->where('o_id', $id);
        $this->db->update('user_orders', $status);
    }

    public function deleteOrder($id) {
        // Menghapus pesanan berdasarkan ID dari tabel "user_orders"
        $this->db->where('o_id', $id);
        $this->db->delete('user_orders');
    }

    public function insertOrder($orderData) {
        // Menyisipkan data pesanan baru ke dalam tabel "user_orders" dengan batch
        $this->db->insert_batch('user_orders', $orderData);
        return $this->db->insert_id();
    }

    public function countOrders() {
        // Menghitung jumlah semua pesanan dalam tabel "user_orders"
        $query = $this->db->get('user_orders');
        return $query->num_rows();
    }

    public function countPendingOrders() {
        // Menghitung jumlah pesanan yang statusnya belum diproses (NULL) dalam tabel "user_orders"
        $this->db->where('status', NULL);
        $query = $this->db->get('user_orders');
        return $query->num_rows();
    }

    public function countDeliveredOrders() {
        // Menghitung jumlah pesanan yang statusnya "closed" dalam tabel "user_orders"
        $this->db->where('status','closed');
        $query = $this->db->get('user_orders');
        return $query->num_rows();
    }

    public function countRejectedOrders() {
        // Menghitung jumlah pesanan yang statusnya "rejected" dalam tabel "user_orders"
        $this->db->where('status','rejected');
        $query = $this->db->get('user_orders');
        return $query->num_rows();
    }

    public function getAllOrders() {
        // Mendapatkan semua pesanan dengan informasi yang lebih lengkap dari tabel "user_orders" dan diurutkan berdasarkan ID secara menurun
        $this->db->order_by('o_id','DESC');
        $this->db->select('o_id, d_name, quantity, price, status, date, username, address');
        $this->db->from('user_orders');
        $this->db->join('users', 'users.u_id = user_orders.u_id');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getOrderByUser($id) {
        // Mendapatkan data pesanan berdasarkan ID pesanan dan informasi pengguna terkait dari tabel "user_orders" dan "users"
        $this->db->select('o_id, r_id, d_id, users.u_id, d_name, quantity, price, status, f_name, l_name, user_orders.date, users.email, users.phone,  success-date, username, address');
        $this->db->from('user_orders');
        $this->db->join('users', 'users.u_id = user_orders.u_id');
        $this->db->where('o_id', $id);
        $result = $this->db->get()->row_array();
        return $result;
    }
}
