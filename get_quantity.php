<?php
include('koneksi/config.php');

if (isset($_POST['selectedItem'])) {
    $selectedItem = $_POST['selectedItem'];

    // Query to retrieve information based on the selected item
    $query = "SELECT * FROM item WHERE nama_item = '$selectedItem'";
    
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Return data as JSON
        echo json_encode($row);
    } else {
        echo 'Error: ' . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
?>
