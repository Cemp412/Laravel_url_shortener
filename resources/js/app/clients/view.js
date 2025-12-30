$(document).ready(function() {
    /* $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + localStorage.getItem('auth_token'), //post successful login requests
            'Accept': 'application/json'
        }
    }); */

    $("#inviteModalForm").on("submit", function(e) {
        e.preventDefault();
        $('#sendInvite-btn').prop('disabled', true); //disabled login button to prevent double-submission

        var form = $(this);
        var url = form.attr("action");
        var method = form.attr("method");
        var formData = form.serialize();

        $.ajax({
            type: method,
            url:  url,
            data: formData,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            success: (response) => {
                console.log(response.status);
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                    showConfirmButton: true,
                    //timer: 100
                })
                /* .then(() => {
                    //redirect based on response
                    window.location.href = response.data.redirect;
                }); */
            },
            error: (response) => {
                let errormessage = '';

                $('#sendInvite-btn').prop('disabled', false); //enable submit button

                if (response.responseJSON && response.responseJSON["errors"]) {
                    $.each(response.responseJSON["errors"], function (keys, values) {
                        if (Array.isArray(values)) {
                            $.each(values, function (key, value) {
                                errormessage += value + "<br>";
                            });
                        } else {
                            errormessage += values + "<br>";
                        }
                    });
                } 
                else if (response.responseJSON && response.responseJSON.message) {
                    errormessage = response.responseJSON.message;
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