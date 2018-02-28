@extends('layouts.master')

@section('title', 'Member Directory')

@section('content')
@include('layouts.nav')
<h1>Member Directory</h1>
<div id="member"></div>
@endsection

@section('scripts')
<script src="{{mix('js/members.js')}}"></script>
@endsection