@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Documents
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
                                <h4 class="text-bold">All Documents</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('documents.create') }}" class="btn btn-success text-bold">
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
                                <th width="10%">Date</th>
                                <th width="20%">Name</th>
                                <th width="10%">NIC Front</th>
                                <th width="10%">NIC Back</th>
                                <th width="10%">Resume</th>
                                <th width="10%">Payslip</th>
                                <th width="10%">Experience Letter</th>
                                <th width="10%">Bill</th>
                                <th width="10%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documentUsers as $documentUser)
                                @php
                                    $experienceLetterPath = $documentUser->experience_letter;
                                    $resumePath = $documentUser->resume;

                                    $experienceLetterExtension = pathinfo($experienceLetterPath, PATHINFO_EXTENSION);
                                    $resumeExtension = pathinfo($resumePath, PATHINFO_EXTENSION);
                                @endphp
                                <tr>
                                    <td>{{ $documentUser->created_at->toFormattedDateString() }}</td>
                                    <td>{{ $documentUser->user->name }}</td>
                                    <td><img src="{{ asset($documentUser->nic_front) }}" width="100" height="100"
                                            class="img-fluid" alt=""></td>
                                    <td><img src="{{ asset($documentUser->nic_back) }}" width="100" height="100"
                                            class="img-fluid" alt=""></td>
                                    <td>
                                        @if (in_array($resumeExtension, ['pdf']))
                                            <iframe src="{{ asset($documentUser->resume) }}" width="100" height="100"
                                                style="border: none;"></iframe>
                                        @elseif(in_array($resumeExtension, ['doc', 'docx']))
                                            <a href="{{ asset($documentUser->resume) }}"
                                                download="{{ basename($documentUser->resume) }}">
                                                Download
                                            </a>
                                        @endif
                                    </td>
                                    <td><img src="{{ asset($documentUser->payslip) }}" width="100" height="100"
                                            class="img-fluid" alt=""></td>
                                    <td>
                                        @if (in_array($experienceLetterExtension, ['pdf']))
                                            <iframe src="{{ asset($documentUser->experience_letter) }}" width="100"
                                                height="100" style="border: none;"></iframe>
                                        @elseif(in_array($experienceLetterExtension, ['doc', 'docx']))
                                            <div class="manage-process">
                                                <a href="{{ asset($documentUser->experience_letter) }}" class="edit-item"
                                                    download="{{ basename($documentUser->experience_letter) }}">
                                                    Download
                                                </a>
                                            </div>
                                        @endif

                                    </td>
                                    <td><img src="{{ asset($documentUser->bill) }}" width="100" height="100"
                                            class="img-fluid" alt=""></td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="{{ route('documents.edit', $documentUser) }}">
                                                <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                            </a>
                                            <a href="#">
                                                <div class="delete-item delete-document"
                                                    data-document-id="{{ $documentUser->id }}"
                                                    data-delete-route="{{ route('documents.destroy', $documentUser->id) }}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tfoot>
                                    <tr>
                                        <td colspan="9">
                                            {{ $documentUsers->links('pagination::bootstrap-4') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @empty
                                <tbody>
                                    <tr>
                                        <td colspan="9" class="text-center">No record Found!</td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@endsection

@push('css')
<style>
    div#loadingSpinner {
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
    }

    div#loadingSpinner i {
        color: #007bff;
    }
</style>
@endpush
@push('js')
<script>
    $(document).ready(function() {
        $('.delete-document').on('click', function(e) {
            e.preventDefault();
            const documentID = $(this).data('document-id');
            const deleteRoute = $(this).data('delete-route');
            const targetElement = $(this);
            const button = $(this);
            button.prop('disabled', true);

            if (confirm('Are you sure you want to delete this Document?')) {

                $('#loadingSpinner').show();
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    method: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(result) {
                    setTimeout(() => {
                        $('#loadingSpinner').hide();
                        toastr.success(result.message);
                        targetElement.closest('tr').fadeOut('slow', function() {
                            $(this).css('background', 'red');
                            $(this).remove();
                        });
                    }, 1000);
                    button.prop('disabled', false);
                }).catch(function(err) {
                    console.log(err);
                    button.prop('disabled', false);
                    $('#loadingSpinner').hide();
                    toastr.error('Failed to delete Document');
                });
            }
        });
    });
</script>
@endpush
