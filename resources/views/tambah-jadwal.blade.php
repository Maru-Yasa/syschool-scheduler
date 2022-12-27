@extends('layouts.app')

@section('header')
    <h1 class="fw-bold">Tambah Jadwal</h1>
    @if (!$master_setting_umum->id_semester)
        <div class="alert bg-danger mt-3"><i class="bi bi-exclamation-triangle-fill mr-2"></i> Anda belum memilih semster, pilih semester di halaman beranda\setting umum</div>
    @endif
@endsection

@section('content')

<div class="bg-white w-100 rounded border p-4">
    <form action="" id="form_tambah_jadwal">
        <input type="text" id="id_semester" hidden name="id_semester">
        <div class="row">
            <div class="col-6">
                <div class="mb-3 d-flex flex-column">
                    <label for="">1. Pilih Jurusan :</label>
                    <select class="form-control form-control-sm" name="id_jurusan" id="">
                        <option value="" disabled selected>-- Pilih Jurusan --</option>
                    </select>
                    <div hidden id="validation_id_jurusan" class="text-danger text-sm validation">

                    </div>
                </div>            
                <div class="mb-3 d-flex flex-column">
                    <div class="row">
                        <div class="col">
                            <label for="">2. Pilih Kelas :</label>
                            <select class="form-control form-control-sm w-100" name="id_kelas" id="">
                                <option value="" disabled selected>-- Pilih Kelas --</option>
                            </select>
                            <div hidden id="validation_id_kelas" class="text-danger text-sm validation">

                            </div>
                        </div>
                        <div class="col">
                            <label for="">Ruang Kelas :</label>
                            <select class="form-control form-control-sm w-100" name="id_ruang_kelas" id="">
                                <option value="" disabled selected>-- Pilih Ruang Kelas --</option>
                            </select>
                            <div hidden id="validation_id_ruang_kelas" class="text-danger text-sm validation">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3 d-flex flex-column">
                    <label for="">3. Pilih Guru :</label>
                    <select class="form-control form-control-sm" name="id_guru" id="">
                        <option value="" disabled selected>-- Pilih Guru --</option>
                    </select>
                    <div hidden id="validation_id_guru" class="text-danger text-sm validation">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3 d-flex flex-column">
                    <label for="">3. Pilih Mapel :</label>
                    <select class="form-control form-control-sm" name="id_mapel" id="">
                        <option value="" disabled selected>-- Pilih Mapel --</option>
                    </select>
                    <div hidden id="validation_id_mapel" class="text-danger text-sm validation">

                    </div>
                </div>
                <div class="mb-3 d-flex flex-column">
                    <label for="">3. Pilih Hari :</label>
                    <select class="form-control form-control-sm" name="id_hari" id="">
                        <option value="" disabled selected>-- Pilih Hari --</option>
                    </select>
                    <div hidden id="validation_id_hari" class="text-danger text-sm validation">

                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="">4. JP Awal :</label>
                            <input name="jam_awal" type="number" min="1" max="{{ $master_setting_jp['jumlah_jp'] }}" class="form-control form-control-sm">
                            <div hidden id="validation_jam_awal" class="text-danger text-sm validation">

                            </div>
                        </div>
                        <div class="col">
                            <label for="">JP Akhir :</label>
                            <input name="jam_akhir" type="number" min="1" max="{{ $master_setting_jp['jumlah_jp'] }}" class="form-control form-control-sm">
                            <div hidden id="validation_jam_akhir" class="text-danger text-sm validation">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-3">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle"></i> Tambah</button>
            </div>
        </div>

    </form>
</div>

<div id="table-wrapper" class="bg-white w-100 p-4 mt-4 rounded border">
    {{-- <div class="" id="calendar"></div> --}}
    {{-- <table id="preview_jadwal" class="table table-sm table-bordered">
        <thead class="bg-primary">
            <tr>
                <th width="10%"></th>
                @foreach ($master_hari as $h)
                    <th>{{ $h['nama_hari'] }}</th>
                @endforeach
            </tr>
        </thead>
    </table> --}}

</div>

@endsection

@section('js')
    <x-script />
    <script>

        $("select").select2()

        function create_table(id, data, id_guru) {
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
            const nama_kelas = document.createElement('h3')
            nama_kelas.innerHTML = data[0].kelas.nama_kelas
            
            container.appendChild(nama_kelas)
            container.appendChild(tbl)

            // render thead
            tbl.appendChild(thead)

            // render tr
            thead.setAttribute('class', 'bg-primary text-white')
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
            for (let i = 1; i < setting_jp.jumlah_jp + 1; i++) {
                const tr = document.createElement('tr')
                tr.setAttribute('class', 'bg-white text-black text-canter')
                for (let j = 0; j < hari.length + 1; j++) {

                    // render JP
                    if(j === 0){
                        const td = tr.insertCell()
                        td.appendChild(document.createTextNode(i))
                    }else{
                        const td = document.createElement('td')
                        td.setAttribute('id', `${id}-${i}-${j}`)
                        td.classList.add('text-center')
                        td.innerHTML = '-'
                        tr.appendChild(td)              
                    }
                }
                tbl.appendChild(tr)
            }

            data.forEach((obj) => {
                const elem = $(`#${id}-${obj.jam_awal}-${obj.hari.urut}`)
                const selisih = obj.jam_akhir - obj.jam_awal + 1
                if(obj.id_guru == id_guru){
                    elem.html(`${obj.mapel.nama_mapel} <br> ${obj.guru.nama}`)
                    elem.css('background-color', 'rgba(0,123,255, 0.1)')
                }else{
                    elem.css('background-color', 'rgba(217, 83, 79, 0.1)	')
                }
                elem.addClass('text-center align-middle')
                for (let i = 1; i < selisih; i++) {
                    $(`#${id}-${obj.jam_awal + i}-${obj.hari.urut}`).remove()  
                }
                elem.prop('rowspan', selisih)
            })

        }

        $(document).ready(() => {
            $.fn.select2.defaults.set("escapeMarkup", (text) => text)

            $("#preview_jadwal").DataTable({
                'ordering': false,
                'paging': false,
                'info': false,
                'searching' : false
            })

            // const Calendar = tui.Calendar

            // const calendar = new Calendar('#calendar', {
            //     usageStatistics: false,
            //     defaultView: 'week'
            // });
                        
            // initialize data master

            // jurusan
            $.ajax({
                url: "{{ route('get_jurusan') }}",
                type: 'get',
                success: (res) => {
                    let data = [];
                    res.data.forEach(jurusan => {
                        data.push({
                            id: jurusan.id,
                            text: jurusan.nama_jurusan
                        })
                    });
                    $("select[name=id_jurusan]").select2({
                        data: data
                    })
                }
            })

            // id semester
            $.ajax({
                type: 'get',
                url: `{{ route('get_setting_umum') }}`,
                success: (res) => {
                    $("#id_semester").attr('value', res.data.id_semester)
                }
            })

            // kelas by id_jurusan
            $("select[name=id_jurusan]").on('select2:select',(e) => {
                var url = `{{ route('get_kelas_by_id_jurusan')}}`
                $.ajax({
                    url: `${url}?id_jurusan=${e.target.value}`,
                    type: 'get',
                    success: (res) => {
                        let data = [];
                        res.data.forEach(kelas => {
                            data.push({
                                id: kelas.id,
                                text: kelas.nama_kelas
                            })
                        });
                        $("select[name=id_kelas]").empty().select2({
                            data: data
                        })
                    }
                })
            })

            // ruang kelas
            $.ajax({
                url: "{{ route('get_ruang_kelas') }}",
                type: 'get',
                success: (res) => {
                    
                    let data = [];
                    res.data.forEach(ruang => {
                        data.push({
                            id: ruang.id,
                            text: `${ruang.nama} - ${ruang.owner}`
                        })
                    });
                    $("select[name=id_ruang_kelas]").select2({
                        data: data
                    })
                }
            })

            
            // guru
            $.ajax({
                url: "{{ route('get_guru') }}",
                type: 'get',
                success: (res) => {
                    let data = [];
                    
                    res.data.forEach(guru => {
                        data.push({
                            id: guru.id,
                            text: guru.nama_raw
                        })
                    });
                    
                    $("select[name=id_guru]").select2({
                        data: data
                    })
                }
            })   
            
            // mapel
            $.ajax({
                url: "{{ route('get_mapel') }}",
                type: 'get',
                success: (res) => {
                    let data = [];
                    res.data.forEach(mapel => {
                        data.push({
                            id: mapel.id,
                            text: mapel.nama_mapel
                        })
                    });
                    $("select[name=id_mapel]").select2({
                        data: data
                    })
                }
            })   
            
            // hari
            $.ajax({
                url: "{{ route('get_hari') }}",
                type: 'get',
                success: (res) => {
                    let data = [];
                    res.data.forEach(hari => {
                        data.push({
                            id: hari.id,
                            text: hari.nama_hari
                        })
                    });
                    $("select[name=id_hari]").select2({
                        data: data
                    })
                }
            })

            // preview jadwal
            $("select[name='id_guru']").off().on('select2:select', () => {
                var preview_jadwal_url = "{{ route('get_jadwal_guru') }}"
                $.ajax({
                    url: preview_jadwal_url + `?id_guru=${$("select[name='id_guru']").val()}`,
                    type: "GET",
                    success: (res) => {
                        const kelas_raw = Object.keys(res);
                        $('#table-wrapper').children().remove()
                        // res[parseInt(kelas_raw)]
                        kelas_raw.forEach((id_kelas) => {
                            const obj = res[parseInt(id_kelas)]
                            create_table(`${id_kelas}`, obj, $("select[name='id_guru']").val())
                        })
                    }
                })
            })

            // handle tambah jadwal
            $("#form_tambah_jadwal").off().on('submit',(e) => {
                e.preventDefault()
                const formData = new FormData(e.target)
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="my-csrf-token"]').attr('content')
                    },
                    type: 'post',
                    method: 'post',
                    url: "{{ route('tambah_jadwal') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        
                        if(res.status){
                            toastr.success(res.message)
                            $(".validation").removeClass('is-invalid')
                            $(`.validation`).prop('hidden', true)   
                            $("#form_tambah_jadwal").trigger('reset')
                            $("#table-wrapper").empty()
                        }else{
                            toastr.error(res.message)
                            $(".validation").removeClass('is-invalid')
                            $(`.validation`).prop('hidden', true)                               
                            Object.keys(res.messages).forEach((value, key) => {
                                $(`#validation_${value}`).prop('hidden', true)                               
                                $(`*[name=${value}]`).removeClass('is-invalid')
                                $(`#validation_${value}`).html('')

                                $(`*[name=${value}]`).addClass('is-invalid')
                                $(`#validation_${value}`).html(res.messages[value])
                                $(`#validation_${value}`).prop('hidden', false)                               
                            })
                        }
                    },
                    error: (res) => {
                        
                    }
                })
            })

        })

    </script>
@endsection

