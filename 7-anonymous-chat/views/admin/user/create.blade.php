@extends('admin.layouts.master')

@section('content')
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-blue h4">Create User</h4>
            </div>
        </div>
        <form action="{{ route('admin.user.store') }}" method="POST">
            @include('_csrf')

            @include('admin.user._form')

        </form>
    </div>
@endsection