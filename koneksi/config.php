<?php
// config.php

$host = "localhost"; // Host database
$username = "123"; // Username database
$password = "123"; // Password database
$database = "toko"; // Nama database

// Membuat koneksi ke database
$koneksi = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
