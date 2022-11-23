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
                        <h3><span id="display_durasi_jp">0</span> menit</h3>
                        <p>Durasi Jam Pembelajaran</p>
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
                        <h3><span id="display_hari_aktif">0</span> hari</h3>
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
                        <h3><span id="display_jumlah_jp">0</span> Jam</h3>
                        <p>Jumlah Jam Pembelajaran</p>
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
                        <span id="jumlah_mapel" class="info-box-number">Loading...</span>
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
                                <label for="">Semester Sekarang:</label>
                                <select class="form-control" name="id_semester" id="id_semester">

                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Logo Sekolah:</label>
                                <input type="file" name="logo" class="form-control" placeholder="Logo sekolah">
                                <div hidden id="validation_nama_sekolah" class="text-danger validation">

                                </div>
                                <div class="p-3 mt-3">
                                    <img src="" id="preview_logo" class="img-fluid" width="128" alt="">
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
                        <form action="" id="edit_setting_jp">
                            @csrf
                            <div class="mb-3 d-flex flex-column">
                                <label for="">Mulai Jam Pembelajaran:</label>
                                <input type="text" name="mulai_jp" class="form-control" placeholder="00:00">
                                <small class="form-text text-muted">Mulainya jam pembelajaran</small>
                                <div hidden id="validation_mulai_jp" class="text-danger validation">

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="">Jumlah Jam Pembelajaran:</label>
                                <input type="number" name="jumlah_jp" class="form-control" placeholder="Jumlah jam pelajaran">
                                <small class="form-text text-muted">Jumlah max jam pembelajaran dalam satu hari</small>
                                <div hidden id="validation_jumlah_jp" class="text-danger validation">

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="">Lama Jam Pembelajaran (menit):</label>
                                <input type="number" name="durasi_jp" class="form-control" placeholder="Lama satu jam pelajaran">
                                <small class="form-text text-muted">Lamanya setiap satu jam pembelajaran</small>
                                <div hidden id="validation_durasi_jp" class="text-danger validation">

                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" id="#id_setting_jp" name="id" hidden>
                                <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>                
            </div>

        </div>
    </div>

{{-- modal --}}

<div class="modal fade" tabindex="" id="modal_tambah_jeda" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Jeda</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_tambah_jeda" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama Jeda: </label>
                <input type="text" name="nama_jeda" placeholder="Nama jeda" class="form-control">
                <div hidden id="validation_nama_jeda" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Mulai Jeda (JP): </label>
                <input type="number" name="mulai_jeda" placeholder="Mulai jeda" class="form-control">
                <div hidden id="validation_mulai_jeda" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Durasi Jeda (Menit): </label>
                <input type="number" name="durasi_jeda" placeholder="Durasi jeda" class="form-control">
                <div hidden id="validation_durasi_jeda" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="buttonTambahJurusan" class="btn btn-primary">Edit</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="" id="modal_edit_jeda" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Jeda</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_jeda" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama Jeda: </label>
                <input type="text" name="nama_jeda" placeholder="Nama jeda" class="form-control edit">
                <div hidden id="validation_edit_nama_jeda" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Mulai Jeda (JP): </label>
                <input type="number" name="mulai_jeda" placeholder="Mulai jeda" class="form-control edit">
                <div hidden id="validation_edit_mulai_jeda" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Durasi Jeda (Menit): </label>
                <input type="number" name="durasi_jeda" placeholder="Durasi jeda" class="form-control edit">
                <div hidden id="validation_edit_durasi_jeda" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="text" id="id_jeda" name="id" hidden>
          <button type="submit" id="buttonTambahJurusan" class="btn btn-primary">Tambah</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

{{-- end modal --}}

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <i class="bi bi-gear-fill"></i> Setting Jeda
                </div>
                <div class="card-body">
                    <button id="button_tambah_jeda" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Tambah Jeda</button>
                    <table class="table table-bordered" id="table_jeda">
                        <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama Jeda</th>
                                <th>Mulai Jeda (JP)</th>
                                <th>Durasi Jeda</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<x-script />
<script>

    function renderDisplay(){
        $.ajax({
            type: 'get',
            url: "{{ route('get_setting_jp') }}",
            success: (res) => {
                if(res.data != null || res.data != undefined){

                    $('input[name=jumlah_jp]').val(res.data.jumlah_jp)
                    $('input[name=mulai_jp]').val(res.data.mulai_jp)
                    $('input[name=durasi_jp]').val(res.data.durasi_jp)
                    $('#id_setting_jp').val(res.data.id)

                    $("#display_durasi_jp").html(res.data.durasi_jp)
                    $("#display_jumlah_jp").html(res.data.jumlah_jp)
                }
            }
        })
    }

    function editSettingJeda(e){
        
        const data = $(e).data('json')
        
        $("#modal_edit_jeda").modal('show')
        $("#modal_edit_jeda").on('shown.bs.modal', () => {

            // initialize
            $("input[name='nama_jeda'].edit").val(data.nama_jeda)
            $("input[name='mulai_jeda'].edit").val(data.mulai_jeda)
            $("input[name='durasi_jeda'].edit").val(data.durasi_jeda)
            $("#id_jeda").val(data.id)


            // submit handler
            $("#form_edit_jeda").off().on('submit',(e) => {
                e.preventDefault()
                $(`input`).removeClass('is-invalid')
                
                const formData = new FormData($("#form_edit_jeda")[0])
                $('#button_edit_jeda').prop('disabled', true);
                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('edit_jeda') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#button_edit_jurusan').prop('disabled', false);
                        
                        if(res.status){
                            table_jeda.ajax.reload()
                            $("#modal_edit_jeda").modal('hide')
                            toastr.success(res.message)
                        }else{
                            toastr.error(res.message)
                            Object.keys(res.messages).forEach((value, key) => {
                                $(`*[name=${value}]`).addClass('is-invalid')
                                $(`#validation_edit_${value}`).html(res.messages[value])
                                $(`#validation_edit_${value}`).prop('hidden', false)                               
                            })
                        }
                    },
                    error: (res) => {
                        $('#button_edit_jeda').prop('disabled', false);
                        
                    }
                })
            })
        })
    }

    function deleteSettingJeda(id) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: "Anda yakin ingin menghapus data ini?",
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "tidak",
            confirmButtonColor: '#ff4444',
            iconColor: '#ff4444',
        }).then((e) => {
            if(e.isConfirmed){
                $.ajax({
                    type:'get',
                    method: 'get',
                    url:`{{ url('setting_jeda/${id}/delete') }}`,
                    success:(res) => {
                        if(res.status){
                            toastr.success(res.message)
                            table_jeda.ajax.reload()
                        }else{
                            toastr.error(res.message)
                        }
                    }
                })
            }
        })
    }

    const table_jeda = $("#table_jeda").DataTable({
        ajax: "{{ route('get_jeda') }}",
        responsive: true,
        columns: [
            { data: 'DT_RowIndex', class: 'text-center' },
            { data: 'nama_jeda' },
            { data: 'mulai_jeda' },
            { data: 'durasi_jeda' },
            { data: 'aksi' },
        ]
    })

    $(document).ready(() => {
        $("input[name=mulai_jp]").clockTimePicker();

        $.ajax({
            type: 'get',
            url: `{{ route('get_semester') }}`,
            success: (res) => {
                let data = [];
                res.data.forEach(semester => {
                    data.push({
                        id: semester.id,
                        text: semester.nama_semester
                    })
                });
                
                $("select[name=id_semester]").select2({
                    data: data
                })
            }
        })

        $("#modal_tambah_jeda").on('shown.bs.modal', () => {
            $("#form_tambah_jeda").off().on('submit',(e) => {
                e.preventDefault()
                
                const formData = new FormData($("#form_tambah_jeda")[0])
                $('#button_tambah_jeda').prop('disabled', true);

                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('tambah_jeda') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#button_tambah_jeda').prop('disabled', false);
                        
                        if(res.status){
                            toastr.success(res.message)
                            $("#form_tambah_jeda").trigger('reset')
                            table_jeda.ajax.reload()
                        }else{
                            toastr.error(res.message)
                            Object.keys(res.messages).forEach((value, key) => {
                                $(`*[name=${value}]`).addClass('is-invalid')
                                
                                $(`#validation_${value}`).html(res.messages[value])
                                $(`#validation_${value}`).prop('hidden', false)                               
                            })
                        }
                    },
                    error: (res) => {
                        $('#button_tambah_jeda').prop('disabled', false);

                        
                    }
                })

            })  
        })
        // tambah jeda handler
        $("#button_tambah_jeda").off().on('click', () => {
            $("#modal_tambah_jeda").modal('show')
        })

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
                        
                        if(res.status){
                            toastr.success(res.message)
                        }else{
                            toastr.error(res.message)
                            Object.keys(res.messages).forEach((value, key) => {
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


        // initiliase data settingjp

        renderDisplay()

        $.ajax({
            type: 'get',
            url: "{{ route('get_hari') }}",
            success: (res) => {
                const hariCount = res.data.length

                $("#display_hari_aktif").html(hariCount)
            }
        })

        // handle edit settingJP
        $("#edit_setting_jp").off().on('submit', (e) => {
            e.preventDefault()
            const formData = new FormData($("#edit_setting_jp")[0])

            $.ajax({
                type: 'post',
                method: 'post',
                url: "{{ route('edit_setting_jp') }}",
                processData: false,
                contentType: false,
                data: formData,
                success: (res) => {
                    $(`input`).removeClass('is-invalid')
                    $('.validation').empty().prop('hidden', true)
                    
                    if(res.status){
                        toastr.success(res.message)
                        renderDisplay()
                    }else{
                        toastr.error(res.message)
                        Object.keys(res.messages).forEach((value, key) => {
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

        // initialize data setting umum
        $('select').select2()

        $.ajax({
            type: 'get',
            url: "{{ route('get_setting_umum') }}",
            success: (res) => {
                if(res.data != null || res.data != undefined){
                    $("input[name=nama_sekolah]").val(res.data.nama_sekolah)
                    $("select[name=tingkat]").val(res.data.tingkat).trigger('change')
                    $("select[name=id_semester]").val(res.data.id_semester).trigger('change')
                    $("textarea[name=alamat]").val(res.data.alamat)
                    $("input[name=id]").val(res.data.id)
                    $("#preview_logo").prop('src', `image/logo/${res.data.logo}`)
                }
            }
        })

        const requests = [
            $.ajax({url: "{{ route('get_guru') }}", dataType: 'json'}),
            $.ajax({url: "{{ route('get_jurusan') }}"}),
            $.ajax({url: "{{ route('get_kelas') }}"}),
            $.ajax({url: "{{ route('get_mapel') }}"}),
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

            responses[3].then((res) => {
                $("#jumlah_mapel").html(res.data.length)
                $("#jumlah_mapel_loading").hide()
            })

        });

    })

</script>
@endsection
