@extends('layouts.master')

@section('title', 'Lists - '.$list_name)

@section('content')
@include('layouts.nav')
<h1>{{$list_name}}</h1>
<div id="list"></div>
@endsection

@section('scripts')
<script src="{{mix('js/lists.js')}}"></script>
@endsection