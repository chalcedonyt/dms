@extends('layouts.master')

@section('title', 'Admins')

@section('content')
@include('layouts.nav')
<h1>Admins</h1>
<div id="admin"></div>
@endsection

@section('scripts')
<script src="{{mix('js/admins.js')}}"></script>
@endsection