@extends('masterLayout.app')
@section('main')
@section('page-title')
    Manage Bank Details
@endsection
@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                <a href="{{ route('bank-details.create') }}" class="btn btn-default btn-xm"><i class="fa fa-plus"></i></a>
            </h3>
            <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead style="background-color: #F8F8F8;">
                    <tr>
                        <th width="15%">Employee Name</th>
                        <th width="10%">Bank Name</th>
                        <th width="10%">Account Title</th>
                        <th width="20%">Account Number</th>
                        <th width="5%">IBN</th>
                        <th width="10%">Branch Code</th>
                        <th width="10%">Branch Address</th>
                        <th width="10%">Manage</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@endsection