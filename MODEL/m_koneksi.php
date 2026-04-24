<?php

class Koneksi
{
    // konfigurasi database
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "pengaduan_sarana_sekolah_kel6_11rpl2";

    public $koneksi;

    // constructor
    function __construct()
    {
        // membuat koneksi
        $this->koneksi = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->db
        );

        // cek koneksi
        if (!$this->koneksi) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
    }
}
?>