<?php
require_once 'init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

$idUtilisateur = $_SESSION['user_id'];
$idOeuvre = $_POST['idOeuvre'];
$note = $_POST['note'];
$commentaire = $_POST['commentaire'];

if ($note >= 1 && $note <= 5 && !empty($commentaire)) {
    $stmt = $pdo->prepare("INSERT INTO avis (idUtilisateur, idOeuvre, note, critique, dateAvis) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$idUtilisateur, $idOeuvre, $note, $commentaire]);
}

// Récupérer le type depuis la table oeuvre
$stmt = $pdo->prepare("SELECT type FROM oeuvre WHERE idOeuvre = ?");
$stmt->execute([$idOeuvre]);
$type = $stmt->fetchColumn();
if ($type === 'Jeu video') {
    $fichier = 'jeux-video_detail.php';
} else {
    $fichier = $type . '_detail.php';
}
header("Location: $fichier?id=$idOeuvre");
exit();
