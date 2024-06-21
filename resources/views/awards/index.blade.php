@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Awards
@endsection
@section('page-content')
    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
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
                    @if ($awards->count() > 0)
                        @foreach ($awards as $award)
                            <tr>
                                <td>{{ $award->created_at->toFormattedDateString() }}</td>
                                <td>
                                    {{ $award->user->name }}
                                </td>
                                <td>
                                    {{ $award->award_name }}
                                </td>
                                <td>
                                    @php
                                        $awardFilePath = str_replace('public/', 'storage/', $award->award_file) ?? '';
                                    @endphp
                                    @if ($awardFilePath)
                                        <img src="{{ asset($awardFilePath) }}" alt="Award Image" width="70"
                                            height="70">
                                    @endif
                                </td>
                                <td>{!! $award->description !!}</td>
                                <td>
                                    <a href="{{ route('awards.edit', $award->id) }}" class="btn btn-info btn-flat btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="delete-award btn btn-danger btn-flat btn-sm"
                                        data-award="{{ $award->id }}"
                                        data-delete-route="{{ route('awards.destroy', $award->id) }}">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('.delete-award').click(function(e) {
            e.preventDefault();
            
            const awardId = $(this).data('award');
            const url = $(this).data('delete-route').replace(':id', awardId);
            const token = $('meta[name="csrf-token"]').attr('content');
            const targetElement =  $(this);

            if (confirm('Are you sure you want to delete this Award?')) {
                $.ajax({
                        type: "DELETE",
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    })
                    .then((result) => {
                        toastr.success(result.message);
                        targetElement.closest('tr').fadeOut('slow', function () {
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
