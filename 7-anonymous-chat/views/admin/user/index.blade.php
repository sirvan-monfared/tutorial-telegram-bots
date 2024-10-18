@extends('admin.layouts.master')

@section('content')
    <div class="pd-20 card-box mb-30">

        @include('_flash')

        <div class="clearfix mb-20">
            <div class="pull-left">
                <h4 class="text-blue h4">Users List</h4>
            </div>
            <div class="pull-right">
                <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-sm scroll-click" ><i class="fa fa-code"></i> Create user</a>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">name</th>
                    <th scope="col">chat id</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->chat_id }}</td>
                        <td class="d-flex">
                            <a class="btn btn-info" href="{{ route('admin.user.edit', ['id' => $user->id]) }}">Edit</a>

                            <form method="POST" action="{{ route('admin.user.destroy', ['id' => $user->id]) }}">
                                @include('_csrf')
                                @method('DELETE')

                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                @endforeach


            </tbody>
        </table>
    </div>
@endsection