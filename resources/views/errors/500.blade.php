@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Here\'s your.. - Fuck! Fucking shit! Im done!'))
@section('sub-message', "Server error")
@section('image', asset('assets/uploads/images/errors/500-error.gif'))