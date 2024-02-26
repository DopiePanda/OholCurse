@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized'))
@section('image', asset('assets/uploads/images/errors/404-error.webp'))