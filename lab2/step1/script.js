function addCourse(){

var row = document.createElement("div");
row.className="course-row";

row.innerHTML =
'<label>Course:</label>'+
'<input type="text" name="course[]" required>'+

'<label>Credits:</label>'+
'<input type="number" name="credits[]" min="1" required>'+

'<label>Grade:</label>'+
'<select name="grade[]">'+
'<option value="4">A</option>'+
'<option value="3">B</option>'+
'<option value="2">C</option>'+
'<option value="1">D</option>'+
'<option value="0">F</option>'+
'</select>'+

'<button type="button" onclick="this.parentNode.remove()">Remove</button>';

document.getElementById("courses").appendChild(row);
}


function validateForm(){

var courses=document.querySelectorAll('[name="course[]"]');
var credits=document.querySelectorAll('[name="credits[]"]');

for(var i=0;i<courses.length;i++){

if(courses[i].value==""){
alert("All course names are required");
return false;

}

}

for(var j=0;j<credits.length;j++){

if(isNaN(credits[j].value) || credits[j].value<=0){

alert("Credits must be positive numbers");
return false;

}

}

return true;

}
