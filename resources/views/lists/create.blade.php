@extends('layouts.master')

@section('title', 'Create list')

@section('content')
@include('layouts.nav')
<h1>Create a list</h1>
<div id="list"></div>
@endsection

@section('scripts')
<script src="{{mix('js/lists.js')}}"></script>
@endsection