@extends('layouts.app')

@section('header')
    <h1>Beranda</h1>
@endsection

@section('content')
    <div class="">
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>15 menit</h3>
                        <p>1 jam pelajaran</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-clock"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>5 hari</h3>
                        <p>Pembelajaran Aktif</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>10 Jam</h3>
                        <p>Jumlah jam pembelajaran</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="info-box">
                    <div id="jumlah_guru_loading" class="overlay dark">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                    <span class="info-box-icon bg-info">
                        <i class="fa fa-users"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Guru</span>
                        <span id="jumlah_guru" class="info-box-number">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="info-box">
                    <div id="jumlah_jurusan_loading" class="overlay dark">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                    <span class="info-box-icon bg-success">
                        <i class="fa fa-wrench"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Jurusan</span>
                        <span id="jumlah_jurusan" class="info-box-number">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="info-box">
                    <div id="jumlah_kelas_loading" class="overlay dark">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                    <span class="info-box-icon bg-success">
                        <i class="fa fa-list"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Kelas</span>
                        <span id="jumlah_kelas" class="info-box-number">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="info-box">
                    <div id="jumlah_mapel_loading" class="overlay dark">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                    <span class="info-box-icon bg-danger">
                        <i class="fa fa-book"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Mapel</span>
                        <span id="jumlah_mapel" class="info-box-number">999</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-3 mt-3">
        <div class="row">
            <div class="col-7 px-0">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <i class="fa fa-wrench"></i> Setting Umum
                    </div>
                    <div class="card-body">
                        <form action="" id="form_setting_umum">
                            @csrf
                            <div class="mb-3 d-flex flex-column">
                                <label for="">Tingkat Sekolah:</label>
                                <select type="text" name="tingkat" class="form-control">
                                    <option value="" disabled selected>-- Pilih Tingkat Sekolah --</option>
                                    <option value="sd">Sekolah Dasar</option>
                                    <option value="smp">Sekolah Menengah Pertama</option>
                                    <option value="sma">Sekolah Menengah Atas</option>
                                    <option value="smk">Sekolah Menengah Kejuruan</option>
                                </select>
                                <div hidden id="validation_tingkat" class="text-danger validation">

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="">Nama Sekolah:</label>
                                <input type="text" name="nama_sekolah" class="form-control" placeholder="Nama sekolah">
                                <div hidden id="validation_nama_sekolah" class="text-danger validation">

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="">Alamat Sekolah:</label>
                                <textarea type="text" name="alamat" class="form-control" placeholder="Alamat sekolah">
                                </textarea>
                                <div hidden id="validation_alamat" class="text-danger validation">

                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" hidden name="id">
                                <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>                
            </div>

            <div class="col-5 ps-3">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <i class="fa fa-clock"></i> Setting Jam pelajaran
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="mb-3">
                                <label for="">Jumlah Jam Pembelajaran:</label>
                                <input type="number" name="nama_sekolah" class="form-control" placeholder="Jumlah jam pelajaran">
                                <small class="form-text text-muted">Jumlah max jam pembelajaran dalam satu hari</small>
                            </div>
                            <div class="mb-3">
                                <label for="">Lama Jam Pembelajaran (menit):</label>
                                <input type="number" name="nama_sekolah" class="form-control" placeholder="Lama satu jam pelajaran">
                                <small class="form-text text-muted">Lamanya setiap satu jam pembelajaran</small>
                            </div>
                            <div class="mb-3">
                                <label for="">Mulai Istirahat (Jam Pembelajaran)</label>
                                <input type="number" class="form-control" placeholder="Mulainya jeda istirahat">
                                <small class="form-text text-muted">Mulainya jeda istirahat berdasarkan jam pembelajarannya</small>

                            </div>
                            <div class="mb-3">
                                <label for="">Lama Jam Istirahat (menit):</label>
                                <input type="number" name="nama_sekolah" class="form-control" placeholder="Lama satu jam pelajaran">
                                <small class="form-text text-muted">Lama jam istirahat dalam menit</small>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>                
            </div>

        </div>
    </div>
@endsection

@section('js')
<x-script />
<script>

    $(document).ready(() => {

        $("#form_setting_umum").off().on('submit', (e) => {
            e.preventDefault()
            const formData = new FormData($("#form_setting_umum")[0])  

            $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('edit_setting_umum') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        console.log("🚀 ~ file: jurusan.blade.php ~ line 87 ~ $ ~ res", res)
                        if(res.status){
                            toastr.success(res.message)
                        }else{
                            toastr.error(res.message)
                            Object.keys(res.messages).forEach((value, key) => {
                                $(`*[name=${value}]`).addClass('is-invalid')
                                console.log($(`#validation_${value}`));
                                $(`#validation_${value}`).html(res.messages[value])
                                $(`#validation_${value}`).prop('hidden', false)                               
                            })
                        }
                    },
                    error: (res) => {
                        console.log(res);
                    }
            })
        })

        // initialize data
        $('select').select2()

        $.ajax({
            type: 'get',
            url: "{{ route('get_setting_umum') }}",
            success: (res) => {
                if(res.data != null || res.data != undefined){
                    $("input[name=nama_sekolah]").val(res.data.nama_sekolah)
                    $("select[name=tingkat]").val(res.data.tingkat).trigger('change')
                    $("textarea[name=alamat]").val(res.data.alamat)
                    $("input[name=id]").val(res.data.id)
                }
            }
        })

        const requests = [
            $.ajax({url: "{{ route('get_guru') }}", dataType: 'json'}),
            $.ajax({url: "{{ route('get_jurusan') }}"}),
            $.ajax({url: "{{ route('get_kelas') }}"}),
        ]

        $.when(requests).then(function(responses){
            responses[0].then((res) => {
                $("#jumlah_guru").html(res.data.length)
                $("#jumlah_guru_loading").hide()
            })

            responses[1].then((res) => {
                $("#jumlah_jurusan").html(res.data.length)
                $("#jumlah_jurusan_loading").hide()
            })

            responses[2].then((res) => {
                $("#jumlah_kelas").html(res.data.length)
                $("#jumlah_kelas_loading").hide()
            })

        });

    })

</script>
@endsection
