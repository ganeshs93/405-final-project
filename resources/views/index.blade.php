@extends ('layout')

@section ('title')
    There and Back
@stop

@section ('content')
@include ('navbar')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-6">
        
        </div>
        <div class="col-md-6 col-sm-6">
            <form class="form-horizontal" method="post" action="/join">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="text-center">
                    <h1>Sign Up</h1>
                </div>
                @if (count($errors) > 0)
                <div class = "alert alert-danger alert-dismissable">
                    <a class="panel-close close" data-dismiss="alert">×</a> 
                    @foreach ($errors->all() as $error)
                        <div class="flash">{{ $error }}</div>
                    @endforeach
                </div>
                @endif
                @if ($success_message)
                   <div class = "alert alert-success alert-dismissable">
                    <a class="panel-close close" data-dismiss="alert">×</a> 
                    <div class="flash">{{ $success_message }}</div>
                </div> 
                @endif
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" value="{{ old('firstname') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="{{ old('lastname') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="{{ old('username') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-cog fa-fw"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <a href="/login">Already have an account? Login</a>
                            <button type="submit" style="display:inline" id="submit" name="submit" class="btn btn-primary pull-right">Sign Up</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop