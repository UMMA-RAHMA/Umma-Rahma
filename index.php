<?php
require_once 'includes/config.php';
require_once 'includes/fonctions.php';

$actualites = array();
$evenements_passes = array();
$parametres = array();
$medias_par_evenement = array();

try {
    if (function_exists('getActualites')) {
        $actualites = getActualites($pdo, 4);
    } else {
        $stmt = $pdo->query("SELECT * FROM actualites ORDER BY date_creation DESC LIMIT 4");
        $actualites = $stmt->fetchAll();
    }
} catch (Exception $e) {}

try {
    if (function_exists('getEvenementsPasses')) {
        $evenements_passes = getEvenementsPasses($pdo, 10);
    } else {
        $stmt = $pdo->query("SELECT * FROM evenements WHERE date_evenement < CURDATE() ORDER BY date_evenement DESC LIMIT 10");
        $evenements_passes = $stmt->fetchAll();
    }
} catch (Exception $e) {}

try {
    if (function_exists('getParametres')) {
        $parametres = getParametres($pdo);
    } else {
        $stmt = $pdo->query("SELECT cle, valeur FROM parametres");
        while($row = $stmt->fetch()) {
            $parametres[$row['cle']] = $row['valeur'];
        }
    }
} catch (Exception $e) {}

try {
    if (function_exists('getMediasEvenement')) {
        foreach ($evenements_passes as $evt) {
            $medias_par_evenement[$evt['id']] = getMediasEvenement($pdo, $evt['id']);
        }
    } else {
        $stmt = $pdo->query("SELECT * FROM medias ORDER BY ordre ASC, id ASC");
        while($m = $stmt->fetch()) {
            $medias_par_evenement[$m['evenement_id']][] = $m;
        }
    }
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TJKX5SV6');</script>
    <!-- End Google Tag Manager -->
    <link rel="canonical" href="https://ummarahma.wuaze.com/" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-PC7E6XCJS2"></script>
    <script>
      window.dataLayer = window.dataTag = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-PC7E6XCJS2');
    </script>
    <meta charset="UTFU-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umma Rahma – Association de Solidarité à Joal, Sénégal</title>
    <meta name="description" content="Découvrez Umma Rahma à Joal. Nous protégeons et accompagnons les enfants talibés par des actions sociales, sanitaires et éducatives. Rejoignez notre mission.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.formsubmit.co/ajax.js" defer></script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NGO",
      "name": "Association Umma Rahma",
      "url": "https://ummarahma.wuaze.com/",
      "description": "Association de solidarité à Joal, Sénégal, dédiée à la protection sociale, sanitaire et éducative des enfants talibés.",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Joal",
        "addressRegion": "Région de Thiès",
        "addressCountry": "SN"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "Adhésions et Dons",
        "availableLanguage": ["French", "Wolof"]
      }
    }
    </script>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TJKX5SV6"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
    <nav class="navbar">
        <div class="nav-container">
            <a href="#accueil" class="nav-logo">
                <img src="images/logo-oumma-rahma.png" alt="Umma Rahma" class="hero-logo-2">
                <div class="nav-logo-text">Umma <span>Rahma</span></div>
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="#accueil" class="active">Accueil</a></li>
                <li><a href="#association">Association</a></li>
                <li><a href="#membres">Membres</a></li>
                <li><a href="#organisation">Organisation</a></li>
                <li><a href="#passet">PASSET</a></li>
                <li><a href="#financement">Financement</a></li>
                <li><a href="#actualites">Actualités</a></li>
                <li><a href="adhesion.html">Devenir membre</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="#don" class="nav-donate-btn"><i class="fas fa-heart"></i> Faire un don</a></li>
            </ul>
            <div class="hamburger" id="hamburger">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    <section class="hero" id="accueil">
        <div class="hero-content">
            <img src="images/logo-oumma-rahma.png" alt="Oumma Rahma" class="hero-logo">
            <h1>Umma <span>Rahma</span></h1>
            <p class="hero-slogan">« Donner avec amour – Agir avec dignité »</p>
            <p class="hero-description">
                Programme d'Assistance Sociale et Sanitaire pour les Enfants Talibés (PASSET).
                Ensemble, construisons un avenir meilleur pour les enfants les plus vulnérables de Joal.
            </p>
            <div>
                <a href="#don" class="btn btn-primary"><i class="fas fa-heart"></i> Faire un don</a>
                <a href="#passet" class="btn btn-secondary"><i class="fas fa-book-open"></i> Découvrir le PASSET</a>
            </div>
        </div>
    </section>

    <section class="section section-white" id="actualites">
        <div class="container">
            <div class="section-header">
                <div class="section-tag"><i class="fas fa-newspaper"></i> Actualités</div>
                <h2 class="section-title">Nos dernières nouvelles</h2>
                <p class="section-subtitle">Restez informés des actions et événements d'Oumma Rahma</p>
            </div>

            <div class="articles-grid">
                <?php if(empty($actualites)): ?>
                    <p style="text-align:center; grid-column:1/-1;">Aucune actualité publiée pour le moment.</p>
                <?php else: ?>
                    <?php foreach($actualites as $actu): ?>
                    <div class="article-card">
                        <?php if(!empty($actu['image'])): ?>
                        <div class="article-image">
                            <img src="uploads/<?= htmlspecialchars($actu['image']) ?>" alt="<?= htmlspecialchars($actu['titre']) ?>">
                        </div>
                        <?php endif; ?>
                        <div class="article-content">
                            <div class="article-date"><i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($actu['date_creation'])) ?></div>
                            <h4><?= htmlspecialchars($actu['titre']) ?></h4>
                            <p><?= nl2br(htmlspecialchars($actu['contenu'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if(!empty($evenements_passes)): ?>
            <div class="section-header" style="margin-top:60px;">
                <div class="section-tag"><i class="fas fa-camera"></i> Retour en images</div>
                <h2 class="section-title">Nos événements passés</h2>
                <p class="section-subtitle">Les moments forts de nos actions</p>
            </div>
            <div class="gallery-grid">
                <?php foreach($evenements_passes as $evt): 
                    $medias = isset($medias_par_evenement[$evt['id']]) ? $medias_par_evenement[$evt['id']] : array();
                    if(!empty($medias)):
                        foreach(array_slice($medias, 0, 8) as $media): ?>
                <div class="gallery-item">
                    <?php if($media['type'] == 'image'): ?>
                        <img src="uploads/<?= htmlspecialchars($media['fichier']) ?>" alt="<?= htmlspecialchars($evt['titre']) ?>">
                    <?php else: ?>
                        <video controls><source src="uploads/<?= htmlspecialchars($media['fichier']) ?>" type="video/mp4"></video>
                    <?php endif; ?>
                    <div class="gallery-caption"><?= htmlspecialchars($evt['titre']) ?> - <?= date('d/m/Y', strtotime($evt['date_evenement'])) ?></div>
                </div>
                <?php endforeach; endif; endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section section-dark" id="financement">
        <div class="container">
            <div class="section-header">
                <div class="section-tag"><i class="fas fa-hand-holding-dollar"></i> Financement</div>
                <h2 class="section-title">Soutenez notre mission</h2>
                <p class="section-subtitle">Chaque contribution compte pour offrir un avenir meilleur aux enfants.</p>
            </div>
            <div class="donate-section" id="don">
                <h3><i class="fas fa-heart" style="margin-right:10px;color:var(--yellow-light);"></i>Faire un don</h3>
                <p>Votre générosité transforme des vies. Cliquez ci-dessous pour faire un don sécurisé via Wave.</p>
                <a href="<?= htmlspecialchars($parametres['wave'] ?? 'https://pay.wave.com/m/M_sn_-3jBgdphMdHc/c/sn/') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="font-size:1.1rem;padding:16px 40px;"><i class="fas fa-external-link-alt"></i> Faire un don via Wave</a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>Umma <span>Rahma</span></h3>
                <p>Association de solidarité pour l'assistance sociale et sanitaire des enfants talibés à Joal, Sénégal.</p>
            </div>
            <div class="footer-col">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="#accueil">Accueil</a></li>
                    <li><a href="#association">Association</a></li>
                    <li><a href="#passet">PASSET</a></li>
                    <li><a href="#actualites">Actualités</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>S'impliquer</h4>
                <ul>
                    <li><a href="#don">Faire un don</a></li>
                    <li><a href="adhesion.html">Devenir membre</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li><a href="tel:<?= htmlspecialchars(str_replace(' ','',$parametres['tel1']??'+221704776642')) ?>"><?= htmlspecialchars($parametres['tel1']??'+221 70 477 66 42') ?></a></li>
                    <li><a href="mailto:<?= htmlspecialchars($parametres['email']??'rahmaoumma26@gmail.com') ?>"><?= htmlspecialchars($parametres['email']??'rahmaoumma26@gmail.com') ?></a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Umma Rahma. Tous droits réservés.</p>
            <p class="footer-credit">Site conçu par <a href="https://wa.me/221703447818">Aladji Mansour SAMB</a></p>
        </div>
    </footer>

    <a href="https://wa.me/<?= str_replace(['+',' ','-'],'',$parametres['tel1']??'221704776642') ?>?text=Assalamu%20alaykum%20!" class="whatsapp-float" target="_blank"><i class="fab fa-whatsapp"></i></a>
    <script src="assets/js/main.js"></script>
</body>
</html>
