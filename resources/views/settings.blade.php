@extends('layout')

@section('content')
<div class="card-header card-title">Settings</div>

<div class="card-body">
    <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">{{$profile->name}}</h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3" align="center"> 
                    <div class="image-upload">
                        <form action="/profile/change/avatar" method="post">
                            @csrf
                            <label for="file-input">
                                <img src="{!!$profile->Avatar!!}" class="img-circle img-responsive user-avatar"/>
                                <img src="" class="img-circle img-responsive user-avatar" id="testimg" hidden/>
                            </label>

                            <input id="file-avatar-input" type="file" name="avatar" accept="image/png, image/jpeg, image/jpg, image/ico" required/>
                            <input id="base64" name="base64img" type='hidden'>
                            <button type="submit" class="btn btn-primary" id="avatar-change">Change</button>
                        </form>
                    </div>
                    <div class="settings-menu">
                        <a href="/settings" class="settings-menu-option">Settings</a>
                    </div>
                </div>

                <div class=" col-md-9 col-lg-9 "> 
                    <a href="/profile"><< Profile</a>
                    <form method="POST" action="/settings/change/password">
                        @csrf

                        <div class="form-group row">
                            <label for="oldpassword" class="col-md-4 col-form-label text-md-right">Old password</label>

                            <div class="col-md-6">
                                <input id="oldpassword" type="password" class="form-control" name="oldpassword" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="newpassword" class="col-md-4 col-form-label text-md-right">New password</label>

                            <div class="col-md-6">
                                <input id="newpassword" type="password" class="form-control" name="newpassword" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Change
                                </button>
                            </div>
                        </div>
                    </form> 
                    @if(session()->has('success'))
                                                <div class="alert alert-success">
                                                    {{ session()->get('success') }}
                                                </div>
                                            @endif
                                            @if(session()->has('error'))
                                                <div class="alert alert-danger">
                                                    {{ session()->get('error') }}
                                                </div>
                                            @endif
                </div>
              </div>
            </div>
                 <div class="panel-footer">
                        <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
                        <span class="pull-right">
                            <a href="#" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                            <a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
                        </span>
                    </div>
            
          </div>
</div>
@endsection

@section('scripts')

@endsection