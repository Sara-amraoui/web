<?php

declare(strict_types=1);

/**
 * Escape output for HTML.
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Base path of the application (folder containing index.php).
 */
function base_path(): string
{
    static $path;
    if ($path === null) {
        $path = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
        if ($path === '' || $path === '.') {
            $path = '';
        }
    }
    return $path;
}

/**
 * URL to asset under public/.
 */
function asset(string $path): string
{
    $p = ltrim($path, '/');
    return base_path() . '/public/' . $p;
}

/**
 * URL to index.php with query.
 */
function url(string $page, array $params = []): string
{
    $params = array_merge(['page' => $page], $params);
    return base_path() . '/index.php?' . http_build_query($params);
}

/**
 * Redirect and exit.
 */
function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}

/**
 * JSON response for APIs.
 */
function json_response(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_THROW_ON_ERROR);
    exit;
}

/**
 * Grade letter to points (4.0 scale).
 */
function grade_to_points(string $letter): ?float
{
    $map = [
        'A' => 4.0,
        'B' => 3.0,
        'C' => 2.0,
        'D' => 1.0,
        'F' => 0.0,
    ];
    $k = strtoupper(trim($letter));
    return $map[$k] ?? null;
}

/**
 * Allowed grade letters.
 */
function allowed_grades(): array
{
    return ['A', 'B', 'C', 'D', 'F', ''];
}

/**
 * @return array{page:int,per_page:int,total_pages:int,offset:int}
 */
function pagination_state(int $total, int $page, int $perPage): array
{
    $totalPages = max(1, (int) ceil($total / $perPage));
    if ($page < 1) {
        $page = 1;
    }
    if ($page > $totalPages) {
        $page = $totalPages;
    }
    return [
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => $totalPages,
        'offset' => ($page - 1) * $perPage,
    ];
}
