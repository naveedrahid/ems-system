@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Documents
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('documents.create') }}" class="btn btn-primary">
                    Insert Documents
                </a>
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #fff;">
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
                @if ($documentUsers->count() > 0)
                    <tbody style="background: #fff;">
                        @foreach ($documentUsers as $documentUser)
                            <tr>
                                <td>{{ $documentUser->created_at->toFormattedDateString() }}</td>
                                <td>{{ $documentUser->user->name }}</td>
                                <td><img src="{{ asset($documentUser->nic_front) }}" width="100" height="100" class="img-fluid" alt=""></td>
                                <td><img src="{{ asset($documentUser->nic_back) }}" width="100" height="100" class="img-fluid" alt=""></td>
                                <td>
                                    <iframe src="{{ asset($documentUser->resume) }}" width="100" height="100" style="border: none;"></iframe>
                                </td>                                
                                <td><img src="{{ asset($documentUser->payslip) }}" width="100" height="100" class="img-fluid" alt=""></td>
                                <td>
                                    <iframe src="{{ asset($documentUser->experience_letter) }}" width="100" height="100" style="border: none;"></iframe>
                                </td> 
                                <td><img src="{{ asset($documentUser->bill) }}" width="100" height="100" class="img-fluid" alt=""></td>
                                <td>
                                    <a href="{{ route('documents.edit', $documentUser) }}" class="btn btn-info btn-flat btn-sm">
                                        <i class="fa fa-edit"></i></a>

                                    <button class="delete-document btn btn-danger btn-flat btn-sm"
                                        data-document-id="{{ $documentUser->id }}"
                                        data-delete-route="{{ route('documents.destroy', $documentUser->id) }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9">
                                {{ $documentUsers->links('pagination::bootstrap-4') }}
                            </td>
                        </tr>
                    </tfoot>
                @else
                    <tbody style="background: #fff;">
                        <tr>
                            <td colspan="9">No record Found!</td>
                        </tr>
                    </tbody>
                @endif
            </table>
        </div>
    </div>
@endsection
@endsection
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
                const token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    method: "DELETE",
                    url: deleteRoute,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(function(result) {
                    toastr.success(result.message);
                    button.prop('disabled', false);
                    targetElement.closest('tr').fadeOut('slow', function() {
                        $(this).css('background', 'red');
                        $(this).remove();
                    });
                }).catch(function(err) {
                    console.log(err);
                    button.prop('disabled', false);
                    toastr.error('Failed to delete Document');
                });
            }
        });
    });
</script>
@endpush
