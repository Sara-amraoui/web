<?php
header('Content-Type: application/json');

if (isset($_POST['course'], $_POST['credits'], $_POST['grade'])) {

    $courses = $_POST['course'];
    $credits = $_POST['credits'];
    $grades = $_POST['grade'];

    $totalPoints = 0;
    $totalCredits = 0;

    $tableHtml = "<table class='table table-bordered mt-3'>";
    $tableHtml .= "<tr>
    <th>Course</th>
    <th>Credits</th>
    <th>Grade</th>
    <th>Points</th>
    </tr>";

    for ($i = 0; $i < count($courses); $i++) {
        $cr = floatval($credits[$i]);
        $g = floatval($grades[$i]);

        if ($cr <= 0) continue;

        $pts = $cr * $g;
        $totalPoints += $pts;
        $totalCredits += $cr;

        $tableHtml .= "<tr>
        <td>{$courses[$i]}</td>
        <td>$cr</td>
        <td>$g</td>
        <td>$pts</td>
        </tr>";
    }

    $tableHtml .= "</table>";

    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;

        $message = "Your GPA is " . number_format($gpa, 2)
            "($interpretation).";
        echo json_encode([
            "success" => true,
            "gpa" => $gpa,
            "message" => $message,
            "tableHtml" => $tableHtml
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "No valid courses entered.",
        ]);
    }

} else {
    echo json_encode([
        "success" => false,
        "message" => "Data not received."
    ]);
}
exit;
?>
