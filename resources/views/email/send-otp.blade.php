@extends('email.layouts.master')
@section('content')
<h2>{{ $details['title'] }}</h2>
<h3>{!! $details['body'] !!}</h3>
@endsection
 