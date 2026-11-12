<?php
function getEvenementsAvenir($pdo, $limit = 3) {
    $stmt = $pdo->prepare("SELECT * FROM evenements WHERE date_evenement >= CURDATE() ORDER BY date_evenement ASC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getEvenementsPasses($pdo, $limit = 10) {
    $stmt = $pdo->prepare("SELECT * FROM evenements WHERE date_evenement < CURDATE() ORDER BY date_evenement DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getMediasEvenement($pdo, $evenement_id) {
    $stmt = $pdo->prepare("SELECT * FROM medias WHERE evenement_id = ? ORDER BY ordre ASC, id ASC");
    $stmt->execute([$evenement_id]);
    return $stmt->fetchAll();
}

function getActualites($pdo, $limit = 3) {
    $stmt = $pdo->prepare("SELECT * FROM actualites ORDER BY date_creation DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getParametres($pdo) {
    $stmt = $pdo->query("SELECT cle, valeur FROM parametres");
    $params = [];
    while($row = $stmt->fetch()) {
        $params[$row['cle']] = $row['valeur'];
    }
    return $params;
}

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
}

function slugify($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}
?>
