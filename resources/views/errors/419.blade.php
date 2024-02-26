@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('You\'ve been timed out, due to inactivity. Please login to continue'))
@section('image', asset('assets/uploads/images/errors/419-error.gif'))