<?php
require_once 'init.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mdp = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT idUtilisateur, email, motDePasse FROM utilisateur WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($mdp, $user['motDePasse'])) {
                $_SESSION['user_id'] = $user['idUtilisateur'];
                header('Location: accueil.php');
                sleep(2);
                exit();
            } else {
                $error_message = "Mot de passe incorrect.";
            }
        } else {
            $error_message = "Aucun compte trouvé avec cet email.";
        }
    } catch (PDOException $e) {
        $error_message = "Erreur : " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/style_header.css">
    <link rel="stylesheet" href="../css/style_connexion.css">
    <link rel="stylesheet" href="../css/style_footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="icon shorticon" href="../Images/logo.webp">
</head>

<body>
    <div class="site-wrapper">
        <?php require('header.php'); ?>
        <main>
            <div class="form-container-connexion">
                <h2>Connexion</h2>
                <?php if (isset($_GET['compte_cree'])): ?>
                    <p id="success-message" class="message-creation">Votre compte a bien été créé.</p>
                <?php endif; ?>
                <div class="error-message-connexion" <?php if (!empty($error_message)) echo 'style="display: block;"'; ?>>
                    <?php echo $error_message; ?>
                </div>
                <form action="connexion.php" method="POST">
                    <div class="form-group-connexion">
                        <label for="email">Adresse e-mail* :</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group-connexion">
                        <label for="password">Mot de passe* :</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button class="form-button-connexion" type="submit">Se connecter</button>
                </form>
            </div>
        </main>
        <?php include('footer.php'); ?>
    </div>
    <script src="../javascript/script_connexion.js"></script>
</body>

</html>