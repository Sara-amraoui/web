<?php
declare(strict_types=1);
/** @var array{students:int,professors:int,courses:int,semesters:int} $stats */
?>
<div class="row g-4 mb-2">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--violet">
            <div class="stat-label">Students</div>
            <div class="stat-value"><?= e((string) $stats['students']) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--blue">
            <div class="stat-label">Professors</div>
            <div class="stat-value"><?= e((string) $stats['professors']) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--teal">
            <div class="stat-label">Courses</div>
            <div class="stat-value"><?= e((string) $stats['courses']) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--amber">
            <div class="stat-label">Semesters</div>
            <div class="stat-value"><?= e((string) $stats['semesters']) ?></div>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h5 fw-semibold mb-2">Welcome</h2>
        <p class="text-muted mb-0">Use the sidebar to manage semesters, courses, people, enrollments, and teaching assignments. Student GPA updates automatically when professors submit grades.</p>
    </div>
</div>
