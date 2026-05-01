<?php

declare(strict_types=1);

final class Semester
{
    /**
     * @return list<array<string,mixed>>
     */
    public static function all(): array
    {
        $stmt = Database::pdo()->query(
            'SELECT * FROM semesters ORDER BY academic_year DESC, label ASC'
        );
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM semesters WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function active(): ?array
    {
        $stmt = Database::pdo()->query('SELECT * FROM semesters WHERE is_active = 1 LIMIT 1');
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(string $label, string $academicYear, bool $isActive): int
    {
        $pdo = Database::pdo();
        if ($isActive) {
            $pdo->exec('UPDATE semesters SET is_active = 0');
        }
        $stmt = $pdo->prepare(
            'INSERT INTO semesters (label, academic_year, is_active) VALUES (?, ?, ?)'
        );
        $stmt->execute([$label, $academicYear, $isActive ? 1 : 0]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, string $label, string $academicYear, bool $isActive): void
    {
        $pdo = Database::pdo();
        if ($isActive) {
            $stmt = $pdo->prepare('UPDATE semesters SET is_active = 0 WHERE id != ?');
            $stmt->execute([$id]);
        }
        $stmt = $pdo->prepare(
            'UPDATE semesters SET label = ?, academic_year = ?, is_active = ? WHERE id = ?'
        );
        $stmt->execute([$label, $academicYear, $isActive ? 1 : 0, $id]);
    }

    public static function setActive(int $id): void
    {
        $pdo = Database::pdo();
        $pdo->exec('UPDATE semesters SET is_active = 0');
        $stmt = $pdo->prepare('UPDATE semesters SET is_active = 1 WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::pdo()->prepare('DELETE FROM semesters WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function hasCourses(int $semesterId): bool
    {
        $stmt = Database::pdo()->prepare('SELECT 1 FROM courses WHERE semester_id = ? LIMIT 1');
        $stmt->execute([$semesterId]);
        return (bool) $stmt->fetchColumn();
    }

    public static function hasEnrollments(int $semesterId): bool
    {
        $stmt = Database::pdo()->prepare('SELECT 1 FROM enrollments WHERE semester_id = ? LIMIT 1');
        $stmt->execute([$semesterId]);
        return (bool) $stmt->fetchColumn();
    }

    public static function count(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM semesters')->fetchColumn();
    }

    public static function existsLabelYear(string $label, string $academicYear, ?int $excludeId): bool
    {
        $sql = 'SELECT 1 FROM semesters WHERE label = ? AND academic_year = ?';
        $params = [$label, $academicYear];
        if ($excludeId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }
        $sql .= ' LIMIT 1';
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetchColumn();
    }
}
