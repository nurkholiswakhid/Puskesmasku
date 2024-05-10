<?php
// Import koneksi database
include("../connection.php");

// Pastikan request POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah appoid ada dalam request POST
    if (isset($_POST["appoid"])) {
        // Dapatkan appoid dari POST data
        $appoid = $_POST["appoid"];

        // Update status menjadi "selesai" di database
        $updateQuery = "UPDATE appointment SET status = 'selesai' WHERE appoid = $appoid";

        // Eksekusi query
        if ($database->query($updateQuery) === TRUE) {
            // Jika berhasil diupdate, arahkan kembali ke halaman appointment.php atau halaman lain yang sesuai
            header("Location: appointment.php");
            exit();
        } else {
            // Jika terjadi kesalahan saat eksekusi query, tampilkan pesan kesalahan
            echo "Error updating record: " . $database->error;
        }
    } else {
        // Jika appoid tidak ada dalam request POST, tampilkan pesan kesalahan
        echo "Appoid is missing in the POST request.";
    }
} else {
    // Jika bukan request POST, tampilkan pesan kesalahan
    echo "Invalid request method. This script only accepts POST requests.";
}
?>
