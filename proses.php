<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap  = $_POST['nama_lengkap'];
    $nim           = $_POST['nim'];
    $program_studi = $_POST['program_studi'];
    $no_hp         = $_POST['no_hp'];

    // Menangani file gambar yang diunggah
    $nama_file     = $_FILES['bukti_instagram']['name'];
    $ukuran_file   = $_FILES['bukti_instagram']['size'];
    $error         = $_FILES['bukti_instagram']['error'];
    $tmp_name      = $_FILES['bukti_instagram']['tmp_name'];

    // Validasi apakah ada file yang diunggah
    if ($error === 0) {
        $ekstensi_valid = ['jpg', 'jpeg'];
        $ekstensi_file  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (in_array($ekstensi_file, $ekstensi_valid)) {
            // Beri nama unik pada file agar tidak bentrok
            $nama_baru = 'bukti_' . $nim . '.' . $ekstensi_file;
            $tujuan   = 'uploads/' . $nama_baru;

            // Pindahkan file ke folder uploads
            if (move_uploaded_file($tmp_name, $tujuan)) {
                // Simpan data ke database MySQL
                $query = "INSERT INTO pendaftar (nama_lengkap, nim, prodi, no_hp, bukti_foto) 
                          VALUES ('$nama_lengkap', '$nim', '$prodi', '$no_hp', '$nama_baru')";
                
                if (mysqli_query($koneksi, $query)) {
                    echo "<script>alert('Pendaftaran berhasil! Terima kasih.'); window.location='index.html';</script>";
                } else {
                    echo "<script>alert('Gagal menyimpan ke database: " . mysqli_error($koneksi) . "'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Gagal mengunggah file gambar.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Format file harus .jpg atau .jpeg!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Pilih file bukti terlebih dahulu!'); window.history.back();</script>";
    }
}
?>