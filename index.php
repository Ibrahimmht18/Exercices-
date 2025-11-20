<?php
require 'db.php';

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Recherche
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Insertion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $pays = strtoupper(trim($_POST['pays']));
    $course = $_POST['course'];
    $temps = $_POST['temps'];

    if (preg_match('/^[A-Z]{3}$/', $pays) && is_numeric($temps)) {
        $sth = $dbh->prepare("INSERT INTO `100` (`nom`, `pays`, `course`, `temps`) VALUES (:nom, :pays, :course, :temps)");
        $sth->execute([
            'nom' => $nom,
            'pays' => $pays,
            'course' => $course,
            'temps' => $temps
        ]);
    }
}

// Liste déroulante des courses
$courses = $dbh->query("SELECT DISTINCT course FROM `100`")->fetchAll(PDO::FETCH_COLUMN);

// Requête principale
$sql = "SELECT * FROM `100`";
$params = [];

if ($search !== '') {
    $sql .= " WHERE nom LIKE :search OR pays LIKE :search OR course LIKE :search";
    $params['search'] = "%$search%";
}

$sql .= " ORDER BY course, temps ASC LIMIT $limit OFFSET $offset";
$sth = $dbh->prepare($sql);
$sth->execute($params);
$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

// Classement par course
$classement = [];
$result = [];

foreach ($rows as $row) {
    $course = $row['course'];
    if (!isset($classement[$course])) {
        $classement[$course] = 1;
    }
    $row['rang'] = $classement[$course]++;
    $result[] = $row;
}
?>

<!-- Formulaire d'ajout -->
<form method="POST">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="pays" placeholder="Pays (3 lettres)" required>
    <select name="course" required>
        <?php foreach ($courses as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="temps" placeholder="Temps (ex: 9.83)" required>
    <button type="submit">Ajouter</button>
</form>

<!-- Champ de recherche -->
<form method="GET">
    <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Chercher</button>
</form>

<!-- Tableau des résultats -->
<table border="1">
    <tr>
        <th>Nom</th>
        <th>Pays</th>
        <th>Course</th>
        <th>Temps</th>
        <th>Rang</th>
        <th>Modifier</th>
    </tr>
    <?php foreach ($result as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['nom']) ?></td>
            <td><?= htmlspecialchars($row['pays']) ?></td>
            <td><?= htmlspecialchars($row['course']) ?></td>
            <td><?= htmlspecialchars($row['temps']) ?></td>
            <td><?= $row['rang'] ?></td>
            <td><a href="edit.php?id=<?= $row['id'] ?>">Modifier</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Pagination -->
<div>
    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Précédent</a>
    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Suivant</a>
</div>