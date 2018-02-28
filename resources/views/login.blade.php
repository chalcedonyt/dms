@extends('layouts.master')

@section('title', 'Login')
@if (\Session::has('login_error'))
<div class="alert alert-danger" role="alert">
    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
    An error occurred: {{\Session::get('login_error')}}
</div>
@endif
<style>
    .loginbox{
        margin-top:50px;
    }
    .forgot-password{
        float:right;
        font-size: 80%;
        position: relative;
        top:-10px;
    }
    .panel-pad{
        padding-top:30px;
    }
    .login-alert{
        display:none;
    }
    .margT25{
        margin-bottom: 25px;
    }
    .margT10{
        margin-top:10px;
    }
    .margL10{
        padding-left: 25px;
    }
    .no-acc{
        border-top: 1px solid#888; padding-top:15px; font-size:85%;
    }
    .signup-box{
        display:none; margin-top:50px;
    }
    .signin{
        float:right; font-size: 85%; position: relative; top:-10px;
    }
    .btn-google {
      color: #fff;
      background-color: #dd4b39;
      border-color: #dd4b39;
    }
    .btn-google:hover,
    .btn-google:focus,
    .btn-google:active,
    .btn-google.active,
    .open > .dropdown-toggle.btn-google {
      color: #fff;
      background-color: #dd4b39;
      border-color: #dd4b39;
    }
</style>
<div class="container">
    <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 loginbox">
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title">DMS Sign In </div>
            </div>
            <div class="panel-body panel-pad">
                <div id="login-alert" class="alert alert-danger col-sm-12 login-alert"></div>
                    <form id="loginform" class="form-horizontal" role="form">
                        <div class="form-group">
                        <!-- Button -->
                            <div class="col-sm-12 controls">
                                @if (\Session::has('redirect'))
                                <p>Login to authenticate voucher</p>
                                <a id="btn-googlelogin" href="/login/google?redirect={{\Session::get('redirect')}}" class="btn btn-google">Authenticate with Google</a>
                                @else
                                <a id="btn-googlelogin" href="/login/google" class="btn btn-google">Authenticate with Google</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>