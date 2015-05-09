@extends ('layout')

@section ('title')
    There and Back
@stop

@section ('content')
@include ('navbar')
<div class="container">
    @if ($currentUser)
        @if ($currentUser->access_level == 0)
            <p class="text-success">You're Logged In! How about searching for something in a city (city is required but what you look for is optional)</p>
        @elseif ($currentUser->access_level == 1)
            @if ($success_message)
                <div class = "alert alert-success alert-dismissable">
                    <a class="panel-close close" data-dismiss="alert">×</a> 
                    <div class="flash">{{ $success_message }}</div>
                </div> 
            @endif
            @if ($error_message)
                <div class = "alert alert-danger alert-dismissable">
                    <a class="panel-close close" data-dismiss="alert">×</a> 
                    <div class="flash">{{ $error_message }}</div>
                </div>
            @endif
            @if ($suggestions and count($suggestions) > 0)
            <table class="table table-stripe table-hover table-condensed">
                <thead>
                    <tr>
                        <td>Restaurant</td>
                        <td>Suggested Instagram Username</td>
                        <td>Use as Username</td>
                        <td>Remove Suggestion</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suggestions as $suggestion)
                        <tr>
                            <td><a href="/business/{{ $suggestion->business_id }}" target="_blank">{{ $suggestion->business_id }}</a></td>
                            <td><a href="https://instagram.com/{{ $suggestion->instagram_username }}" target="_blank">{{ $suggestion->instagram_username }}</td></a>
                    <td><a href="/add-suggestion/{{ $suggestion->business_id }}/{{ $suggestion->instagram_username }}"><button class="btn btn-success"><i class="fa fa-check fa-fw"></i></button></a></td>
                            <td><a href="/remove-suggestion/{{ $suggestion->business_id }}/{{ $suggestion->instagram_username }}"><button class="btn btn-danger"><i class="fa fa-trash fa-fw"></i></button></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-success">No Current Suggestions</p>
            @endif
        @endif
    @else
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
    @endif
</div>
@stop