<?php
$result = "";
$tableHtml = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courses = $_POST['course'] ?? [];
    $credits = $_POST['credits'] ?? [];
    $grades = $_POST['grade'] ?? [];

    $totalPoints = 0;
    $totalCredits = 0;

    $tableHtml = "<table border='1'>";
    $tableHtml .= "<tr>
    <th>Course</th>
    <th>Credits</th>
    <th>Grade</th>
    <th>Points</th>
    </tr>";

    for ($i = 0; $i < count($courses); $i++) {
        $course = htmlspecialchars($courses[$i]);
        $cr = floatval($credits[$i]);
        $g = floatval($grades[$i]);

        if ($cr <= 0) continue;

        $pts = $cr * $g;
        $totalPoints += $pts;
        $totalCredits += $cr;

        $tableHtml .= "<tr>
        <td>$course</td>
        <td>$cr</td>
        <td>$g</td>
        <td>$pts</td>
        </tr>";
    }

    $tableHtml .= "</table>";

    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;

        if ($gpa >= 3.7) {
            $interpretation = "Distinction";
        } elseif ($gpa >= 3.0) {
            $interpretation = "Merit";
        } elseif ($gpa >= 2.0) {
            $interpretation = "Pass";
        } else {
            $interpretation = "Fail";
        }

        $result = "Your GPA is " . number_format($gpa, 2) . " ($interpretation)";
    } else {
        $result = "No valid data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>GPA Calculator</title>

<style>
body {
    font-family: Arial;
    margin: 20px;
}
.course-row {
    margin-bottom: 10px;
}
table {
    border-collapse: collapse;
    margin-bottom: 15px;
}
table th, table td {
    border: 1px solid black;
    padding: 8px;
}
</style>

<script>
function addCourse() {
    var div = document.createElement("div");
    div.className = "course-row";

    div.innerHTML = `
    Course: <input type="text" name="course[]" required>
    Credits: <input type="number" name="credits[]" min="1" required>
    Grade:
    <select name="grade[]">
        <option value="4.0">A</option>
        <option value="3.0">B</option>
        <option value="2.0">C</option>
        <option value="1.0">D</option>
        <option value="0.0">F</option>
    </select>
    <button type="button" onclick="this.parentNode.remove()">Remove</button>
    `;

    document.getElementById("courses").appendChild(div);
}

function validateForm() {
    let credits = document.querySelectorAll('[name="credits[]"]');

    for (let i = 0; i < credits.length; i++) {
        if (credits[i].value <= 0) {
            alert("Credits must be positive");
            return false;
        }
    }
    return true;
}
</script>

</head>
<body>

<h1>GPA Calculator</h1>

<?php if ($result != ""): ?>
    <?php echo $tableHtml; ?>
    <p><strong><?php echo $result; ?></strong></p>
<?php endif; ?>

<form method="post" onsubmit="return validateForm();">

<div id="courses">
    <div class="course-row">
        Course: <input type="text" name="course[]" required>
        Credits: <input type="number" name="credits[]" min="1" required>
        Grade:
        <select name="grade[]">
            <option value="4.0">A</option>
            <option value="3.0">B</option>
            <option value="2.0">C</option>
            <option value="1.0">D</option>
            <option value="0.0">F</option>
        </select>
    </div>
</div>

<br>
<button type="button" onclick="addCourse()">+ Add Course</button>
<br><br>

<input type="submit" value="Calculate GPA">

</form>

</body>
</html>
