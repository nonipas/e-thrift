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
                swal.fire({
                    title: "Success!",
                    text: "New Qualification Created Successfully",
                    icon: "success",
                    showCancelButton: !0,
                    confirmButtonColor: "#556ee6",
                    cancelButtonColor: "#f46a6a"
                });
                setTimeout(function() {
                    window.location.assign("add-qual.php");
                }, 2000);
            } else {
                alert(response)
            }

        },
        error: function(error) {
            console.log(error);
        }
    });
});