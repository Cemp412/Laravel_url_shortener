$(document).ready(function() {

    // On submit invite modal form
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

    //fetch roles
    if($("#inviteModalForm #role-select").length > 0) {
        fetchRoles();
        // console.log(localStorage.getItem('auth_token'));
    }

    function fetchRoles() {
        $.ajax({
            url: "/api/roles-list",
            type: 'GET',
            xhrFields: {
                withCredentials: true // Mandatory for sending cookies
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            success: function(response) {
                let dropdown = $("#inviteModalForm #role-select");
                dropdown.empty();
                dropdown.append("<option >-- Select Role --</option>");

                $.each(response.data.roles, function(key, role) {
                     const formattedName = role.name.charAt(0).toUpperCase() + role.name.slice(1);
                    dropdown.append(`<option value='${role.name}'>${formattedName}</option>`);
                });
            },
            error: function(xhr) {
                let errormessage = '';
               if (xhr.responseJSON && xhr.responseJSON.message) {
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
    }
});