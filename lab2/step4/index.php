<?php
$conn = new mysqli("localhost", "root", "", "gpa_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$gpa = 0;
$resultMsg = "";
$courses = [];
$credits = [];
$grades = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calculate'])) {
    $student = $_POST['student'];
    $semester = $_POST['semester'];
    $courses = $_POST['course'];
    $credits = $_POST['credits'];
    $grades = $_POST['grade'];

    $totalPoints = 0;
    $totalCredits = 0;

    for ($i = 0; $i < count($courses); $i++) {
        $cr = floatval($credits[$i]);
        $gr = floatval($grades[$i]);

        if ($cr > 0) {
            $totalPoints += $cr * $gr;
            $totalCredits += $cr;
        }
    }

    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;
      
        $check = $conn->prepare("SELECT id FROM results WHERE student = ? AND semester = ?");
        $check->bind_param("ss", $student, $semester);
        $check->execute();
        $check->store_result();

        if ($check->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO results (student, semester, gpa) VALUES (?, ?, ?)");
            $stmt->bind_param("ssd", $student, $semester, $gpa);
            $stmt->execute();
            $stmt->close();
        }
        $check->close();

        $resultMsg = "Your GPA is " . number_format($gpa, 2);
    }
}

if (isset($_POST['delete_all'])) {
    $conn->query("TRUNCATE TABLE results");
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>GPA Calculator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .card-custom {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body class="container mt-5">

    <div class="card card-custom">
        <div class="card-header bg-primary text-white text-center">
            <h2> GPA Calculator</h2>
            <p>Calculate Your Grade Point Average</p>
        </div>
        <div class="card-body">

            <form method="post">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label> Student Name</label>
                        <input type="text" name="student" class="form-control" placeholder="Enter student name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label> Semester</label>
                        <input type="text" name="semester" class="form-control" placeholder="Enter semester" required>
                    </div>
                </div>

                <hr>
                <h5> Courses</h5>
                <div id="courses">
                    <div class="form-row mb-2">
                        <div class="col-md-5">
                            <input type="text" name="course[]" class="form-control" placeholder="Course Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="credits[]" class="form-control" placeholder="Credits" step="1" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <select name="grade[]" class="form-control">
                                <option value="4">A (Excellent)</option>
                                <option value="3">B (Very Good)</option>
                                <option value="2">C (Good)</option>
                                <option value="1">D (Pass)</option>
                                <option value="0">F (Fail)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addCourse()" class="btn btn-secondary btn-sm mb-3"> Add Course</button>
                <br>
                <button type="submit" name="calculate" class="btn btn-primary btn-lg btn-block">Calculate GPA</button>
            </form>

            <?php if($resultMsg != ""): ?>
                <div class="alert alert-info mt-3 text-center">
                    <h4><?php echo $resultMsg; ?></h4>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"> Course Details</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Course Name</th>
                                    <th>Credits</th>
                                    <th>Grade</th>
                                    <th>Grade Letter</th>
                                    <th>Grade Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_points_display = 0;
                                $total_credits_display = 0;
                                for ($i = 0; $i < count($courses); $i++): 
                                    $cr = floatval($credits[$i]);
                                    $gr = floatval($grades[$i]);
                                    $points = $cr * $gr;
                                    $total_points_display += $points;
                                    $total_credits_display += $cr;
                              
                                    if($gr == 4) $grade_letter = "A";
                                    elseif($gr == 3) $grade_letter = "B";
                                    elseif($gr == 2) $grade_letter = "C";
                                    elseif($gr == 1) $grade_letter = "D";
                                    else $grade_letter = "F";
                                ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($courses[$i]); ?></strong></td>
                                        <td><?php echo $cr; ?></td>
                                        <td><?php echo $gr; ?></td>
                                        <td>
                                            <span class="badge badge-primary"><?php echo $grade_letter; ?></span>
                                        </td>
                                        <td><?php echo $points; ?></td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <th colspan="4" class="text-right">Total Points / Total Credits:</th>
                                    <th>
                                        <?php echo number_format($total_points_display, 2); ?> / <?php echo $total_credits_display; ?>
                                        = <span class="text-success"><?php echo number_format($gpa, 2); ?></span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                        
                        <div class="alert alert-success text-center">
                          <strong>Final GPA: <?php echo number_format($gpa, 2); ?></strong>
                            <?php 
                            if ($gpa >= 3.7) echo " Excellent";
                            elseif ($gpa >= 3.0) echo " Merit";
                            elseif ($gpa >= 2.0) echo " Pass";
                            else echo " Need Improvement";
                            ?>
                        </div>

                        <!-- Progress bar -->
                        <?php
                        if ($gpa >= 3.7) $color = "bg-success";
                        elseif ($gpa >= 3.0) $color = "bg-info";
                        elseif ($gpa >= 2.0) $color = "bg-warning";
                        else $color = "bg-danger";
                        ?>
                        <div class="progress">
                            <div class="progress-bar <?php echo $color; ?>" style="width: <?php echo ($gpa/4)*100; ?>%">
                                <?php echo number_format($gpa, 2); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"> Previous Results</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Semester</th>
                        <th>GPA</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("SELECT * FROM results ORDER BY id DESC");
                    $counter = 1;
                    
                    if($res->num_rows > 0):
                        while($row = $res->fetch_assoc()):
                            $gpa_value = $row['gpa'];
                            
                            if ($gpa_value >= 3.7):
                                $status = "Excellent ";
                                $badge_class = "success";
                            elseif ($gpa_value >= 3.0):
                                $status = "Very Good ";
                                $badge_class = "info";
                            elseif ($gpa_value >= 2.0):
                                $status = "Good ";
                                $badge_class = "warning";
                            else:
                                $status = "Need Improvement ";
                                $badge_class = "danger";
                            endif;
                    ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['student']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['semester']); ?></td>
                            <td>
                                <span class="badge badge-primary badge-pill px-3 py-2">
                                    <?php echo number_format($gpa_value, 2); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $badge_class; ?> px-3 py-2">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No results found. Please calculate your GPA first.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <form method="post" onsubmit="return confirm(' Are you sure you want to delete ALL results?');">
                <button type="submit" name="delete_all" class="btn btn-danger"> Delete All Results</button>
            </form>
        </div>
    </div>

    <div class="text-center mt-3 mb-5">
        <a href="export.php" class="btn btn-success"> Download CSV</a>
    </div>

    <script>
        function addCourse(){
            let div = document.createElement("div");
            div.className = "form-row mb-2";
            div.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="course[]" class="form-control" placeholder="Course Name" required>
                </div>
                <div class="col-md-3">
                    <input type="number" name="credits[]" class="form-control" placeholder="Credits" step="1" min="1" required>
                </div>
                <div class="col-md-3">
                    <select name="grade[]" class="form-control">
                        <option value="4">A (Excellent)</option>
                        <option value="3">B (Very Good)</option>
                        <option value="2">C (Good)</option>
                        <option value="1">D (Pass)</option>
                        <option value="0">F (Fail)</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">✖</button>
                </div>
            `;
            document.getElementById("courses").appendChild(div);
        }
    </script>

</body>
</html>
