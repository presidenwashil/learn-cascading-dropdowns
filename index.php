<?php
// Konfigurasi database
$host = "localhost"; // Ganti dengan nama host MySQL kamu
$username = "root"; // Ganti dengan username MySQL kamu
$password = ""; // Ganti dengan password MySQL kamu
$database = "multi_aslab"; // Ganti dengan nama database kamu

// Buat koneksi ke MySQL
$con = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Fungsi untuk menambahkan data absensi ke database
function tambahAbsensi($kode_aslab, $keterangan)
{
    global $con;
    $kode_aslab = mysqli_real_escape_string($con, $kode_aslab);
    $keterangan = mysqli_real_escape_string($con, $keterangan);

    $sql = "INSERT INTO absensi (kode_aslab, keterangan) VALUES ('$kode_aslab', '$keterangan')";

    if (mysqli_query($con, $sql)) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_aslabArray = $_POST['kode_aslab'];
    $combinedKodeAslab = implode(", ", $kode_aslabArray);

    if (!empty($combinedKodeAslab) && tambahAbsensi($combinedKodeAslab, $_POST['keterangan'])) {
        echo "Data absensi berhasil disimpan!";
    } else {
        echo "Gagal menyimpan data absensi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin: 0;
        }

        form {
            background-color: #fff;
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .select-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            border: 1px solid #ccc;
            padding: 10px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            display: none;
            /* Awalnya sembunyikan popup */
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: none;
            /* Awalnya sembunyikan overlay */
        }

        .popup-content {
            text-align: center;
            margin-bottom: 20px;
        }

        .close-popup {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Form Absensi</h1>
    <form method="post">
        <div id="select-container">
            <div>
                <label for="kode_aslab1">Pilih Kode Aslab 1:</label>
                <select id="kode_aslab1" name="kode_aslab[]" required>
                    <option value="" disabled selected>Pilih kode aslab</option>
                    <option value="RN">RN</option>
                    <option value="RZ">RZ</option>
                    <option value="ZD">ZD</option>
                </select>
            </div>
        </div>
        <div>
            <label for="keterangan">Keterangan:</label>
            <input type="text" id="keterangan" name="keterangan" required>
        </div>
        <div>
            <button type="submit" id="submitButton">Simpan Absensi</button>
        </div>
    </form>

    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <div class="popup-content">Data absensi berhasil disimpan!</div>
        <button class="close-popup" id="closePopup">Tutup</button>
    </div>

    <script>
        // Mendapatkan elemen-elemen HTML yang diperlukan
        const selectContainer = document.getElementById("select-container");
        const overlay = document.getElementById("overlay");
        const popup = document.getElementById("popup");
        const closePopup = document.getElementById("closePopup");

        // Daftar pilihan kode aslab yang dapat ditampilkan
        const kodeAslabOptions = [
            "RN",
            "RZ",
            "ZD",
            // Tambahkan pilihan kode aslab lainnya sesuai kebutuhan
        ];

        // Event listener untuk menambahkan input select baru saat kode aslab pertama dipilih
        selectContainer.addEventListener("change", function (event) {
            // Hanya tambahkan input select baru jika perubahan terjadi pada input select terakhir
            if (event.target.tagName === "SELECT" && event.target === selectContainer.lastElementChild.querySelector("select")) {
                tambahInputSelect();
            }
        });

        // Event listener untuk menampilkan popup setelah mengirim formulir
        document.getElementById("submitButton").addEventListener("click", function (event) {
            event.preventDefault(); // Menghentikan pengiriman formulir standar

            // Simulasi pengiriman data formulir (ganti dengan metode yang sesuai)
            // ...

            // Setelah pengiriman data berhasil atau gagal, tampilkan popup
            tampilkanPopup();
        });

        // Event listener untuk menutup popup
        closePopup.addEventListener("click", function () {
            tutupPopup();
        });

        // Fungsi untuk menampilkan popup
        function tampilkanPopup() {
            overlay.style.display = "block"; // Tampilkan overlay
            popup.style.display = "block"; // Tampilkan popup
        }

        // Fungsi untuk menutup popup
        function tutupPopup() {
            overlay.style.display = "none"; // Sembunyikan overlay
            popup.style.display = "none"; // Sembunyikan popup
        }

        // Fungsi untuk menambahkan input select baru
        function tambahInputSelect() {
            const div = document.createElement("div");
            div.classList.add("select-group"); // Tambahkan class "select-group" ke div baru
            const label = document.createElement("label");
            const select = document.createElement("select");
            select.name = "kode_aslab[]";

            // Tambahkan opsi pertama dengan nilai kosong (kosong yang terpilih)
            const emptyOption = document.createElement("option");
            emptyOption.value = "";
            emptyOption.textContent = "Pilih kode aslab";
            emptyOption.selected = true; // Buat yang pertama terpilih
            emptyOption.disabled = true; // Jadikan yang pertama disabled
            select.appendChild(emptyOption);

            kodeAslabOptions.forEach((option) => {
                const optionElement = document.createElement("option");
                optionElement.value = option;
                optionElement.textContent = option;
                select.appendChild(optionElement);
            });

            // Tambahkan label hanya jika bukan input select pertama
            if (selectContainer.childElementCount > 0) {
                label.textContent = "Pilih Kode Aslab " + (selectContainer.childElementCount + 1) + ":";
            }

            div.appendChild(label);
            div.appendChild(select);
            selectContainer.appendChild(div);
        }
    </script>
</body>

</html>