$(document).ready(function () {

    // إضافة مادة
    $("#addCourse").click(function () {
        let row = $(".course-row").first().clone();
        row.find("input").val("");

        row.append(`
            <div class="col-auto">
                <button type="button" class="btn btn-danger remove-row">X</button>
            </div>
        `);

        $("#courses").append(row);
    });

    // حذف مادة
    $(document).on("click", ".remove-row", function () {
        if ($(".course-row").length > 1) {
            $(this).closest(".course-row").remove();
        }
    });

    // إرسال بدون reload
    $("#gpaForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "calculate.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",

            success: function (res) {
                let alertClass = "alert-info";

                if (res.gpa >= 3.7) alertClass = "alert-success";
                else if (res.gpa >= 3.0) alertClass = "alert-info";
                else if (res.gpa >= 2.0) alertClass = "alert-warning";
                else alertClass = "alert-danger";

                $("#result").html(`
                    <div class="alert ${alertClass}">
                        ${res.message}
                    </div>
                    ${res.tableHtml}
                `);
            },

            error: function () {
                $("#result").html(`
                    <div class="alert alert-danger">
                        Server error
                    </div>
                `);
            }
        });
    });

});
