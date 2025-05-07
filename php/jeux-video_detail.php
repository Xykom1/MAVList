<?php
require_once 'init.php';

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeux Vidéo</title>
    <link rel="stylesheet" href="../css/style_header.css">
    <link rel="stylesheet" href="../css/style_jeux-video_detail.css">
    <link rel="stylesheet" href="../css/style_avis.css">
    <link rel="stylesheet" href="../css/style_footer.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon shorticon" href="../Images/logo.webp">
</head>

<body>

    <div class="site-wrapper">
        <?php
        require_once('config.php');
        include('header.php');

        $id = $_GET['id'] ?? null;

        if ($id) {
            $stmt = $pdo->prepare("SELECT o.*, j.*, AVG(a.note) AS 'note' FROM oeuvre o JOIN jeu_video j ON o.idOeuvre = j.idOeuvre LEFT JOIN Avis a ON o.idOeuvre = a.idOeuvre WHERE o.idOeuvre = ?");
            $stmt->execute([$id]);
            $jeu = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($jeu) {
        ?>
                <main class="jeu-detail">
                    <h1><?= htmlspecialchars($jeu['titre']) ?></h1>
                    <img src="<?= $jeu['couverture'] ?>" alt="Couverture de <?= $jeu['titre'] ?>">
                    <p class="note"> Note : 
                    <?php 
                    if ($jeu['note']) {
                        $note = (int)$jeu['note'];
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $note) {
                                echo '<span class="etoilePleine">★</span>';
                            } else {
                                echo '<span class="etoileVide">★</span>';
                            }
                        }
                    } else {
                        echo '<span class="nonNote"> Aucune note </span>';
                    }
                    ?>
                    </p>
                    <p><strong>Genres :</strong> <?= !empty($jeu['genres']) ? htmlspecialchars($jeu['genres']) : 'Aucun genre renseigné' ?></p>
                    <p><strong>Thèmes :</strong> <?= !empty($jeu['themes']) ? htmlspecialchars($jeu['themes']) : 'Aucun thème renseigné' ?></p>
                    <p><strong>Synopsis :</strong> <?= !empty($jeu['synopsis']) ? htmlspecialchars($jeu['synopsis']) : 'Aucun synopsis renseigné' ?></p>
                    <p><strong>Date de sortie :</strong> <?= !empty($jeu['dateSortie']) ? htmlspecialchars($jeu['dateSortie']) : 'Aucune date de sortie renseignée' ?></p>
                    <p><strong>Plateformes :</strong> <?= !empty($jeu['plateformes']) ? htmlspecialchars($jeu['plateformes']) : 'Aucune plateforme renseignée' ?></p>
                    <p><strong>Developpeurs :</strong> <?= !empty($jeu['developpeur']) ? htmlspecialchars($jeu['developpeur']) : 'Aucun développeur renseigné' ?></p>
                    <p><strong>Editeurs :</strong> <?= !empty($jeu['editeur']) ? htmlspecialchars($jeu['editeur']) : 'Aucun éditeur renseigné' ?></p>

                    <!-- Section Avis -->
                    <section class="avis-section">
                        <h2>Avis des utilisateurs</h2>
                        <?php
                        // Récupérer les avis pour cette œuvre
                        $stmt_avis = $pdo->prepare("SELECT a.*, u.nom FROM avis a JOIN utilisateur u ON a.idUtilisateur = u.idUtilisateur WHERE a.idOeuvre = ?");
                        $stmt_avis->execute([$id]);
                        $avis = $stmt_avis->fetchAll(PDO::FETCH_ASSOC);

                        if (count($avis) > 0) {
                            foreach ($avis as $avis_item) {
                                echo '<div class="avis">';
                                echo '<p class="auteur"><strong>' . htmlspecialchars($avis_item['nom']) . '</strong></p>';
                                // Affichage des étoiles
                                echo '<p class="note">';
                                $note = (int)$avis_item['note'];
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $note) {
                                        echo '<span class="etoilePleine">★</span>';
                                    } else {
                                        echo '<span class="etoileVide">★</span>';
                                    }
                                }
                                echo '</p>';
                                echo '<p class="commentaire">' . htmlspecialchars($avis_item['critique']) . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>Aucun avis pour le moment.</p>';
                        }
                        ?>

                        <!-- Formulaire d'ajout d'avis (si utilisateur connecté) -->
                        <?php if ($id) : ?>
                            <button id="openModalBtn">Ajouter un avis</button>
                        <?php else : ?>
                            <p>Connectez-vous pour ajouter un avis.</p>
                        <?php endif; ?>
                    </section>
                </main>
        <?php
            } else {
                echo "<p>Jeu introuvable.</p>";
            }
        } else {
            echo "<p>ID invalide.</p>";
        }
        include('footer.php');
        ?>

    </div>
    <!-- Fenêtre modale -->
    <div id="avisModal" class="avisModal">
        <div class="avisModal-content">
            <span class="close">&times;</span>
            <h3>Ajouter un avis</h3>
            <form action="ajouter_avis.php" method="post">
                <input type="hidden" name="idOeuvre" value="<?= $id ?>">
                <label for="note">Note :</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="note" value="5" /><label for="star5" title="5 étoiles">★</label>
                    <input type="radio" id="star4" name="note" value="4" /><label for="star4" title="4 étoiles">★</label>
                    <input type="radio" id="star3" name="note" value="3" /><label for="star3" title="3 étoiles">★</label>
                    <input type="radio" id="star2" name="note" value="2" /><label for="star2" title="2 étoiles">★</label>
                    <input type="radio" id="star1" name="note" value="1" /><label for="star1" title="1 étoile">★</label>
                </div>
                <label for="commentaire">Commentaire :</label><br>
                <textarea name="commentaire" id="commentaire" rows="4" required></textarea><br>
                <button class="ajouter-avis" type="submit">Ajouter</button>
            </form>
        </div>
    </div>
    <script src="../javascript/script_header.js"></script>
    <script src="../javascript/script_avis.js"></script>
</body>

</html>