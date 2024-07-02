$(document).ready(function () {
    function modalCallBack() {
        $('.modal-toggle').on('click', function (e) {
            e.preventDefault();
            $('.modal').toggleClass('is-visible');
        });
    }
    modalCallBack();


    // Destroy Designation



    // Add Users



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


    // update status Employee

    // $('#department_id').change(function () {
    //     const departmentId = $(this).val();
    //     if (departmentId) {
    //         $.ajax({
    //             url: '/get-designations/' + departmentId,
    //             type: 'GET',
    //             success: function (data) {
    //                 $('#designation_id').empty();
    //                 $('#designation_id').append('<option value="">Select Designation</option>');
    //                 $.each(data, function (key, value) {
    //                     $('#designation_id').append('<option value="' + key + '">' + value + '</option>');
    //                 });
    //             }
    //         });
    //     } else {
    //         $('#designation_id').empty();
    //         $('#designation_id').append('<option value="">Select Designation</option>');
    //     }
    // });


});