<?php
// Vérifier si l'utilisateur est connecté
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;



try {
    // Récupérer les informations de l'utilisateur connecté
    $user_info = null;
    if ($user_id) {
        if (!isset($pdo)) {
            echo "ERREUR : \$pdo n'est pas défini";
        }
        $stmt = $pdo->prepare("SELECT nom, email FROM utilisateur WHERE idUtilisateur = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<header>
    <div class="container">
        <div class="logo">
            <a href="./accueil.php"><img src="../Images/logo.webp" alt="MAVList"></a> <!-- Remplacez par le chemin de votre logo -->
            <span><a href="./accueil.php">MAVList</a></span>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="jeux-video.php">Jeux Vidéo</a></li>
                <li><a href="anime.php">Anime</a></li>
                <li><a href="manga.php">Manga</a></li>
            </ul>
        </nav>
        <?php if ($user_id): ?>
            <div class="profile-menu">
                <i class="fas fa-user-circle" id="profile-icon"></i>
                <ul class="dropdown-content" id="dropdown-menu">
                    <li><a href="compte.php">Mon compte</a></li>
                    <li><a href="collection.php">Mes collections</a></li>
                    <li><a href="#" id="logout-btn">Déconnexion</a></li>
                </ul>
            </div>
        <?php else: ?>
            <div class="auth-links">
                <a href="inscription.php">Inscription</a>
                <a href="connexion.php">Connexion</a>
            </div>
        <?php endif; ?>
    </div>

</header>

<div id="logout-modal" class="modal">
    <div class="modal-content">
        <p>Voulez-vous vraiment vous déconnecter ?</p>
        <div class="modal-buttons">
            <button id="confirm-logout">Oui</button>
            <button id="cancel-logout">Annuler</button>
        </div>
    </div>
</div>
