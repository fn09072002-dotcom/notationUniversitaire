<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des copies</title>
</head>
<body>
    <h1>Copies enregistrées</h1>

    <p><a href="/copies/create">Soumettre une nouvelle copie</a></p>

    <?php if (empty($copies)): ?>
        <p>Aucune copie enregistrée pour le moment.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Date de dépôt</th>
                    <th>Date limite</th>
                    <th>Note brute</th>
                    <th>Note finale</th>
                    <th>Pénalité</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($copies as $copie): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $copie->getId(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($copie->getDateDepot(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($copie->getDateLimite(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $copie->getNoteBrute(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) $copie->getNoteFinale(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $copie->isPenaliteAppliquee() ? 'Oui' : 'Non' ?></td>
                        <td><a href="/copies/<?= htmlspecialchars((string) $copie->getId(), ENT_QUOTES, 'UTF-8') ?>">Voir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
