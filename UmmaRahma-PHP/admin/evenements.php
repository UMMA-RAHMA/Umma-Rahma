<?php
require_once '../includes/config.php';
require_once '../includes/fonctions.php';
requireAdmin();

$action = $_GET['action'] ?? 'liste';
$message = '';

// Mise à jour automatique du statut
$pdo->query("UPDATE evenements SET statut = 'passe' WHERE date_evenement < CURDATE() AND statut = 'a_venir'");
$pdo->query("UPDATE evenements SET statut = 'a_venir' WHERE date_evenement >= CURDATE() AND statut = 'passe'");

if ($action === 'ajouter' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $date_evenement = $_POST['date_evenement'];
    $lieu = trim($_POST['lieu']);
    $description = trim($_POST['description']);
    $slug = slugify($titre);
    
    $image_couverture = null;
    if (isset($_FILES['image_couverture']) && $_FILES['image_couverture']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['image_couverture']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $nom_fichier = 'couverture_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['image_couverture']['tmp_name'], '../uploads/' . $nom_fichier)) {
                $image_couverture = $nom_fichier;
            }
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO evenements (titre, slug, date_evenement, lieu, description, image_couverture) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $slug, $date_evenement, $lieu, $description, $image_couverture]);
    $message = '<div style="background:#d4edda;color:#155724;padding:10px 20px;border-radius:8px;margin-bottom:20px;">Événement ajouté avec succès !</div>';
    $action = 'liste';
}

if ($action === 'supprimer' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM evenements WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header('Location: evenements.php');
    exit;
}

$evenements = $pdo->query("SELECT * FROM evenements ORDER BY date_evenement DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des événements - Umma Rahma</title>
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
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: #f8f9fa; padding: 12px 30px; text-align: left; font-weight: 600; color: #333; font-size: 0.9rem; }
        .table td { padding: 12px 30px; border-top: 1px solid #f0f0f0; color: #666; }
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; }
        .badge.avenir { background: #d4edda; color: #155724; }
        .badge.passe { background: #f8d7da; color: #721c24; }
        .form-group { margin-bottom: 20px; padding: 0 30px; }
        .form-group label { display: block; margin-bottom: 8px; color: #333; font-weight: 500; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; }
        .form-group textarea { resize: vertical; min-height: 100px; }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-calendar-alt"></i> Gestion des événements</h1>
        <div style="display: flex; gap: 20px; align-items: center;">
            <a href="index.php" style="color: white; text-decoration: none;"><i class="fas fa-arrow-left"></i> Retour au dashboard</a>
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
        
        <?php if($action === 'liste'): ?>
        <div class="card">
            <div class="card-header">
                <h2>Liste des événements</h2>
                <a href="evenements.php?action=ajouter" class="btn"><i class="fas fa-plus"></i> Ajouter un événement</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($evenements as $evt): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($evt['titre']) ?></strong></td>
                        <td><?= date('d/m/Y', strtotime($evt['date_evenement'])) ?></td>
                        <td><?= htmlspecialchars($evt['lieu']) ?></td>
                        <td><span class="badge <?= $evt['date_evenement'] >= date('Y-m-d') ? 'avenir' : 'passe' ?>"><?= $evt['date_evenement'] >= date('Y-m-d') ? 'À venir' : 'Passé' ?></span></td>
                        <td>
                            <a href="medias.php?evenement_id=<?= $evt['id'] ?>" style="color: #17a2b8; margin-right: 10px;" title="Gérer les médias"><i class="fas fa-images"></i></a>
                            <a href="evenements.php?action=supprimer&id=<?= $evt['id'] ?>" onclick="return confirm('Supprimer cet événement ?')" style="color: #dc3545;"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        
        <?php if($action === 'ajouter'): ?>
        <div class="card">
            <div class="card-header">
                <h2>Ajouter un événement</h2>
                <a href="evenements.php" class="btn" style="background: #6c757d;"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
            <form method="POST" enctype="multipart/form-data" style="padding: 30px 0;">
                <div class="form-group">
                    <label>Titre de l'événement *</label>
                    <input type="text" name="titre" required>
                </div>
                <div class="form-group">
                    <label>Date de l'événement *</label>
                    <input type="date" name="date_evenement" required>
                </div>
                <div class="form-group">
                    <label>Lieu</label>
                    <input type="text" name="lieu" value="Joal, Sénégal">
                </div>
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label>Image de couverture</label>
                    <input type="file" name="image_couverture" accept="image/*">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn"><i class="fas fa-save"></i> Enregistrer</button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
