<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Repository\Database;

try {
    $pdo = Database::getInstance();
    $stmt = $pdo->query('SELECT * FROM copie_examen');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Connexion réussie. Contenu de copie_examen :" . PHP_EOL;
    foreach ($rows as $row) {
        echo "  id={$row['id']} | note_finale={$row['note_finale']} | penalite={$row['penalite_appliquee']}" . PHP_EOL;
    }
} catch (\Throwable $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
}
