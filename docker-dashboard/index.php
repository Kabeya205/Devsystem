<?php
// Inclure la configuration et les fonctions
require_once 'config.php';
require_once 'functions.php';

// Récupérer la liste des conteneurs
$containers = getContainers();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Docker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; }
        h2 { text-align: center; margin-top: 20px; }
        table { border-collapse: collapse; width: 90%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #eee; }
        .btn { padding: 6px 12px; border: none; cursor: pointer; border-radius: 4px; }
        .btn-start { background-color: #4CAF50; color: white; }
        .btn-stop { background-color: #f44336; color: white; }
        .btn-restart { background-color: #2196F3; color: white; }
    </style>
</head>
<body>
    <h2>Liste des conteneurs Docker</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Status</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($containers)): ?>
            <?php foreach ($containers as $c): ?>
                <tr>
                    <td><?= substr($c['Id'], 0, 12) ?></td>
                    <td><?= htmlspecialchars($c['Image']) ?></td>
                    <td><?= htmlspecialchars($c['Status']) ?></td>
                    <td><?= htmlspecialchars($c['Names'][0]) ?></td>
                    <td>
                        <a href="actions.php?action=start&id=<?= $c['Id'] ?>" class="btn btn-start">Start</a>
                        <a href="actions.php?action=stop&id=<?= $c['Id'] ?>" class="btn btn-stop">Stop</a>
                        <a href="actions.php?action=restart&id=<?= $c['Id'] ?>" class="btn btn-restart">Restart</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Aucun conteneur trouvé.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>