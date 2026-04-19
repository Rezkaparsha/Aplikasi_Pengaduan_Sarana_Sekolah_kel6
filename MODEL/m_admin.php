<?php
require_once 'm_koneksi.php';

class Admin {
    private $koneksi;

    // Properties
    public $id_admin;
    public $username;
    public $password;
    public $nama_lengkap;
    public $email;

    // constructor
    public function __construct() {
        $db = new Koneksi();
        $this->koneksi = $db->koneksi;
    }

    // LOGIN
    public function login($username, $password) {
        $query = "SELECT * FROM admin WHERE username = '$username'";
        $result = mysqli_query($this->koneksi, $query);
        $admin = mysqli_fetch_assoc($result);

        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }

    // GET ALL ADMIN
    public function getAllAdmin() {
        $query = "SELECT id_admin, username, nama_lengkap, email, created_at FROM admin ORDER BY id_admin ASC";
        $result = mysqli_query($this->koneksi, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // GET ADMIN BY ID
    public function getAdminById($id) {
        $query = "SELECT id_admin, username, nama_lengkap, email FROM admin WHERE id_admin = '$id'";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_assoc($result);
    }

    // TAMBAH ADMIN
    public function tambahAdmin($data) {
        $username = $data['username'];
        $nama = $data['nama_lengkap'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO admin (username, password, nama_lengkap, email)
                  VALUES ('$username', '$password', '$nama', '$email')";

        return mysqli_query($this->koneksi, $query);
    }

    // UPDATE ADMIN
    public function updateAdmin($data) {
        $id = $data['id_admin'];
        $username = $data['username'];
        $nama = $data['nama_lengkap'];
        $email = $data['email'];

        if (!empty($data['password'])) {
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $query = "UPDATE admin SET 
                        username = '$username',
                        nama_lengkap = '$nama',
                        email = '$email',
                        password = '$password'
                      WHERE id_admin = '$id'";
        } else {
            $query = "UPDATE admin SET 
                        username = '$username',
                        nama_lengkap = '$nama',
                        email = '$email'
                      WHERE id_admin = '$id'";
        }

        return mysqli_query($this->koneksi, $query);
    }

    // HAPUS ADMIN
    public function hapusAdmin($id) {
        $query = "DELETE FROM admin WHERE id_admin = '$id'";
        return mysqli_query($this->koneksi, $query);
    }

    // CEK USERNAME
    public function cekUsername($username) {
        $query = "SELECT * FROM admin WHERE username = '$username'";
        $result = mysqli_query($this->koneksi, $query);

        return mysqli_num_rows($result) > 0;
    }
}
?>