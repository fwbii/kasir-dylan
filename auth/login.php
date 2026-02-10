<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir DYLAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            /* Gradient Background modern */
            background: linear-gradient(135deg, #a30606 0%, #a80d0d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px); /* Efek kaca halus */
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        .brand-logo {
            width: 70px;
            height: 70px;
            background: #fd0d0d;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.8rem;
            box-shadow: 0 5px 15px rgba(253, 13, 13, 0.4);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(253, 13, 13, 0.1);
            border-color: #a40606;
        }

        .btn-login {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
            background: #8b1717;
            border: none;
        }

        .btn-login:hover {
            background: #b30000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background-color: #f8f9fa;
            border-right: none;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="login-card">
        <div class="brand-logo">
            <i class="fas fa-store"></i>
        </div>

        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark">KASIR DYLAN</h3>
            <p class="text-muted">Manajemen Toko Jadi Lebih Mudah</p>
        </div>

        <?php if(isset($_GET['pesan']) && $_GET['pesan'] == "gagal"): ?>
            <div class="alert alert-danger border-0 text-center py-2 mb-4" role="alert" style="border-radius: 10px; font-size: 14px;">
                <i class="fas fa-exclamation-circle me-2"></i> Username / Password Salah!
            </div>
        <?php endif; ?>

        <form action="auth.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="username" required autocomplete="off">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="off">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                MASUK SEKARANG
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-muted mb-0" style="font-size: 11px; letter-spacing: 1px;">
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>