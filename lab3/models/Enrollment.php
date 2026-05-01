<?php

declare(strict_types=1);

final class Enrollment
{
    /**
     * @param list<int> $studentIds
     */
    public static function syncSemester(int $semesterId, array $studentIds): void
    {
        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('DELETE FROM enrollments WHERE semester_id = ?');
            $stmt->execute([$semesterId]);
            $ins = $pdo->prepare(
                'INSERT INTO enrollments (student_id, semester_id) VALUES (?, ?)'
            );
            foreach (array_unique($studentIds) as $sid) {
                $ins->execute([(int) $sid, $semesterId]);
            }
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * @return list<int>
     */
    public static function studentIdsForSemester(int $semesterId): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT student_id FROM enrollments WHERE semester_id = ?'
        );
        $stmt->execute([$semesterId]);
        return array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }

    public static function isEnrolled(int $studentId, int $semesterId): bool
    {
        $stmt = Database::pdo()->prepare(
            'SELECT 1 FROM enrollments WHERE student_id = ? AND semester_id = ?'
        );
        $stmt->execute([$studentId, $semesterId]);
        return (bool) $stmt->fetchColumn();
    }
}
