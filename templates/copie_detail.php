<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de la copie</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <h1>Détail de la copie #<?= htmlspecialchars((string) $copie->getId(), ENT_QUOTES, 'UTF-8') ?></h1>

    <ul>
        <li>Date de dépôt : <?php echo($copie->getDateDepot()) ?></li>
        <li>Date limite : <?= htmlspecialchars($copie->getDateLimite(), ENT_QUOTES, 'UTF-8') ?></li>
        <li>Note brute : <?= htmlspecialchars((string) $copie->getNoteBrute(), ENT_QUOTES, 'UTF-8') ?></li>
        <li>Note finale : <?= htmlspecialchars((string) $copie->getNoteFinale(), ENT_QUOTES, 'UTF-8') ?></li>
        <li>Pénalité appliquée : <?= $copie->isPenaliteAppliquee() ? 'Oui' : 'Non' ?></li>
    </ul>

    <p><a href="/copies">Retour à la liste</a></p>
</body>
</html>
