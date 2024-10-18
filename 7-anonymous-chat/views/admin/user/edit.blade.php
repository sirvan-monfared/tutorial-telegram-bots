@extends('admin.layouts.master')

@section('content')
    <div class="pd-20 card-box mb-30">

        @include('_flash')

        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-blue h4">Edit User</h4>
            </div>
        </div>
        <form action="{{ route('admin.user.update', ['id' => $user->id]) }}" method="POST">
            @include('_csrf')
            @method('PUT')

            @include('admin.user._form', [
                'buttonText' => 'Update'
            ])

        </form>
    </div>
@endsection