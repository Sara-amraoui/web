<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$page = isset($_GET['page']) ? preg_replace('/[^a-z0-9_]/', '', (string) $_GET['page']) : 'home';

if ($page === '' || $page === 'home') {
    if (Auth::check()) {
        redirect(url(Auth::defaultPageForRole(Auth::role()) ?? 'login'));
    }
    redirect(url('login'));
}

$routes = [
    'login' => [AuthController::class, 'login'],
    'logout' => [AuthController::class, 'logout'],
    'admin_dashboard' => [AdminController::class, 'dashboard'],
    'admin_semesters' => [AdminController::class, 'semesters'],
    'admin_courses' => [AdminController::class, 'courses'],
    'admin_professors' => [AdminController::class, 'professors'],
    'admin_students' => [AdminController::class, 'students'],
    'admin_enrollments' => [AdminController::class, 'enrollments'],
    'admin_assignments' => [AdminController::class, 'assignments'],
    'professor_grades' => [ProfessorController::class, 'grades'],
    'student_dashboard' => [StudentController::class, 'dashboard'],
    'student_history' => [StudentController::class, 'history'],
];

if (!isset($routes[$page])) {
    http_response_code(404);
    echo 'Page not found.';
    exit;
}

[$class, $method] = $routes[$page];
$controller = new $class();
$controller->$method();
