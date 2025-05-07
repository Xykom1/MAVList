<?php
require_once 'init.php';

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions Légales</title>
    <link rel="stylesheet" href="../css/style_header.css">
    <link rel="stylesheet" href="../css/style_mentions_legales.css">
    <link rel="stylesheet" href="../css/style_footer.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon shorticon" href="../Images/logo.webp">
</head>

<body>

    <div class="site-wrapper">
        <?php include('header.php'); ?>
        <main>
            <section class="mentions">
                <div class="mentions-title">
                    <h1>Mentions Légales</h1>
                </div>

                <h2>Éditeur du site</h2>
                <p>
                    MAVList<br>
                    Projet personnel dans le cadre du BTS SIO SLAM<br>
                    Contact : lucas.bourguet1@lycee-pardailhan.fr
                </p>

                <h2>Hébergement</h2>
                <p>
                    Ce site est hébergé localement dans le cadre d’un projet d’étude.<br>
                    Aucun hébergeur professionnel n’est utilisé actuellement.
                </p>

                <h2>Propriété intellectuelle</h2>
                <p>
                    L’ensemble des contenus (textes, images, données) utilisés sur MAVList sont utilisés à titre d’illustration dans un cadre éducatif.
                    Les données proviennent d’API publiques telles que Jikan (MyAnimeList) ou IGDB (jeux vidéo).
                </p>

                <h2>Responsabilité</h2>
                <p>
                    MAVList est un projet d’étude sans vocation commerciale.
                    L’équipe ne peut être tenue responsable en cas de mauvais usage ou d’erreurs dans les informations affichées.
                </p>

                <h2>Crédits</h2>
                <p>
                    Données fournies par :
                <ul>
                    <li><a href="https://jikan.moe" target="_blank">Jikan API (MyAnimeList)</a></li>
                    <li><a href="https://api.igdb.com" target="_blank">IGDB API (Jeux vidéo)</a></li>
                </ul>
                </p>
            </section>
        </main>
        <?php include('footer.php'); ?>
    </div>
    <script src="../javascript/script_header.js"></script>
</body>

</html>