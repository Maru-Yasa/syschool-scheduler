@extends('layouts.app')

@section('header')
@endsection

@section('content')
<h1 class="fw-bold">Setting Profile </h1>

<div class="row justify-content-center">
    <div class="col card p-4">
        <form method="post" action="/admin/ubahPassword">
            @csrf
            <div class="mb-3">
              <label class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
            </div>
            <div class="mb-3">
              <label class="form-label">Password Lama</label>
              <input type="text" class="form-control" id="password" name="currentPassword" >
            </div>
            <div class="mb-3">
              <label class="form-label">Password Baru</label>
              <input type="text" class="form-control" id="password" name="password" >
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        
    </div>
</div>




@endsection

@section('js')
<x-script />
@endsection

