<?php
require_once 'init.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $terms = isset($_POST['terms']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO utilisateur (prenom, nom, email, motDePasse, numeroTel, condUtilisation) VALUES (:firstname, :lastname, :email, :password, :phone, :terms)");

    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':terms', $terms, PDO::PARAM_INT);

    try {
        $stmt->execute();
        header("Location: connexion.php?compte_cree");
        sleep(2);
    } catch (PDOException $e) {
        echo "Erreur lors de l'inscription : " . $e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/style_header.css">
    <link rel="stylesheet" href="../css/style_inscription.css">
    <link rel="stylesheet" href="../css/style_footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="icon shorticon" href="../Images/logo.webp">
</head>

<body>
    <div class=site-wrapper>

        <?php include('header.php'); ?>
        <main>
            <div class="form-container-inscription">
                <h2>Inscription</h2>
                <?php if (isset($_GET['compte_supprime'])): ?>
                    <p id="success-message" class="message-suppression">Votre compte a bien été supprimé.</p>
                <?php endif; ?>
                <form action="inscription.php" method="POST">
                    <div class="form-group-inscription">
                        <label for="firstname">Prénom* :</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group-inscription">
                        <label for="lastname">Nom* :</label>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>
                    <div class="form-group-inscription">
                        <label for="email">Adresse e-mail* :</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group-inscription">
                        <label for="password">Mot de passe* :</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group-inscription">
                        <label for="confirm_password">Confirmer le mot de passe* :</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="form-group-inscription">
                        <label for="phone">Numéro de téléphone :</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    <div class="form-group-inscription" id="form-group-terms-inscription">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms" class="checkbox-label">J'accepte les <a href="cgu.php">conditions d'utilisation</a>*</label>
                    </div>
                    <button class="form-button-inscription" id="registration-form" type="submit">S'inscrire</button>
                </form>
            </div>
        </main>
        <?php include('footer.php'); ?>
    </div>
    <script src="../javascript/script_inscription.js"></script>
</body>

</html>