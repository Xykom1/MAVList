<?php
require_once 'init.php';
$message = null;

// Paramètres de pagination
$limit = 9;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$offset = ($page - 1) * $limit;

$params = [];
$searchSQL = '';

if (!empty($search)) {
    $searchSQL .= " AND titre LIKE ?";
    $params[] = "%$search%";
}

$sql = "
    SELECT o.*
    FROM oeuvre o
    JOIN (
        SELECT MIN(idOeuvre) AS idOeuvre
        FROM oeuvre
        WHERE type = 'anime' $searchSQL
        GROUP BY titre
    ) AS uniques ON o.idOeuvre = uniques.idOeuvre
    WHERE o.type = 'anime'
    ORDER BY o.dateSortie DESC
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$animes = $stmt->fetchAll();

if (!$animes) {
    $message = "Aucun anime trouvé";
}

// Nombre total
$count_sql = "
    SELECT COUNT(*) FROM (
        SELECT 1 FROM oeuvre
        WHERE type = 'anime' $searchSQL
        GROUP BY titre
    ) AS total
";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total = $count_stmt->fetchColumn();
$total_pages = ceil($total / $limit);

function afficherPagination($page, $total_pages, $search = '')
{
    $limiteAffichage = 2; // nombre de pages à afficher avant/après la page courante

    echo '<div class="pagination">';

    // Première page
    if ($page > 1 + $limiteAffichage) {
        echo '<a href="?page=1&search=' . urlencode($search) . '">1</a>';
        if ($page > 2 + $limiteAffichage) {
            echo '<span class="dots">...</span>';
        }
    }

    // Pages autour de la page courante
    for ($i = max(1, $page - $limiteAffichage); $i <= min($total_pages, $page + $limiteAffichage); $i++) {
        $class = ($i == $page) ? 'active' : '';
        echo '<a href="?page=' . $i . '&search=' . urlencode($search) . '" class="' . $class . '">' . $i . '</a>';
    }

    // Dernière page
    if ($page < $total_pages - $limiteAffichage) {
        if ($page < $total_pages - $limiteAffichage - 1) {
            echo '<span class="dots">...</span>';
        }
        echo '<a href="?page=' . $total_pages . '&search=' . urlencode($search) . '">' . $total_pages . '</a>';
    }

    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime</title>
    <link rel="stylesheet" href="../css/style_header.css">
    <link rel="stylesheet" href="../css/style_anime.css">
    <link rel="stylesheet" href="../css/style_footer.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon shorticon" href="../Images/logo.webp">
</head>

<body>

    <div class="site-wrapper">
        <?php include('header.php'); ?>
        <main class="anime-main">
            <h1>Liste des Animes</h1>

            <form method="get" class="search-bar">
                <input type="text" name="search" placeholder="Rechercher un anime..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit">Rechercher</button>
            </form>
            <div id="div-error-play">
                <?php if ($message): ?>
                    <p id="error-play"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
            </div>
            <div class="anime-grid">
                <?php foreach ($animes as $anime): ?>
                    <a href="anime_detail.php?id=<?= $anime['idOeuvre'] ?>" class="card-link">
                        <div class="anime-card">
                            <img src="<?= $anime['couverture'] ?>" alt="Couverture de <?= htmlspecialchars($anime['titre']) ?>">
                            <h3><?= htmlspecialchars($anime['titre']) ?></h2>
                            <p><?= htmlspecialchars($anime['genres']) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php afficherPagination($page, $total_pages, $_GET['search'] ?? ''); ?>
        </main>
        <?php include('footer.php'); ?>
    </div>
    <script src="../javascript/script_header.js"></script>
</body>

</html>