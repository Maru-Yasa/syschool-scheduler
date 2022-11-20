<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jadwal Pelajaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <meta name="my-csrf-token" content="{{ csrf_token() }}" />

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Poppins;
            font-size: 17px;
            padding-bottom: 50px;
        }

        #myVideo  {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
        }

        .content {
            position: fixed;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            color: #f1f1f1;
            width: 100%;
            height: 100vh;
            padding-bottom: 50px;
            overflow-y: scroll;
            overflow-x: hidden;

        }

        #myBtn {
            width: 200px;
            font-size: 18px;
            padding: 10px;
            border: none;
            background: #000;
            color: #fff;
            cursor: pointer;
        }

        #myBtn:hover {
            background: #ddd;
            color: black;
        }
        .zoom:hover {
            -ms-transform: scale(0.5);
            -webkit-transform: scale(0.5);
            transform: scale(1.1); 
        }

        .logo-sekolah {
            max-width: 128px;
        }
        
    </style>
</head>
<body>
    <div class="fullscreen-bg">
        {{-- <img class="img-fluid" src="{{ asset('image/bg.png') }}" alt=""> --}}
    </div>

    <div class="content">
        <div class="row justify-content-center mt-5">
            <div class="col-12 text-center">
                <img src="{{ asset('image/logo/'.$setting_umum->logo) }}" class="img-fluid logo-sekolah" alt="">
                <h1 class="mt-3  font-weight-bold">Selamat Datang</h1>
                <h3 class="font-weight-semibold">Jadwal Pelajaran {{ $setting_umum->nama_sekolah }}</h3>
            </div>
        </div>           

        @yield('content')
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>
</body>

</html>