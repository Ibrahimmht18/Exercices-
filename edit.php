<?php
require 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID manquant");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $pays = strtoupper($_POST['pays']);
    $course = $_POST['course'];
    $temps = $_POST['temps'];

    if (preg_match('/^[A-Z]{3}$/', $pays) && is_numeric($temps)) {
        $sth = $dbh->prepare("UPDATE `100` SET nom = :nom, pays = :pays, course = :course, temps = :temps WHERE id = :id");
        $sth->execute([
            'nom' => $nom,
            'pays' => $pays,
            'course' => $course,
            'temps' => $temps,
            'id' => $id
        ]);
        header("Location: index.php");
        exit;
    }
}

$sth = $dbh->prepare("SELECT * FROM `100` WHERE id = :id");
$sth->execute(['id' => $id]);
$data = $sth->fetch();
?>

<!-- Formulaire de modification -->
<form method="POST">
    <input type="text" name="nom" value="<?= htmlspecialchars($data['nom']) ?>" required>
    <input type="text" name="pays" value="<?= htmlspecialchars($data['pays']) ?>" required>
    <input type="text" name="course" value="<?= htmlspecialchars($data['course']) ?>" required>
    <input type="text" name="temps" value="<?= htmlspecialchars($data['temps']) ?>" required>
    <button type="submit">Enregistrer</button>
</form>