$(document).ready(function() {
    //Get the 'email' parameter from the URL
    const urlParams = new URLSearchParams(window.location.search);
    const emailValue = urlParams.get('email');

    if (emailValue) {
        $('input[name="email"]').val(emailValue);
    }

    $("#loginForm").on("submit", function(e) {
        e.preventDefault(); //prevent default form submission
        $('#login-btn').prop('disabled', true); //disabled login button to prevent double-submission

        var form = $(this);
        var url = form.attr("action");
        var method = form.attr("method");
        var formData = form.serialize();
        // console.log(formData);

        // remove existing li inside error message div
        $("#error-div ul li").remove();
        $("#error-div").addClass("d-none");

        $.ajax({
            type: method,
            url:  url,
            data: formData,
            dataType: "json",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")   
            },
            success: (response) => {

                if(response.token !== undefined) {
                    localStorage.setItem('auth_token', response.token);
                }
                //redirect based on response
                window.location.href = response.data.redirect;

                // Swal.fire({
                //     icon: "success",
                //     title: "Success",
                //     text: response.message,
                //     showConfirmButton: false,
                //     //     timer: 100
                // }).then(() => {
                //     //redirect based on response
                //     window.location.href = response.data.redirect;
                // });
            },
            error: (response) => {
                const errorDiv = $("#error-div");
                const errorList = errorDiv.find(".error-list");

                errorList.empty(); // clear previous shown errors
                $('#login-btn').prop('disabled', false); //enable login button
                setTimeout(() => {
                    errorDiv.removeClass("d-none");
                }, 100);

                if (response.responseJSON && response.responseJSON["errors"]) {

                    $.each(response.responseJSON["errors"], function (keys, values) {
                        if (Array.isArray(values)) {
                            $.each(values, function (key, value) {
                                errorList.append("<li>" + value + "</li>");
                            });
                        }
                        else {
                            errorList.append("<li>" + values + "</li>");
                        }

                    });
                }
                else if (response.responseJSON && response.responseJSON.message) {
                    errorList.append("<li>" + response.responseJSON.message + "</li>");
                }
                else {
                    errorList.append("<li>An unexpected error occurred. Please try again.</li>");
                }
            }
        });
    });
});