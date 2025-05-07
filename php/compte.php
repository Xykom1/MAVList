<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Récupérer les infos actuelles de l'utilisateur
$stmt = $pdo->prepare("SELECT nom, prenom, email, numeroTel FROM utilisateur WHERE idUtilisateur = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updates = [];
    $params = ['id' => $user_id];

    // Comparer chaque champ avec sa valeur actuelle
    if (!empty($_POST['nom']) && $_POST['nom'] !== $user['nom']) {
        $updates[] = "nom = :nom";
        $params['nom'] = $_POST['nom'];
    }

    if (!empty($_POST['prenom']) && $_POST['prenom'] !== $user['prenom']) {
        $updates[] = "prenom = :prenom";
        $params['prenom'] = $_POST['prenom'];
    }

    if (!empty($_POST['email']) && $_POST['email'] !== $user['email']) {
        $updates[] = "email = :email";
        $params['email'] = $_POST['email'];
    }

    if (!empty($_POST['telephone']) && $_POST['telephone'] !== $user['numeroTel']) {
        $updates[] = "numeroTel = :telephone";
        $params['telephone'] = $_POST['telephone'];
    }

    if (!empty($_POST['nouveau_mot_de_passe'])) {
        $updates[] = "motDePasse = :mot_de_passe";
        $params['mot_de_passe'] = password_hash($_POST['nouveau_mot_de_passe'], PASSWORD_DEFAULT);
    }

    if (!empty($updates)) {
        $sql = "UPDATE utilisateur SET " . implode(', ', $updates) . " WHERE idUtilisateur = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $message = "Vos informations ont été mises à jour.";
        // Mettre à jour les infos locales
        $stmt = $pdo->prepare("SELECT nom, prenom, email, numeroTel FROM utilisateur WHERE idUtilisateur = :id");
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "Aucune information modifiée.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon compte</title>
    <link rel="stylesheet" href="../css/style_header.css">
    <link rel="stylesheet" href="../css/style_compte.css">
    <link rel="stylesheet" href="../css/style_footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="icon shorticon" href="../Images/logo.webp">
</head>

<body>
    <?php include('header.php'); ?>
    <main>
        <h1>Mon compte</h1>
        <?php if (!empty($message)): ?>
            <div id="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form class="info-form" method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>">

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>">

            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">

            <label for="telephone">Téléphone :</label>
            <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($user['numeroTel']) ?>">

            <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
            <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe">

            <button id="maj-button" type="submit">Mettre à jour</button>
        </form>

        <form id="suppr-form" action="supprimer_compte.php" method="post">
            <button type="button" id="open-suppr-modal">Supprimer mon compte</button>
        </form>
    </main>
    <div id="suppr-modal" class="modal">
            <div class="modal-content">
                <p> Voulez-vous vraiment supprimer votre compte ?</p>
                <div class="modal-buttons">
                    <button id="confirm-suppr">Oui</button>
                    <button id="cancel-suppr">Annuler</button>
                </div>
            </div>
    </div>
    <?php include('footer.php'); ?>
    <script src="../javascript/script_header.js"></script>
    <script src="../javascript/script_compte.js"></script>
</body>

</html>