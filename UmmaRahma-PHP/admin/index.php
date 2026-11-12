<?php
require_once '../includes/config.php';
require_once '../includes/fonctions.php';
requireAdmin();

$nb_evenements = $pdo->query("SELECT COUNT(*) as total FROM evenements")->fetch()['total'];
$nb_evenements_avenir = $pdo->query("SELECT COUNT(*) as total FROM evenements WHERE date_evenement >= CURDATE()")->fetch()['total'];
$nb_medias = $pdo->query("SELECT COUNT(*) as total FROM medias")->fetch()['total'];
$nb_actualites = $pdo->query("SELECT COUNT(*) as total FROM actualites")->fetch()['total'];

$derniers_evenements = $pdo->query("SELECT * FROM evenements ORDER BY date_creation DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Umma Rahma</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #f5f7fa; }
        .header { background: linear-gradient(135deg, #1a4d2e 0%, #2d5f3b 100%); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header h1 { font-size: 1.5rem; }
        .header-right { display: flex; align-items: center; gap: 20px; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 20px; }
        .stat-icon { width: 60px; height: 60px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; }
        .stat-icon.green { background: #d4edda; color: #155724; }
        .stat-icon.blue { background: #d1ecf1; color: #0c5460; }
        .stat-icon.orange { background: #fff3cd; color: #856404; }
        .stat-icon.purple { background: #e7d4f8; color: #6f42c1; }
        .stat-info h3 { font-size: 2rem; color: #333; }
        .stat-info p { color: #666; font-size: 0.9rem; }
        .card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .card-header { padding: 20px 30px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
        .card-header h2 { color: #1a4d2e; font-size: 1.3rem; }
        .btn { padding: 10px 20px; background: #1a4d2e; color: white; text-decoration: none; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; }
        .btn:hover { background: #2d5f3b; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { background: #f8f9fa; padding: 12px 30px; text-align: left; font-weight: 600; color: #333; font-size: 0.9rem; }
        .table td { padding: 12px 30px; border-top: 1px solid #f0f0f0; color: #666; }
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; }
        .badge.avenir { background: #d4edda; color: #155724; }
        .badge.passe { background: #f8d7da; color: #721c24; }
        .nav-menu { display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap; }
        .nav-menu a { padding: 10px 20px; background: white; color: #333; text-decoration: none; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 8px; }
        .nav-menu a:hover, .nav-menu a.active { background: #1a4d2e; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-tachometer-alt"></i> Tableau de bord - Umma Rahma</h1>
        <div class="header-right">
            <span><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['admin_nom']) ?></span>
            <a href="logout.php" style="color: white; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            <a href="../index.php" target="_blank" style="color: white; text-decoration: none;"><i class="fas fa-external-link-alt"></i> Voir le site</a>
        </div>
    </div>
    
    <div class="container">
        <div class="nav-menu">
            <a href="index.php" class="active"><i class="fas fa-home"></i> Accueil</a>
            <a href="evenements.php"><i class="fas fa-calendar-alt"></i> Événements</a>
            <a href="actualites.php"><i class="fas fa-newspaper"></i> Actualités</a>
            <a href="medias.php"><i class="fas fa-images"></i> Médias</a>
            <a href="utilisateurs.php"><i class="fas fa-users"></i> Utilisateurs</a>
            <a href="parametres.php"><i class="fas fa-cog"></i> Paramètres</a>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-info">
                    <h3><?= $nb_evenements ?></h3>
                    <p>Total Événements</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info">
                    <h3><?= $nb_evenements_avenir ?></h3>
                    <p>Événements à venir</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-images"></i></div>
                <div class="stat-info">
                    <h3><?= $nb_medias ?></h3>
                    <p>Photos/Vidéos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-newspaper"></i></div>
                <div class="stat-info">
                    <h3><?= $nb_actualites ?></h3>
                    <p>Actualités</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-clock"></i> Derniers événements</h2>
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
                    <?php foreach($derniers_evenements as $evt): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($evt['titre']) ?></strong></td>
                        <td><?= date('d/m/Y', strtotime($evt['date_evenement'])) ?></td>
                        <td><?= htmlspecialchars($evt['lieu']) ?></td>
                        <td><span class="badge <?= $evt['date_evenement'] >= date('Y-m-d') ? 'avenir' : 'passe' ?>"><?= $evt['date_evenement'] >= date('Y-m-d') ? 'À venir' : 'Passé' ?></span></td>
                        <td><a href="evenements.php?action=modifier&id=<?= $evt['id'] ?>" style="color: #1a4d2e;"><i class="fas fa-edit"></i></a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
