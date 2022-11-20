@extends('layouts.preview')
@section('content')
<div class="">
    <div class="d-flex flex-wrap align-items-center justify-content-center mt-4 w-100 h-100">
        @foreach ($kelas as $item)
            <a href="{{ route('lihat_jadwal',  $item->id) }}" class="text-decoration-none text-dark zoom">
            <div class="px-4 justify-content-center mt-3">
                    <div class="card p-3 shadow text-center mx-0"
                        style="background-color:#ffffff9c;width: 10rem;height: 5rem;">
                        <h4 class="p font-weight-bold font-weight-bold mt-2">{{ $item->nama_kelas }}</h4>
                    </div>
                </div>
            </a>  
        @endforeach
    </div>
    <div class="text-center mt-5">
        <a href="{{ route('lihat_jurusan') }}" class="h1" style="text-decoration-nonr;"><i class="bi bi-arrow-left-circle-fill"></i></a>
    </div>
</div>
    
@endsection
  