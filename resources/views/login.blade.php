@extends ('layout')

@section ('title')
    Login
@stop

@section ('content')
@include ('navbar')
<div class="container">
    <form class="form-horizontal" method="post" action="/login">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="text-center">
            <h1>Login</h1>
        </div>
        @if ($error_message)
            <div class = "alert alert-danger alert-dismissable col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2">
                <a class="panel-close close" data-dismiss="alert">×</a> 
                <div class="flash">{{ $error_message }}</div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2">
                <div class="form-group"> 
                    <div class="checkbox" style="display:inline">
                        <label><input type="checkbox" name="remember">Stay Signed In</label>
                    </div>
                    <button type="submit" style="display:inline" id="submit" name="submit" class="btn btn-primary pull-right">Login</button>
                </div>
            </div>
        </div>
    </form>
</div>
@stop