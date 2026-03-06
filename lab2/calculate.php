<?php

if(isset($_POST['course'],$_POST['credits'],$_POST['grade'])){

$courses=$_POST['course'];
$credits=$_POST['credits'];
$grades=$_POST['grade'];

$totalPoints=0;
$totalCredits=0;

echo "<table>";

echo "<tr>
<th>Course</th>
<th>Credits</th>
<th>Grade</th>
<th>Points</th>
</tr>";

for($i=0;$i<count($courses);$i++){

$course=$courses[$i];
$cr=floatval($credits[$i]);
$g=floatval($grades[$i]);

$points=$cr*$g;

$totalPoints+=$points;
$totalCredits+=$cr;

echo "<tr>
<td>$course</td>
<td>$cr</td>
<td>$g</td>
<td>$points</td>
</tr>";

}

echo "</table>";

$gpa=$totalPoints/$totalCredits;

echo "<h2>Your GPA is ".number_format($gpa,2)."</h2>";

}
?>
