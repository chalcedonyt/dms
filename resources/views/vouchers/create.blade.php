@extends('layouts.master')

@section('title', 'Create voucher')

@section('content')
@include('layouts.nav')
<h1>Create a voucher</h1>
<div id="voucher"></div>
@endsection

@section('scripts')
<script src="{{mix('js/vouchers.js')}}"></script>
@endsection