@extends('layouts.master')

@section('title', 'Lists')

@section('content')
@include('layouts.nav')
<h1>Lists</h1>
<div id="list"></div>
@endsection

@section('scripts')
<script src="{{mix('js/lists.js')}}"></script>
@endsection