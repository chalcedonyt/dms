@extends('layouts.master')

@section('title', 'Voucher Redemptions')

@section('content')
@include('layouts.nav')
<h1>Voucher Redemptions</h1>
<div id="voucher"></div>
@endsection

@section('scripts')
<script src="{{mix('js/vouchers.js')}}"></script>
@endsection