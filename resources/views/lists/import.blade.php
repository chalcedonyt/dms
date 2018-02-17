@extends('layouts.master')

@section('title', 'Import list')

@section('content')
@include('layouts.nav')
<h1>Importing list from Google Sheets</h1>
<div id="list">
@endsection

@section('scripts')
<script src="{{mix('js/lists.js')}}"></script>
@endsection