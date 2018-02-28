@extends('layouts.master')

@section('title', 'Create admin')

@section('content')
@include('layouts.nav')
<h1>Create an admin</h1>
<div id="admin"></div>
@endsection

@section('scripts')
<script src="{{mix('js/admins.js')}}"></script>
@endsection