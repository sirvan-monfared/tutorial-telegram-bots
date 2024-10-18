@if(\App\Core\Session::has('success'))
    <div class="alert alert-success">{{ \App\Core\Session::get('success') }}</div>
@endif

@if(\App\Core\Session::has('danger'))
    <div class="alert alert-danger">{{ \App\Core\Session::get('danger') }}</div>
@endif