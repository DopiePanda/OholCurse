@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', "The page you're looking for could not be found.")
@section('sub-message', "We're sorry.")
@section('image', asset('assets/uploads/images/errors/404-error.gif'))