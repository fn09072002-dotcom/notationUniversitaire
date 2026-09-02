<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CopieExamen;
use PDO;

final class PdoCopieExamenRepository implements CopieExamenRepositoryInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function save(CopieExamen $copie): int
    {
        $sql = "INSERT INTO copie_examen (date_depot, date_limite, note_brute, note_finale, penalite_appliquee)
                VALUES (:date_depot, :date_limite, :note_brute, :note_finale, :penalite_appliquee)
                RETURNING id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':date_depot', $copie->getDateDepot());
        $stmt->bindValue(':date_limite', $copie->getDateLimite());
        $stmt->bindValue(':note_brute', $copie->getNoteBrute());
        $stmt->bindValue(':note_finale', $copie->getNoteFinale());
        $stmt->bindValue(':penalite_appliquee', $copie->isPenaliteAppliquee(), PDO::PARAM_BOOL);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /** @return CopieExamen[] */
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM copie_examen ORDER BY id DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'hydrater'], $rows);
    }

    public function findById(int $id): ?CopieExamen
    {
        $stmt = $this->pdo->prepare("SELECT * FROM copie_examen WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $this->hydrater($row);
    }

    private function hydrater(array $row): CopieExamen
    {
        return new CopieExamen(
            (string) $row['date_depot'],
            (float) $row['note_brute'],
            $row['penalite_appliquee'] === 't' || $row['penalite_appliquee'] === '1' || $row['penalite_appliquee'] === true,
            (string) $row['date_limite'],
            (int) $row['id']
        );
    }
}
