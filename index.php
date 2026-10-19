<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login - Pengarsipan Surat</title>
    <!-- Menggunakan Font Awesome untuk Ikon gratis (Opsional jika memakai ikon SVG/Gambar) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #e5e5e5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .title {
            margin-bottom: 25px;
            color: #222;
            font-size: 24px;
            text-align: center;
            letter-spacing: 1px;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Container untuk Logo / Ikon */
        .icon-container {
            width: 80px;
            height: 80px;
            background-color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        /* Jika Menggunakan Ikon FontAwesome */
        .icon-container i {
            font-size: 36px;
            color: #222222;
        }

        /* Jika Menggunakan Gambar Logo (Pakai tag <img>) */
        .icon-container img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .form-group {
            width: 100%;
            margin-bottom: 18px;
        }

        .input-field {
            width: 100%;
            height: 55px;
            padding: 0 25px;
            border: none;
            border-radius: 30px;
            background-color: #ffffff;
            font-size: 16px;
            outline: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .input-field::placeholder {
            color: #888;
        }

        .input-field:focus {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-submit {
            width: 100%;
            height: 55px;
            border: none;
            border-radius: 30px;
            background-color: #222222;
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #000000;
        }

        .alert {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            background-color: #ffdddd;
            color: #d8000c;
            border-radius: 15px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <h1 class="title"><b>PENGARSIPAN</b> SURAT</h1>

    <div class="login-card">
        
        <!-- OPSI 1: Menggunakan Ikon User (FontAwesome) -->
        <div class="icon-container">
            <i class="fa-solid fa-user"></i>
        </div>

        <!-- OPSI 2: Menggunakan Gambar Logo (Aktifkan baris di bawah jika ada file logo) -->
        <!-- 
        <div class="icon-container">
            <img src="logo.png" alt="Logo Instansi">
        </div> 
        -->

        <?php
        if(isset($_GET['pesan'])){
            if($_GET['pesan']=="gagal"){
                echo "<div class='alert'>Username dan Password tidak sesuai!</div>";
            }    
        }
        ?>

        <form action="login_validasi.php" method="post" style="width: 100%;">
            <div class="form-group">
                <input name="username" type="text" placeholder="Username" class="input-field" required>
            </div>
            
            <div class="form-group">
                <input name="password" type="password" placeholder="Password" class="input-field" required>
            </div>

            <input name="login" type="submit" value="Login" class="btn-submit">
        </form>
    </div>

</body>
</html>