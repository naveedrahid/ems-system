@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Awards
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
                                <h4 class="text-bold">All Awards</h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="box-header pl-1">
                                <h3 class="box-title">
                                    <a href="{{ route('awards.create') }}" class="btn btn-success text-bold">
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
                                <th width="20%">Date</th>
                                <th width="15%">Employee Name</th>
                                <th width="15%">Award Name</th>
                                <th width="20%">Image</th>
                                <th width="20%">Description</th>
                                <th width="10%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($awards as $award)
                                <tr>
                                    <td>{{ $award->created_at->toFormattedDateString() }}</td>
                                    <td>{{ $award->user->name }}</td>
                                    <td>{{ $award->award_name }}</td>
                                    <td>
                                        @php
                                            $awardFilePath =
                                                str_replace('public/', 'storage/', $award->award_file) ?? '';
                                        @endphp
                                        @if ($awardFilePath)
                                            <img src="{{ asset($awardFilePath) }}" alt="Award Image" width="70"
                                                height="70">
                                        @endif
                                    </td>
                                    <td>{!! $award->description !!}</td>
                                    <td>
                                        <div class="manage-process">
                                            <a href="{{ route('awards.edit', $award) }}">
                                                <div class="edit-item"><i class="fas fa-edit"></i> Edit</div>
                                            </a>
                                            <a href="#">
                                                <div class="delete-item delete-award"
                                                    data-award-id="{{ $award->id }}"
                                                    data-delete-route="{{ route('awards.destroy', ':id') }}">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="9">Record Not Found!</td>
                                </tr>
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
        $('.delete-award').click(function(e) {
            e.preventDefault();

            const awardId = $(this).data('award-id');
            const url = $(this).data('delete-route').replace(':id', awardId);
            const token = $('meta[name="csrf-token"]').attr('content');
            const targetElement = $(this);

            if (confirm('Are you sure you want to delete this Award?')) {
                $('#loadingSpinner').show();

                $.ajax({
                        method: "DELETE",
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    })
                    .then((response) => {
                        setTimeout(() => {
                            $('#loadingSpinner').hide();
                            toastr.success(response.message);
                        }, 1000);
                        targetElement.closest('tr').fadeOut('slow', function() {
                            $(this).remove();
                        })
                    }).catch((err) => {
                        console.log(err);
                        toastr.error('Faild to delete award');
                    });
            }
        });
    });
</script>
@endpush
