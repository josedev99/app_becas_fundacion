@extends('Layouts.App')

@section('title','Inicio - Portal DTE')

@section('page-title')
<div class="pagetitle">
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
@endsection
@section('content')
    <h1>Bienvenido {{ Auth()->user()->nombre }}</h1>
@endsection