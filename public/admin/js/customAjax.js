$(document).ready(function () {
    function modalCallBack() {
        $('.modal-toggle').on('click', function (e) {
            e.preventDefault();
            $('.modal').toggleClass('is-visible');
        });
    }
    modalCallBack();
    // add Role Request

    $('#addRoleForm').submit(function (e) {
        e.preventDefault();
        const roleName = $('#add_role').val().trim();
        if (roleName === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Role name cannot be empty.',
            });
            return;
        }

        const formData = new FormData(this);
        const url = $(this).attr('action');
        const token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': token
            }
        })
            .then(function (response) {
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                });
                $('#addRoleForm')[0].reset();
                $('#add_role').val('');
            })
            .catch(function (xhr) {
                console.error(xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to create role.',
                });
            });
    });


    // post check in request

    $('#checkin').submit(function (e) {
        e.preventDefault();
        const url = $(this).attr('action');
        const token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: token,
                user_id: '{{ Auth::id() }}',
            },
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function (response) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Check in successfully"
                });
                $('.checkinBtn').addClass('checkinActive');
                setTimeout(function () {
                    window.location.reload();
                }, 2500);
            },
            error: function (xhr) {
                console.error(xhr);
            }
        });
    });

    // post check out request

    $('#checkOut').submit(function (e) {
        e.preventDefault();
        const url = $(this).attr('action');
        const token = $('meta[name="csrf-token"]').attr('content');
        const alreadyCheckedOut = $(this).data('already-checked-out');

        if (confirm("Are you sure you want to check out?")) {
            if (alreadyCheckedOut === 'true') {
                Swal.fire({
                    icon: 'error',
                    title: 'Already Checked Out',
                    text: 'You have already checked out.',
                });
                return;
            }

            const now = new Date();
            const hours = 17 - now.getHours();
            const minutes = 60 - now.getMinutes();
            const remainingTime = hours + " hours and " + minutes + " minutes";

            if (hours <= 0 && minutes <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Already Past Office Closing Time',
                    text: 'It\'s already past office closing time. You cannot check out early.',
                });
                return;
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: token,
                    user_id: userId,
                },
                headers: {
                    'X-CSRF-TOKEN': token
                },
                success: function (response) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Check out successfully"
                    });
                    setTimeout(function () {
                        window.location.reload();
                    }, 2500);
                },
                error: function (xhr) {
                    console.error(xhr);
                }
            });
        }
    });


    // Destroy Designation

    $('.delete-designation').on('click', function (e) {
        e.preventDefault();
        const leaveType = $(this).data('designation-id');
        const deleteRoute = $(this).data('delete-route').replace(':id', leaveType);
        const $clickedElement = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this Leave Type!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function (response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    });
                    $clickedElement.closest('tr').fadeOut('slow', function () {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function (xhr) {
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to delete Leave Type.',
                    });
                });
            }
        });
    });

    // Update Status Designation

    $('.status-toggle').click(function () {
        const button = $(this);
        const id = button.data('id');
        const status = button.data('status');
        const newStatus = status === 'active' ? 'deactive' : 'active';
        const statusIcon = status === 'active' ? 'down' : 'up';
        const btnClass = status === 'active' ? 'danger' : 'info';

        $.ajax({
            url: '/update-status/' + id,
            method: 'PUT',
            data: { status: newStatus },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                button.removeClass('btn-' + (status === 'active' ? 'info' : 'danger')).addClass('btn-' + btnClass);
                button.find('i').removeClass('fa-thumbs-' + (status === 'active' ? 'up' : 'down')).addClass('fa-thumbs-' + statusIcon);
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Status " + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + " successfully"
                });
                button.data('status', newStatus);
            },
            error: function (xhr) {
                console.error(xhr);
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "error",
                    title: "Failed to update status"
                });
            }
        });
    });

    // Add Users

    $('#addUsers').submit(function (e) {
        e.preventDefault();

        const user_name = $('input[name="user_name"]').val().trim();
        const fater_name = $('input[name="fater_name"]').val().trim();
        const user_email = $('input[name="user_email"]').val().trim();
        const city = $('input[name="city"]').val().trim();
        const phone_number = $('input[name="phone_number"]').val().trim();
        const emergency_phone_number = $('input[name="emergency_phone_number"]').val().trim();
        const emergency_person_name = $('input[name="emergency_person_name"]').val().trim();
        const gender = $('select[name="gender"]').val().trim();
        const date_of_birth = $('input[name="date_of_birth"]').val().trim();
        const joining_date = $('input[name="joining_date"]').val().trim();
        const address = $('input[name="address"]').val().trim();
        const user_role = $('select[name="user_role"]').val().trim();
        const status = $('select[name="status"]').val().trim();

        emergency_person_name
        if (user_name == '' || fater_name == '' || user_email == '' || city == '' || phone_number == '' || emergency_phone_number == '' || emergency_person_name == '' || gender == '' || date_of_birth == '' || joining_date == '' || address == '' || user_role == '' || status == '') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'All Fields cannot be empty.',
            });
            return;
        }

        const formData = new FormData(this);
        const url = $(this).attr('action');
        const token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': token
            }
        })
            .then(function (response) {
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                });
                $('#addUsers')[0].reset();
                $('#user_name').val('');
                $('#fater_name').val('');
                $('#user_email').val('');
                $('#city').val('');
                $('#phone_number').val('');
                $('#emergency_phone_number').val('');
                $('#emergency_person_name').val('');
                $('#user_role').val('');
                $('#gender').val('');
                $('#date_of_birth').val('');
                $('#joining_date').val('');
                $('#address').val('');
                $('#status').val('');
                // window.location.reload(); // or redirect to a different page
            })
            .catch(function (xhr) {
                console.error(xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to create Designation.',
                });
            });
    });

    // Update User

    $('#UpdateUser').submit(function (e) {
        e.preventDefault();

        const user_name = $('input[name="user_name"]').val().trim();
        const fater_name = $('input[name="fater_name"]').val().trim();
        const user_email = $('input[name="user_email"]').val().trim();
        const city = $('input[name="city"]').val().trim();
        const phone_number = $('input[name="phone_number"]').val().trim();
        const emergency_phone_number = $('input[name="emergency_phone_number"]').val().trim();
        const emergency_person_name = $('input[name="emergency_person_name"]').val().trim();
        const gender = $('select[name="gender"]').val().trim();
        const date_of_birth = $('input[name="date_of_birth"]').val().trim();
        const joining_date = $('input[name="joining_date"]').val().trim();
        const address = $('input[name="address"]').val().trim();
        const user_role = $('select[name="user_role"]').val().trim();
        const status = $('select[name="status"]').val().trim();

        if (emergency_person_name == '' || user_name == '' || fater_name == '' || user_email == '' || city == '' || phone_number == '' || emergency_phone_number == '' || emergency_person_name == '' || gender == '' || date_of_birth == '' || joining_date == '' || address == '' ||  user_role == '' || status == '') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'All Fields cannot be empty.',
            });
            return;
        }

        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        const url = $(this).attr('action');
        const token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': token
            }
        })
            .then(function (response) {
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                });
                // $('#addUsers')[0].reset();
            })
            .catch(function (xhr) {
                console.error(xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to create Designation.',
                });
            });
    });

    // user status update

    $('.user-toggle').click(function () {
        const button = $(this);
        const id = button.data('id');
        const status = button.data('status');
        const newStatus = status === 'active' ? 'deactive' : 'active';
        const statusIcon = status === 'active' ? 'down' : 'up';
        const btnClass = status === 'active' ? 'danger' : 'info';

        $.ajax({
            url: '/user-status/' + id,
            method: 'PUT',
            data: { status: newStatus },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                button.removeClass('btn-' + (status === 'active' ? 'info' : 'danger')).addClass('btn-' + btnClass);
                button.find('i').removeClass('fa-thumbs-' + (status === 'active' ? 'up' : 'down')).addClass('fa-thumbs-' + statusIcon);
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: "Status " + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + " successfully"
                });
                button.data('status', newStatus);
            },
            error: function (xhr) {
                console.error(xhr);
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "error",
                    title: "Failed to update status"
                });
            }
        });
    });

    // Add Employee


    // Update Employee

    $('#UpdateEmployee').submit(function (e) {
        e.preventDefault();

        const user_name = $('input[name="user_name"]').val().trim();
        const fater_name = $('input[name="fater_name"]').val().trim();
        const user_email = $('input[name="user_email"]').val().trim();
        const city = $('input[name="city"]').val().trim();
        const phone_number = $('input[name="phone_number"]').val().trim();
        const emergency_phone_number = $('input[name="emergency_phone_number"]').val().trim();
        const emergency_person_name = $('input[name="emergency_person_name"]').val().trim();
        // const employee_img = $('#employee_img')[0].files[0];
        const gender = $('select[name="gender"]').val().trim();
        const date_of_birth = $('input[name="date_of_birth"]').val().trim();
        const joining_date = $('input[name="joining_date"]').val().trim();
        const address = $('input[name="address"]').val().trim();
        const user_role = $('select[name="user_role"]').val().trim();
        const status = $('select[name="status"]').val().trim();


        if (emergency_person_name == '' || user_name == '' || fater_name == '' || user_email == '' || city == '' || phone_number == '' || emergency_phone_number == '' || emergency_person_name == '' || gender == '' || date_of_birth == '' || joining_date == '' || address == '' || user_role == '' || status == '') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'All Fields cannot be empty.',
            });
            return;
        }

        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        const url = $(this).attr('action');
        const token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': token
            }
        })
            .then(function (response) {
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                });
                // $('#addUsers')[0].reset();
            })
            .catch(function (xhr) {
                console.error(xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to create Designation.',
                });
            });
    });

    // update status Employee



    $('#department_id').change(function () {
        const departmentId = $(this).val();
        if (departmentId) {
            $.ajax({
                url: '/get-designations/' + departmentId,
                type: 'GET',
                success: function (data) {
                    $('#designation_id').empty();
                    $('#designation_id').append('<option value="">Select Designation</option>');
                    $.each(data, function (key, value) {
                        $('#designation_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        } else {
            $('#designation_id').empty();
            $('#designation_id').append('<option value="">Select Designation</option>');
        }
    });

    // add Leave Type

    // $('.leave-toggle').click(function () {
    //     const button = $(this);
    //     const id = button.data('id');
    //     const status = button.data('status');
    //     const newStatus = status === 'active' ? 'deactive' : 'active';
    //     const statusIcon = status === 'active' ? 'down' : 'up';
    //     const btnClass = status === 'active' ? 'danger' : 'info';
    //     const loader = button.find('img');

    //     $.ajax({
    //         url: '/leave-types/' + id + '/status',
    //         method: 'PUT',
    //         data: { status: newStatus },
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function (response) {
    //             button.removeClass('btn-' + (status === 'active' ? 'info' : 'danger')).addClass('btn-' + btnClass);
    //             button.find('i').removeClass('fa-thumbs-' + (status === 'active' ? 'up' : 'down')).addClass('fa-thumbs-' + statusIcon);
    //             button.data('status', newStatus);
    //             loader.css('display', 'block');
    //             const Toast = Swal.mixin({
    //                 toast: true,
    //                 position: "top-end",
    //                 showConfirmButton: false,
    //                 timer: 3000,
    //                 timerProgressBar: true,
    //                 didOpen: (toast) => {
    //                     toast.onmouseenter = Swal.stopTimer;
    //                     toast.onmouseleave = Swal.resumeTimer;
    //                 },
    //                 willClose: () => {
    //                     loader.css('display', 'none');
    //                 }
    //             });
    //             Toast.fire({
    //                 icon: "success",
    //                 title: "Status " + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + " successfully"
    //             });
    //         },
    //         error: function (xhr) {
    //             console.error(xhr);
    //             const Toast = Swal.mixin({
    //                 toast: true,
    //                 position: "top-end",
    //                 showConfirmButton: false,
    //                 timer: 3000,
    //                 timerProgressBar: true,
    //                 didOpen: (toast) => {
    //                     toast.onmouseenter = Swal.stopTimer;
    //                     toast.onmouseleave = Swal.resumeTimer;
    //                 },
    //                 willClose: () => {
    //                     loader.css('opacity', '0');
    //                 }
    //             });
    //             Toast.fire({
    //                 icon: "error",
    //                 title: "Failed to update status"
    //             });
    //         }
    //     });
    // });

    // Destroy Leave Type

    $('.delete-leave-type').on('click', function (e) {
        e.preventDefault();
        const designationId = $(this).data('leave-type-id');
        const deleteRoute = $(this).data('delete-route').replace(':id', designationId);
        const $clickedElement = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this designation!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function (response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    });
                    $clickedElement.closest('tr').fadeOut('slow', function () {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function (xhr) {
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to delete Department.',
                    });
                });
            }
        });
    });

    $('.delete-leave-application').on('click', function (e) {
        e.preventDefault();
        const leaveAppId = $(this).data('leave-app-id');
        const deleteRoute = $(this).data('delete-route').replace(':id', leaveAppId);
        const $clickedElement = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this Leave Application!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function (response) {
                    console.log(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                    });
                    $clickedElement.closest('tr').fadeOut('slow', function () {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                }).catch(function (xhr) {
                    console.error(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to delete Department.',
                    });
                });
            }
        });
    });

});