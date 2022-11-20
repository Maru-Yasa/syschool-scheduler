<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jadwal Pelajaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <meta name="my-csrf-token" content="{{ csrf_token() }}" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial;
            font-size: 17px;
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
        <video loop muted autoplay class="fullscreen-bg__video" id="myVideo">
            <source src="{{ asset('video/bg_video.mp4') }}" type="video/mp4">
        </video>
    </div>

    <div class="content">
        <div class="row justify-content-center mt-5">
            <div class="col-12 text-center">
                <img src="{{ asset('image/logo/'.$setting_umum->logo) }}" class="img-fluid logo-sekolah" alt="">
                <h1 class="mt-3">Selamat Datang</h1>
                <h3>Jadwal Pelajaran {{ $setting_umum->nama_sekolah }}</h3>
            </div>
        </div>  
        <div class="">
            <div id="table-wrapper" class="d-flex flex-wrap align-items-center justify-content-center mt-4 px-5 overflow-auto">

            </div>
            <div class="text-center mt-5">
                <a href="{{ route('lihat_kelas', $id_jurusan) }}" class="h1" style="text-decoration-nonr;"><i class="bi bi-arrow-left-circle-fill"></i></a>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/dayjs@1.8.20/dayjs.min.js"></script>
    <script src="https://unpkg.com/dayjs@1.8.20/plugin/customParseFormat.js"></script>
    <script>
        function random_rgba() {
            var o = Math.round, r = Math.random, s = 255;
            return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + 0.2 + ')';
        }
        function create_table(id, data, nama_kelas, id_kelas) {
            const container = document.getElementById('table-wrapper')
            const tbl = document.createElement('table')
            const thead = document.createElement('thead')
            const tr = document.createElement('tr')
            const table_id = `table_preview_${id}`
            let hari = @json($master_hari);
            
            const setting_jp = @json($master_setting_jp);
            hari = hari.map((obj, key) => {
                let result = {
                    name: obj.nama_hari,
                    targets: key
                }
                return result
            })

            // render table
            tbl.setAttribute('id', table_id)
            tbl.setAttribute('class', "table table-sm table-bordered mb-3")
            const nama_kelas_element = document.createElement('h3')
            const kelas_url = "{{ route('get_kelas_by_id') }}"
            
            nama_kelas_element.innerHTML = nama_kelas
            container.appendChild(nama_kelas_element)
            container.appendChild(tbl)
            // render thead
            tbl.appendChild(thead)

            // render tr
            thead.setAttribute('class', 'bg-primary text-white text-center')
            thead.appendChild(tr)

            const th_jp = document.createElement('th')
            th_jp.innerHTML = 'JP'
            tr.appendChild(th_jp)
            hari.forEach((h) => {
                const th = document.createElement('th')
                th.innerHTML = h.name
                tr.appendChild(th)
            })


            data.reverse()

            // render data
            let jam_jp = dayjs("{{ $master_setting_jp['mulai_jp'] }}", "HH:mm")
            const master_durasi_jp = "{{ $master_setting_jp['durasi_jp'] }}"
            const jeda = JSON.parse('{!! json_encode($master_jeda) !!}')
            let list_mulai_jeda = []
            for (let i = 1; i < setting_jp.jumlah_jp + 1; i++) {
                const tr = document.createElement('tr')
                tr.setAttribute('class', 'bg-white text-black text-canter')
                for (let j = 0; j < hari.length + 1; j++) {

                    // render JP
                    if(j === 0){
                        let durasi_jp = master_durasi_jp
                        const td = tr.insertCell()
                        let isIstirahat = false
                        $(td).addClass("align-middle")
                        td.classList.add('text-center')
                        $(td).html(document.createTextNode(`${jam_jp.format('HH:mm')}`))
                        jam_jp = jam_jp.add(parseInt(master_durasi_jp), 'm')

                        jeda.forEach((jeda) => {
                            if(i == jeda.mulai_jeda -1){
                                isIstirahat = true
                                jam_jp = jam_jp.add(parseInt(jeda.durasi_jeda), 'm')
                            }
                        })


                    }else{

                        const td = document.createElement('td')
                        $(td).addClass("align-middle")
                        td.setAttribute('id', `${id}-${i}-${j}`)
                        let y = i
                        list_mulai_jeda.forEach((lst) => {
                            if(lst <= i){
                                y -= 1
                                td.setAttribute('id', `${id}-${i - 1}-${j}`)
                            }else{
                                td.setAttribute('id', `${id}-${i}-${j}`)
                            }
                        })
                        td.classList.add('text-center')
                        td.classList.add('prop-wrapper')
                        td.classList.add('position-relative')
                        td.classList.add('p-2')
                        td.innerHTML = `-`
                        tr.appendChild(td)              

                    }
                }
                tbl.appendChild(tr)
            }

            let warna = 50
            if (data !== []) {
                data.forEach((obj) => {
                    const elem = $(`#${id}-${obj.jam_awal}-${obj.hari.urut}`)
                    var selisih = obj.jam_akhir - obj.jam_awal + 1
                    const profileUrl = "{{ url('image/guru/') }}"
                    elem.html(`<img src="${profileUrl+'/'+obj.guru.profile}" class="img-fluid rounded-circle my-2" style="object-fit: cover;width:64px;height:64px;" /> <br> ${obj.mapel.nama_mapel} <br> ${obj.guru.nama} <br> ${obj.ruang_kelas.nama}`)
                    elem.css('background-color', random_rgba())
                    elem.addClass('text-center align-middle position-relative prop-wrapper')
                    for (let i = 1; i < selisih; i++) {
                        $(`#${id}-${obj.jam_awal + i}-${obj.hari.urut}`).remove()  
                    }
                    elem.prop('rowspan', selisih)
                    warna += 50
                })
            }

        }

        function renderTable() {
            $.ajax({
                url: "{{ route('get_jadwal_kelas') }}" + "?id_kelas={{ $id_kelas }}",
                type: 'get',
                success: (res) => {
                    console.log(res);
                    const kelas_raw = Object.keys(res)
                    $("#table-wrapper").empty()
                        kelas_raw.forEach((id_kelas) => {
                        const obj = res[id_kelas]
                        const kelas_url = "{{ route('get_kelas_by_id') }}"
                        $.ajax({
                            type: 'get',
                            url: `${kelas_url}?id_kelas=${id_kelas}`,
                            success: (res) => {
                                create_table(`${id_kelas}-table-jadwal`, obj, res.nama_kelas, id_kelas)
                            }
                        })
                    })
                }
            })
        }

        $(document).ready(() => {
            console.log('test');
            dayjs.extend(window.dayjs_plugin_customParseFormat);
            renderTable();

        })

    </script>

</body>

</html>