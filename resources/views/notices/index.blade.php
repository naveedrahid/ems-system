@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Notice
@endsection
@section('page-content')
    <div id="loadingSpinner" style="display: none; text-align: center;">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card data-table small-box">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="header-title">
                                <h4 class="text-bold">Manage Notices</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('notices.create') }}" class="btn btn-success text-bold"
                                        data-toggle="modal" data-target="#noticeModal" data-type="add">
                                        Add <i class="fas fa-plus" style="font-size: 13px;"></i>
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="15%">Title</th>
                                <th width="15%">Notice Type</th>
                                <th width="15%">Department Name</th>
                                <th width="20%">Description</th>
                                <th width="12%">Status</th>
                                <th width="18%">Manage</th>
                            </tr>
                        </thead>
                        <tbody id="noticeTableData">
                        </tbody>
                    </table>
                    <ul id="pagination"></ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Notice Modal --}}
    <div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Notice Form</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div id="formContainer">
                        <div id="loadingSpinner2" style="display: none; text-align: center;">
                            <i class="fas fa-spinner fa-spin fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection

@push('css')
<style>
    #designationWrapper ul#pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 15px 0px;
    }

    .modal-dialog {
        max-width: 600px !important;
        margin: 2.75rem auto !important;
        border-radius: 20px !important;

        .modal-title {
            font-size: 18px !important;
            font-weight: 600 !important;
        }

        .modal-content {
            border-radius: 20px;
            box-shadow: 0 .1rem .5rem rgba(0, 0, 0, .5);
        }

        label {
            font-weight: 600 !important;
            font-size: 15px;
        }

        .modal-header {
            padding-top: 0;
            padding-left: 7px;
            border: none;
        }

        .form-control {
            height: calc(2.25rem + 0px);
            font-size: 15px;
            border-radius: 10px;

            &:focus {
                border: 1.5px solid #80BDFF;
            }
        }

        .btn-close {
            border: 0;
            background: none;
        }

        .btn-primary {
            font-weight: 600;
            border-radius: 10px;
        }

        .col-md-6 {
            padding-left: 0;
        }
    }

    .profile-box .profile-img {
        border-top-left-radius: 15px;
        border-bottom-left-radius: 15px;
    }

    div#loadingSpinner,
    div#loadingSpinner2 {
        position: fixed;
        left: 0;
        right: 0;
        margin: auto;
        top: 0;
        bottom: 0;
        z-index: 99;
        background: #00000036;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index:99999;
    }

    div#loadingSpinner i,
    div#loadingSpinner2 i {
        color: #007bff;
    }
</style>
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.1.1/tinymce.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {

        function initializeTinyMCE() {
            if (typeof tinymce !== 'undefined') {
                if (Array.isArray(tinymce.editors)) {
                    tinymce.editors.forEach(editor => editor.remove());
                }

                tinymce.init({
                    selector: '.txtArea',
                    branding: false,
                    plugins: 'code table lists',
                    menubar: false,
                    statusbar: false,
                    toolbar: 'bold italic underline | fontsizeselect | forecolor | bullist numlist | alignleft aligncenter alignright | link | blocks',
                });
            } else {
                console.error("TinyMCE is not loaded.");
            }
        }

        function loadForm(url, container, formId) {
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $(container).html(data);
                    $(container).trigger('form.loaded');
                },
                error: function(xhr) {
                    console.error("Error loading form: ", xhr);
                }
            });
        }

        $('a[data-type="add"]').on('click', function(e) {
            e.preventDefault();
            const url = '{{ route('notices.create') }}';
            const container = '#formContainer';
            const formId = 'noticeSoter';
            loadForm(url, container, formId);
        });

        $(document).on('click', '.edit-notices', function(e) {
            e.preventDefault();
            const noticeId = $(this).data('id');
            const url = `/notices/${noticeId}/edit`;
            const container = '#formContainer';
            const formId = 'noticeUpdate';
            loadForm(url, container, formId);
        });

        $('#formContainer').on('form.loaded', function() {
            initializeTinyMCE();
        });

        function fetchNoticeData() {
            const endPoint = "{{ route('notices.data') }}";
            const targetTable = $('#noticeTableData');
            const targetPagination = $('#pagination');

            const htmlRowCallback = (notices) => `
                                    <tr>
                                        <td>${notices.name}</td>
                                        <td>${notices.notice_type}</td>
                                        <td>
                                            ${notices.department_id == 0 ? 'All' : (notices.department ? notices.department.department_name : '')}
                                        </td>
                                        <td>${notices.description}</td>
                                        <td>
                                            <div class="manage-process">
                                                <a style="width:70px;text-align:right;" href="#" class="notice-toggle" data-id="${notices.id}" data-status="${notices.status}">
                                                    <span class="badges ${notices.status === 'active' ? 'active-badge' : 'deactive-badge'}">
                                                        ${notices.status}
                                                    </span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="manage-process">
                                                <a href="#" class="edit-notices edit-item" data-toggle="modal" data-target="#noticeModal" data-id="${notices.id}"><i class="fa fa-edit"></i> Edit</a>
                                                <a href="#">
                                                    <div class="delete-item delete-notice" data-notice-id="${notices.id}" data-delete-route="notices/${notices.id}">
                                                        <i class="far fa-trash-alt"></i> Delete
                                                    </div>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>`;

            fetchDataGlobal(1, endPoint, targetTable, targetPagination, htmlRowCallback);
            initializePaginationClickHandler(endPoint, targetTable, targetPagination, htmlRowCallback);
        }

        fetchNoticeData();

        $(document).on('click', '.delete-notice', function(e) {
            e.preventDefault();

            const noticeId = $(this).data('notice-id');
            const deleteRoute = $(this).data('delete-route').replace(':id', noticeId);
            const $clickedElement = $(this);

            if (confirm('Are you sure you want to delete this Notice?')) {
                const token = $('meta[name="csrf-token"]').attr('content');
                $('#loadingSpinner').show();

                $.ajax({
                    type: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(response) {
                    fetchNoticeData();
                    toastr.success(response.message);
                    $clickedElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('backgroundColor', 'red').remove();
                    });
                    $('#loadingSpinner').hide();
                }).catch(function(xhr) {
                    $('#loadingSpinner').hide();
                    console.error(xhr);
                    toastr.error('Failed to Delete Notice');
                });
            }

        });

        $(document).on('click', '.notice-toggle', function(e) {
            e.preventDefault();

            const $this = $(this);
            const noticeId = $this.data('id');
            const currentStatus = $this.data('status');
            const newStatus = currentStatus === 'active' ? 'deactive' : 'active';
            const token = $('meta[name="csrf-token"]').attr('content');
            $('#loadingSpinner').show();

            $.ajax({
                    url: `/notices/notices-status/${noticeId}`,
                    method: 'PUT',
                    data: {
                        _token: token,
                        id: noticeId,
                        status: newStatus
                    },
                })
                .then((response) => {
                    if (response.success) {
                        setTimeout(() => {
                            $('#loadingSpinner').hide();
                            toastr.success('Status updated successfully!');
                        }, 1000);

                        $this.data('status', newStatus);
                        $this.find('span').text(newStatus.charAt(0).toUpperCase() + newStatus.slice(
                            1));
                        $this.find('span').attr('class', 'badges ' + (newStatus === 'active' ?
                            'active-badge' : 'deactive-badge'));
                    }
                }).catch((err) => {
                    console.error(err);
                    toastr.error('Failed to update status. Please try again.');
                });
        });

        $(document).on('submit', '#noticeSoter, #noticeUpdate', function(e) {
            e.preventDefault();

            const name = $('input[name="name"]').val().trim();
            const noticeType = $('select[name="notice_type"]').val().trim();
            const departmentId = $('select[name="department_id"]').val().trim();
            const description = $('textarea[name="description"]').val().trim();

            if (name === '' || noticeType === '' || departmentId === '' || description === '') {
                if (name === '') {
                    toastr.error('Title is required.');
                }
                if (noticeType === '') {
                    toastr.error('Notice Type is required.');
                }
                if (departmentId === '') {
                    toastr.error('Department is required.');
                }
                if (description === '') {
                    toastr.error('Description is required.');
                }
                return;
            }

            const formData = new FormData(this);
            const url = $(this).attr('action');
            const token = $('meta[name="csrf-token"]').attr('content');
            const button = $('input[type="submit"]');
            button.prop('disabled', true);
            $('#loadingSpinner').show();

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
                .then(function(response) {
                    fetchNoticeData();
                    toastr.success(response.message);
                    button.prop('disabled', false);
                    if ($(e.target).attr('id') === 'noticeSoter') {
                        $('#noticeSoter')[0].reset();
                    }
                    $('#loadingSpinner').hide();
                    $('#noticeModal').modal('hide');
                })
                .catch(function(xhr) {
                    console.error(xhr);
                    $('#loadingSpinner').hide();
                    toastr.error(response.message);
                    button.prop('disabled', false);
                    $('#noticeModal').modal('hide');
                });
        });
    });
</script>
@endpush
