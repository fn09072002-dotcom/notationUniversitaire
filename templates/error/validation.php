<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Erreur de soumission</title>
</head>
<body>
    <h1>La soumission n'a pas pu être enregistrée</h1>

    <ul style="color: red;">
        <?php foreach ($erreurs as $erreur): ?>
            <li><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>

    <p><a href="/copies/create">Réessayer</a></p>
</body>
</html>
