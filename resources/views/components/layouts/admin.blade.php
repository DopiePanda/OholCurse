@extends('layouts.app')

@section('content')
  <div class="navigation">
    ...YourNavbar...
  </div>
  <div class="content">
    @yield('slot')
  </div>
@overwrite