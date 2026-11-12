<?php
require_once '../includes/config.php';
require_once '../includes/fonctions.php';

if (isAdmin()) {
    header('Location: index.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];
    
    if (!empty($email) && !empty($mot_de_passe)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_nom'] = $user['nom'];
            $_SESSION['admin_role'] = $user['role'];
            header('Location: index.php');
            exit;
        } else {
            $erreur = 'Email ou mot de passe incorrect';
        }
    } else {
        $erreur = 'Veuillez remplir tous les champs';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Umma Rahma</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: linear-gradient(135deg, #1a4d2e 0%, #2d5f3b 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login-box { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); max-width: 400px; width: 100%; }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h1 { color: #1a4d2e; font-size: 1.8rem; margin-bottom: 5px; }
        .login-header p { color: #666; font-size: 0.9rem; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #333; font-weight: 500; }
        .form-group input { width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; }
        .form-group input:focus { outline: none; border-color: #2d5f3b; }
        .btn-login { width: 100%; padding: 12px; background: #1a4d2e; color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        .btn-login:hover { background: #2d5f3b; }
        .erreur { background: #fee; color: #c33; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #666; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <h1><i class="fas fa-shield-alt"></i> Espace Administrateur</h1>
            <p>Umma Rahma</p>
        </div>
        
        <?php if($erreur): ?>
            <div class="erreur"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mot_de_passe"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
        </form>
        
        <div class="back-link">
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Retour au site</a>
        </div>
    </div>
</body>
</html>
