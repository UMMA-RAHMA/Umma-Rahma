<?php
require_once '../includes/config.php';
require_once '../includes/fonctions.php';
requireAdmin();

$evenement_id = $_GET['evenement_id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM evenements WHERE id = ?");
$stmt->execute([$evenement_id]);
$evenement = $stmt->fetch();

if (!$evenement) {
    header('Location: evenements.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
        $type = in_array($ext, ['mp4', 'webm']) ? 'video' : 'image';
        
        if ($type === 'image' && in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $nom_fichier = 'img_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], '../uploads/' . $nom_fichier)) {
                $stmt = $pdo->prepare("INSERT INTO medias (evenement_id, fichier, type) VALUES (?, ?, ?)");
                $stmt->execute([$evenement_id, $nom_fichier, $type]);
                $message = '<div style="background:#d4edda;color:#155724;padding:10px 20px;border-radius:8px;margin-bottom:20px;">Fichier ajouté avec succès !</div>';
            }
        } elseif ($type === 'video' && $ext === 'mp4') {
            $nom_fichier = 'vid_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], '../uploads/' . $nom_fichier)) {
                $stmt = $pdo->prepare("INSERT INTO medias (evenement_id, fichier, type) VALUES (?, ?, ?)");
                $stmt->execute([$evenement_id, $nom_fichier, $type]);
                $message = '<div style="background:#d4edda;color:#155724;padding:10px 20px;border-radius:8px;margin-bottom:20px;">Vidéo ajoutée avec succès !</div>';
            }
        }
    }
}

if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM medias WHERE id = ?");
    $stmt->execute([$_GET['supprimer']]);
    header('Location: medias.php?evenement_id=' . $evenement_id);
    exit;
}

$medias = getMediasEvenement($pdo, $evenement_id);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des médias - Umma Rahma</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #f5f7fa; }
        .header { background: linear-gradient(135deg, #1a4d2e 0%, #2d5f3b 100%); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .nav-menu { display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap; }
        .nav-menu a { padding: 10px 20px; background: white; color: #333; text-decoration: none; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 8px; }
        .nav-menu a:hover, .nav-menu a.active { background: #1a4d2e; color: white; }
        .card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .card-header { padding: 20px 30px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
        .card-header h2 { color: #1a4d2e; font-size: 1.3rem; }
        .btn { padding: 10px 20px; background: #1a4d2e; color: white; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; border: none; cursor: pointer; }
        .btn:hover { background: #2d5f3b; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 30px; }
        .media-item { position: relative; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .media-item img, .media-item video { width: 100%; height: 200px; object-fit: cover; }
        .media-delete { position: absolute; top: 10px; right: 10px; background: rgba(220,53,69,0.9); color: white; border: none; border-radius: 50%; width: 35px; height: 35px; cursor: pointer; }
        .form-group { margin-bottom: 20px; padding: 0 30px; }
        .form-group label { display: block; margin-bottom: 8px; color: #333; font-weight: 500; }
        .form-group input { width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-images"></i> Médias - <?= htmlspecialchars($evenement['titre']) ?></h1>
        <div style="display: flex; gap: 20px; align-items: center;">
            <a href="evenements.php" style="color: white; text-decoration: none;"><i class="fas fa-arrow-left"></i> Retour aux événements</a>
            <a href="logout.php" style="color: white; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <div class="nav-menu">
            <a href="index.php"><i class="fas fa-home"></i> Accueil</a>
            <a href="evenements.php" class="active"><i class="fas fa-calendar-alt"></i> Événements</a>
            <a href="actualites.php"><i class="fas fa-newspaper"></i> Actualités</a>
            <a href="medias.php"><i class="fas fa-images"></i> Médias</a>
            <a href="utilisateurs.php"><i class="fas fa-users"></i> Utilisateurs</a>
            <a href="parametres.php"><i class="fas fa-cog"></i> Paramètres</a>
        </div>
        
        <?= $message ?>
        
        <div class="card">
            <div class="card-header">
                <h2>Ajouter des photos/vidéos</h2>
            </div>
            <form method="POST" enctype="multipart/form-data" style="padding: 30px 0;">
                <div class="form-group">
                    <label>Fichier (JPG, PNG, WebP, GIF, MP4)</label>
                    <input type="file" name="fichier" accept="image/*,video/mp4" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn"><i class="fas fa-upload"></i> Uploader</button>
                </div>
            </form>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2>Galerie (<?= count($medias) ?> fichier(s))</h2>
            </div>
            <?php if(empty($medias)): ?>
                <p style="padding: 30px; text-align: center; color: #666;">Aucun média pour cet événement</p>
            <?php else: ?>
            <div class="grid">
                <?php foreach($medias as $media): ?>
                <div class="media-item">
                    <?php if($media['type'] == 'image'): ?>
                        <img src="../uploads/<?= htmlspecialchars($media['fichier']) ?>" alt="">
                    <?php else: ?>
                        <video src="../uploads/<?= htmlspecialchars($media['fichier']) ?>" controls></video>
                    <?php endif; ?>
                    <a href="medias.php?evenement_id=<?= $evenement_id ?>&supprimer=<?= $media['id'] ?>" onclick="return confirm('Supprimer ce fichier ?')" class="media-delete"><i class="fas fa-trash"></i></a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
