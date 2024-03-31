<?php
// config.php

$host = "localhost"; // Host database
$username = "root"; // Username database
$password = ""; // Password database
$database = "sembako-v1"; // Nama database

// Membuat koneksi ke database
$koneksi = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
