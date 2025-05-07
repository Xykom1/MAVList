<?php
require_once 'init.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    sleep(2);
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Suppression des avis de l'utilisateur
    $stmt = $pdo->prepare("DELETE FROM avis WHERE idUtilisateur = :user_id");
    $stmt->execute(['user_id' => $user_id]);

    // Suppression des collections de l'utilisateur
    $stmt = $pdo->prepare("DELETE FROM collection WHERE idUtilisateur = :user_id");
    $stmt->execute(['user_id' => $user_id]);

    // Suppression de l'utilisateur
    $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE idUtilisateur = :user_id");
    $stmt->execute(['user_id' => $user_id]);

    // Déconnexion et nettoyage session + cookies
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');

    header("Location: inscription.php?compte_supprime");
    exit();

} catch (PDOException $e) {
    echo "Erreur lors de la suppression : " . $e->getMessage();
}
?>