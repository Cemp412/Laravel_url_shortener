$(document).ready(function() {

    $("#inviteModalForm").on("submit", function(e) {
        e.preventDefault();
        $('#sendInvite-btn').prop('disabled', true); //disabled button to prevent double-submission

        var form = $(this);
        var url = form.attr("action");
        var method = form.attr("method");
        var formData = form.serialize();

        $.ajax({
            url:  url,
            type: method,
            data: formData,
            dataType: "json",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            success: (response) => {
                $('#inviteModal').modal('hide');
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                    showConfirmButton: true,
                    //timer: 100
                })
                .then(() => {
                    //redirect based on response
                    window.location.reload();
                });
            },
            error: (xhr) => {
                let errormessage = '';

                $('#sendInvite-btn').prop('disabled', false); //enable submit button

                if (xhr.responseJSON && xhr.responseJSON["errors"]) {
                    $.each(xhr.responseJSON["errors"], function (keys, values) {
                        if (Array.isArray(values)) {
                            $.each(values, function (key, value) {
                                errormessage += value + "<br>";
                            });
                        } else {
                            errormessage += values + "<br>";
                        }
                    });
                } 
                else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errormessage = xhr.responseJSON.message;
                } 
                else {
                    errormessage = "An unexpected error occurred. Please try again.";
                }
                Swal.fire({
                    title: 'Error!',
                    html: errormessage,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33' 
                });
            }
        });
    });
});