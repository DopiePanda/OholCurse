@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))
@section('image', asset('assets/uploads/images/errors/500-error.gif'))