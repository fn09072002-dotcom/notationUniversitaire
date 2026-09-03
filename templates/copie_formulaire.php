<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Soumettre une copie</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <h1>Soumettre une copie d'examen</h1>

    <?php if (!empty($erreurs)): ?>
        <ul style="color: red;">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="/copies" method="post">
        <label for="note_brute">Note brute (sur 20)</label><br>
        <input type="number" step="0.01" id="note_brute" name="note_brute"
               value="<?= htmlspecialchars($ancienneNote ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br>

        <label for="date_depot">Date de dépôt</label><br>
        <input type="date" id="date_depot" name="date_depot"
               value="<?= htmlspecialchars($ancienneDateDepot ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br>

        <label for="date_limite">Date limite</label><br>
        <input type="date" id="date_limite" name="date_limite"
               value="<?= htmlspecialchars($ancienneDateLimite ?? '', ENT_QUOTES, 'UTF-8') ?>" required><br>

        <button type="submit">Soumettre</button>
    </form>

    <p><a href="/copies">Voir la liste des copies</a></p>
</body>
</html>
