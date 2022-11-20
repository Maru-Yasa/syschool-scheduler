@extends('layouts.preview')
@section('content')
<div class="">
    <div class="d-flex flex-wrap align-items-center justify-content-center mt-4 w-100 h-100">
        @foreach ($jurusan as $item)
            <a href="{{ route('lihat_kelas',  $item->id) }}" class="text-decoration-none text-dark zoom">
                <div class="px-3 justify-content-center mt-3">
                    <div class="card p-3 shadow text-center mx-0"
                        style="background-color:#ffffff9c;width: 7rem;height: 7rem;">
                        <p class="h1 text-dark my-0 font-weight-bold"><i class="{{ $item->icon }}"></i></p>
                        <div class="" style="white-space: nowrap;width: 70px;overflow: hidden;text-overflow: ellipsis; ">
                            <p class="p font-weight-bold font-weight-bold mt-2">{{ $item->nama_jurusan }}</p>
                        </div>
                    </div>
                </div>
            </a>   
        @endforeach 
         
    </div>
</div>
    
@endsection