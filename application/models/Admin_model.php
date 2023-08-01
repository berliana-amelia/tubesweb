<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Admin_model extends CI_Model {
    
    public function getByUsername($username) {

        // Mendapatkan data admin berdasarkan username
        $this->db->where('username', $username);
        $admin = $this->db->get('admin')->row_array();
        return $admin;
    }
    
    public function getAllOrders() {
        // Mendapatkan semua pesanan dengan beberapa kolom yang dipilih, termasuk informasi pelanggan yang terkait
        $this->db->order_by('o_id','DESC');
        $this->db->select('o_id, d_name, quantity, price, status, date, username, address');
        $this->db->from('user_orders');
        $this->db->join('users', 'users.u_id = user_orders.u_id');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getResReport() {
        // Mendapatkan laporan tentang total pendapatan dari masing-masing toko (restaurant)
        $this->db->group_by('u.r_id');
        $this->db->select('u.r_id, name, price, success-date');
        $this->db->select_sum('price');
        $this->db->from('user_orders as u');
        $this->db->join('restaurants as r', 'r.r_id = u.r_id');
        $result = $this->db->get()->result();
        return $result;
    }

    public function dishReport() {
        // Mendapatkan laporan tentang makanan dengan jumlah pemesanan tertinggi (jumlah terbanyak)
        $query = $this->db->query('SELECT d_id, d_name, 
        SUM(quantity) AS qty
        FROM user_orders
        GROUP BY d_id
        ORDER BY SUM(quantity) DESC');
        return $query->result();
    }

    public function mostOrderdDishes() {
        // Mendapatkan makanan yang paling sering dipesan beserta informasi lainnya untuk setiap toko
        $sql = 'SELECT u.r_id, r.name, u.price, u.d_name, 
        MAX(u.quantity) AS quantity, 
        SUM(price) AS total
        FROM user_orders AS u
        INNER JOIN restaurants as r
        ON u.r_id = r.r_id
        GROUP BY u.r_id';

        $query = $this->db->query($sql);
        return $query->result();
    }
}
