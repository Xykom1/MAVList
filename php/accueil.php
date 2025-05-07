<?php
require_once 'init.php';
try {
    // Récupérer les œuvres populaires
    $stmt = $pdo->query("
        SELECT o.idOeuvre, o.titre, o.type, o.couverture, AVG(a.note) as note_moyenne
        FROM Oeuvre o
        LEFT JOIN Avis a ON o.idOeuvre = a.idOeuvre
        GROUP BY o.idOeuvre
        ORDER BY note_moyenne DESC
        LIMIT 5
    ");
    $oeuvres_populaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les recommandations (œuvres récemment ajoutées)
    $stmt = $pdo->query("
        SELECT o.idOeuvre, o.titre, o.type, o.couverture
        FROM Oeuvre o
        ORDER BY RAND()
        LIMIT 3
    ");
    $recommandations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les derniers avis
    $stmt = $pdo->query("
        SELECT o.titre, a.critique, a.note, u.nom
        FROM Avis a
        JOIN Oeuvre o ON a.idOeuvre = o.idOeuvre
        JOIN Utilisateur u ON a.idUtilisateur = u.idUtilisateur
        ORDER BY a.dateAvis DESC
        LIMIT 5
    ");
    $derniers_avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les tendances (œuvres les mieux notées)
    $stmt = $pdo->query("
        SELECT o.idOeuvre, o.titre, o.type, o.couverture
        FROM oeuvre o
        WHERE YEAR(o.dateSortie) = YEAR(CURDATE())
        ORDER BY RAND()
        LIMIT 5;
    ");
    $tendances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="../css/style_header.css">
    <link rel="stylesheet" href="../css/style_accueil.css">
    <link rel="stylesheet" href="../css/style_footer.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon shorticon" href="../Images/logo.webp">
</head>

<body>
    <div class="site-wrapper">
        <?php include('header.php'); ?>
        <main>
            <!-- Section principale : Œuvres Populaires -->
            <section class="popular-works">
                <div class="swiper">
                    <h2>Œuvres Populaires</h2>
                    <div class="swiper-wrapper">
                        <?php foreach ($oeuvres_populaires as $oeuvre): ?>
                            <div class="swiper-slide carousel-item">
                                <img src="<?php echo htmlspecialchars($oeuvre['couverture']); ?>" alt="<?php echo htmlspecialchars($oeuvre['titre']); ?>">
                                <h3><?php echo htmlspecialchars($oeuvre['titre']); ?></h3>
                                <p><?php echo htmlspecialchars($oeuvre['type']); ?></p>
                                <p>Note moyenne :
                                    <span class="star-rating">
                                        <?php
                                        $note_moyenne = round($oeuvre['note_moyenne']);
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $note_moyenne) {
                                                echo '★'; // Étoile pleine
                                            } else {
                                                echo '☆'; // Étoile vide
                                            }
                                        }
                                        ?>
                                    </span>
                                </p>
                                <?php
                                $type = strtolower($oeuvre['type']); // Met en minuscules pour gérer les différences
                                $page = match ($type) {
                                    'anime' => 'anime_detail.php',
                                    'manga' => 'manga_detail.php',
                                    'jeu video' => 'jeux-video_detail.php',
                                };
                                ?>
                                <a href="<?= $page ?>?id=<?= htmlspecialchars($oeuvre['idOeuvre']) ?>" class="cta-button">En savoir plus</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Ajouter les boutons de navigation -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </section>

            <!-- Section recommandations -->
            <section class="recommendations">
                <div class="recommendation-frame">
                    <h2>Recommandations</h2>
                    <div class="recommendation-list">
                        <?php foreach ($recommandations as $recommandation): ?>
                            <div class="recommendation-item">
                                <img src="<?php echo htmlspecialchars($recommandation['couverture']); ?>" alt="<?php echo htmlspecialchars($recommandation['titre']); ?>">
                                <h3><?php echo htmlspecialchars($recommandation['titre']); ?></h3>
                                <p><?php echo htmlspecialchars($recommandation['type']); ?></p>
                                <?php
                                $type = strtolower($recommandation['type']); // Met en minuscules pour gérer les différences
                                $page = match ($type) {
                                    'anime' => 'anime_detail.php',
                                    'manga' => 'manga_detail.php',
                                    'jeu video' => 'jeux-video_detail.php',
                                };
                                ?>
                                <a href="<?= $page ?>?id=<?= htmlspecialchars($recommandation['idOeuvre']) ?>" class="cta-button">En savoir plus</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- Section Derniers Avis -->
            <section class="latest-reviews">
                <h2>Derniers Avis</h2>
                <div class="review-list">
                    <?php foreach ($derniers_avis as $avis): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <h3><?php echo htmlspecialchars($avis['titre']); ?></h3>
                                <p>Note :
                                    <span>
                                        <?php
                                        $note = round($avis['note']);
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $note) {
                                                echo '★'; // Étoile pleine
                                            } else {
                                                echo '☆'; // Étoile vide
                                            }
                                        }
                                        ?>
                                    </span>
                                </p>
                            </div>
                            <p class="review-body">"<?php echo htmlspecialchars($avis['critique']); ?>"</p>
                            <p class="review-author">- <?php echo htmlspecialchars($avis['nom']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Section Tendances -->
            <section class="trends">
                <h2>Tendances</h2>
                <div class="trend-list">
                    <?php foreach ($tendances as $tendance): ?>
                        <div class="trend-item">
                            <img src="<?php echo htmlspecialchars($tendance['couverture']); ?>" alt="<?php echo htmlspecialchars($tendance['titre']); ?>">
                            <h3><?php echo htmlspecialchars($tendance['titre']); ?></h3>
                            <p><?php echo htmlspecialchars($tendance['type']); ?></p>
                            <?php
                            $type = strtolower($tendance['type']); // Met en minuscules pour gérer les différences
                            $page = match ($type) {
                                'anime' => 'anime_detail.php',
                                'manga' => 'manga_detail.php',
                                'jeu video' => 'jeux-video_detail.php',
                            };
                            ?>
                            <a href="<?= $page ?>?id=<?= htmlspecialchars($tendance['idOeuvre']) ?>" class="cta-button">En savoir plus</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
        <?php include('footer.php'); ?>
    </div>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="../javascript/script_accueil.js"></script>
    <script src="../javascript/script_header.js"></script>
</body>

</html>