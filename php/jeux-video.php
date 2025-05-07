<?php
require_once 'init.php';
$message = null;

// Paramètres de pagination
$limit = 9;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$offset = ($page - 1) * $limit;

// Requête conditionnelle
$sql = "SELECT * FROM oeuvre WHERE type = 'jeu vidéo'";
$params = [];

if (!empty($search)) {
    $sql .= " AND titre LIKE ?";
    $params[] = "%$search%";
}

$sql .= " ORDER BY dateSortie DESC LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jeux = $stmt->fetchAll();

if (!$jeux) {
    $message = "Aucun jeu vidéo trouvé";
}

// Nombre total
$count_sql = "SELECT COUNT(*) FROM oeuvre WHERE type = 'jeu vidéo'";
if (!empty($search)) {
    $count_sql .= " AND titre LIKE ?";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute(["%$search%"]);
} else {
    $count_stmt = $pdo->query($count_sql);
}
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
    <title>Jeux Vidéo</title>
    <link rel="stylesheet" href="../css/style_header.css">
    <link rel="stylesheet" href="../css/style_jeux-video.css">
    <link rel="stylesheet" href="../css/style_footer.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon shorticon" href="../Images/logo.webp">
</head>

<body>

    <div class="site-wrapper">
        <?php include('header.php'); ?>
        <main class="video-main">
            <h1>Liste des Jeux Vidéo</h1>
            <form method="GET" class="search-bar">
                <input type="text" name="search" placeholder="Rechercher un jeu vidéo..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit">Rechercher</button>
            </form>
            <div id="div-error-play">
                <?php if ($message): ?>
                    <p id="error-play"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
            </div>
            <div class="video-grid">
                <?php foreach ($jeux as $jeu): ?>
                    <a href="jeux-video_detail.php?id=<?= $jeu['idOeuvre'] ?>" class="card-link">
                        <div class="video-card">
                            <img src="<?= htmlspecialchars($jeu['couverture']) ?>" alt="<?= htmlspecialchars($jeu['titre']) ?>">
                            <h3><?= htmlspecialchars($jeu['titre']) ?></h3>
                            <p><?= htmlspecialchars($jeu['genres']) ?></p>
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