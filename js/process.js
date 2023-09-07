"use strict";

$('#addqual').click(function(e) {
    e.preventDefault();

    var formData = $('#addqual22').serialize();

    $.ajax({
        type: "POST",
        url: "insert2.php",
        data: formData,
        success: function(response) {
            console.log(response);
            // You will get response from your PHP page (what you echo or print)
            if (response == 1) {
                alert("Record added successfully")
                window.location.assign("add-qual.php");
            } else {
                alert(response)
            }

        },
        error: function(error) {
            console.log(error);
        }
    });
});