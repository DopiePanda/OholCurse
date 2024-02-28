@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('The server has encoutered an issue'))
@section('sub-message', 'This day sucks')
@section('image', asset('assets/uploads/images/errors/500-error.gif'))