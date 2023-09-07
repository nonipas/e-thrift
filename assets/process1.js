"use strict";

$('#addqual').click(function(e) {
    e.preventDefault();

    var formData = $('form').serialize();

    $.ajax({
        type: "POST",
        url: "insert2.php",
        data: FormData,
        success: function(response) {
            // You will get response from your PHP page (what you echo or print)
            if (response['success']) {
                swal.fire({
                    "title": "Success",
                    "text": response['message'],
                    "type": "success",
                    "confirmButtonClass": "btn btn-secondary"
                });
                window.location.assign("add-qual.php");
            } else {
                swal.fire({
                    "title": "Error",
                    "text": response['message'],
                    "type": "warning",
                    "confirmButtonClass": "btn btn-secondary"
                });
            }

        },
        error: function(error) {
            console.log(error);
        }
    });
});