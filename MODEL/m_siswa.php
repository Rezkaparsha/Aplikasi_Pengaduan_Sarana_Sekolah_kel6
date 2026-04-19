<?php
require_once 'm_koneksi.php';

class Siswa {
    private $koneksi;

    // Properties
    public $nis;
    public $nama;
    public $kelas;
    public $email;
    public $password;

    // constructor
    public function __construct() {
        $db = new Koneksi();
        $this->koneksi = $db->koneksi;
    }

    // GET ALL SISWA
    public function getAllSiswa() {
        $query = "SELECT * FROM siswa ORDER BY nama ASC";
        $result = mysqli_query($this->koneksi, $query);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    // GET BY NIS
    public function getSiswaByNis($nis) {
        $query = "SELECT * FROM siswa WHERE nis = '$nis'";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_assoc($result);
    }

    // LOGIN
    public function login($nis, $password) {
        $query = "SELECT * FROM siswa WHERE nis = '$nis'";
        $result = mysqli_query($this->koneksi, $query);
        $siswa = mysqli_fetch_assoc($result);

        if ($siswa && password_verify($password, $siswa['password'])) {
            return $siswa;
        }
        return false;
    }

    // TAMBAH SISWA
    public function tambahSiswa($data) {
        $nis = $data['nis'];
        $nama = $data['nama'];
        $kelas = $data['kelas'];
        $email = $data['email'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $query = "INSERT INTO siswa (nis, nama, kelas, email, password)
                  VALUES ('$nis', '$nama', '$kelas', '$email', '$password')";

        return mysqli_query($this->koneksi, $query);
    }

    // UPDATE SISWA
    public function updateSiswa($data) {
        $nis = $data['nis'];
        $nama = $data['nama'];
        $kelas = $data['kelas'];
        $email = $data['email'];

        if (!empty($data['password'])) {
            $password = password_hash($data['password'], PASSWORD_DEFAULT);

            $query = "UPDATE siswa SET 
                        nama = '$nama',
                        kelas = '$kelas',
                        email = '$email',
                        password = '$password'
                      WHERE nis = '$nis'";
        } else {
            $query = "UPDATE siswa SET 
                        nama = '$nama',
                        kelas = '$kelas',
                        email = '$email'
                      WHERE nis = '$nis'";
        }

        return mysqli_query($this->koneksi, $query);
    }

    // HAPUS SISWA
    public function hapusSiswa($nis) {
        $query = "DELETE FROM siswa WHERE nis = '$nis'";
        return mysqli_query($this->koneksi, $query);
    }

    // CEK NIS
    public function cekNis($nis) {
        $query = "SELECT * FROM siswa WHERE nis = '$nis'";
        $result = mysqli_query($this->koneksi, $query);

        return mysqli_num_rows($result) > 0;
    }

    // UPDATE PROFIL
    public function updateProfil($nis, $data) {
        $nama = $data['nama'];
        $kelas = $data['kelas'];
        $email = $data['email'];

        $query = "UPDATE siswa SET 
                    nama = '$nama',
                    kelas = '$kelas',
                    email = '$email'
                  WHERE nis = '$nis'";

        return mysqli_query($this->koneksi, $query);
    }

    // UPDATE PASSWORD
    public function updatePassword($nis, $passwordBaru) {
        $password = password_hash($passwordBaru, PASSWORD_DEFAULT);

        $query = "UPDATE siswa SET 
                    password = '$password'
                  WHERE nis = '$nis'";

        return mysqli_query($this->koneksi, $query);
    }
}
?>