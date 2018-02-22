@extends('layouts.master')

@section('title', 'Vouchers')

@section('content')
@include('layouts.nav')
<h1>Vouchers</h1>
<div id="voucher"></div>
@endsection

@section('scripts')
<script src="{{mix('js/vouchers.js')}}"></script>
@endsection