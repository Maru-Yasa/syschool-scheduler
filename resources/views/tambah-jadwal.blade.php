@extends('layouts.app')

@section('header')
    <h1 class="fw-bold">Tambah Jadwal</h1>
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
                </div>            
                <div class="mb-3 d-flex flex-column">
                    <div class="row">
                        <div class="col">
                            <label for="">2. Pilih Kelas :</label>
                            <select class="form-control form-control-sm w-100" name="id_kelas" id="">
                                <option value="" disabled selected>-- Pilih Kelas --</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="">Ruang Kelas :</label>
                            <select class="form-control form-control-sm w-100" name="id_ruang_kelas" id="">
                                <option value="" disabled selected>-- Pilih Ruang Kelas --</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3 d-flex flex-column">
                    <label for="">3. Pilih Guru :</label>
                    <select class="form-control form-control-sm" name="id_guru" id="">
                        <option value="" disabled selected>-- Pilih Guru --</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3 d-flex flex-column">
                    <label for="">3. Pilih Mapel :</label>
                    <select class="form-control form-control-sm" name="id_mapel" id="">
                        <option value="" disabled selected>-- Pilih Mapel --</option>
                    </select>
                </div>
                <div class="mb-3 d-flex flex-column">
                    <label for="">3. Pilih Hari :</label>
                    <select class="form-control form-control-sm" name="id_hari" id="">
                        <option value="" disabled selected>-- Pilih Hari --</option>
                    </select>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="">4. JP Awal :</label>
                            <input name="jam_awal" type="number" min="1" max="{{ $master_setting_jp['jumlah_jp'] }}" class="form-control form-control-sm">
                        </div>
                        <div class="col">
                            <label for="">JP Akhir :</label>
                            <input name="jam_akhir" type="number" min="1" max="{{ $master_setting_jp['jumlah_jp'] }}" class="form-control form-control-sm">
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

<div class="bg-white w-100 p-4 mt-4 rounded border">
    <table id="preview_jadwal" class="table table-sm table-bordered">
        <thead class="bg-primary">
            <tr>
                <th width="10%"></th>
                @foreach ($master_hari as $h)
                    <th>{{ $h['nama_hari'] }}</th>
                @endforeach
            </tr>
        </thead>
    </table>
</div>

@endsection

@section('js')
    <x-script />
    <script>

        $("select").select2()

        $(document).ready(() => {
            $("#preview_jadwal").DataTable({
                'ordering': false,
                'paging': false,
                'info': false,
                'searching' : false
            })
        
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
                    console.log(res);
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
                    console.log(res);
                    res.data.forEach(guru => {
                        data.push({
                            id: guru.id,
                            text: guru.nama_raw
                        })
                    });
                    console.log(data);
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
                        console.log(res);
                    },
                    error: (res) => {
                        console.log(res);
                    }
                })
            })

        })

    </script>
@endsection

