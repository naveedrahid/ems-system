$(document).ready(function () {

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

});