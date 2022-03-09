@extends('layouts.app')

@section('content')
    @auth 
        @php 
            $admin = "true";
        @endphp
    @else
        @php 
            $admin = "false";
        @endphp
    @endauth
    
    <dash-component view_type="dash" application="null" v-bind:is-user-admin="{{ $admin }}"></dash-component>
@endsection