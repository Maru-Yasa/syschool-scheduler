@extends('layouts.app')

@section('header')
<h1 class="fw-bold">Kelas</h1>
@endsection

@section('content')

<div class="modal fade" id="modalTambahKelas" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kelas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formTambahKelas" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama: </label>
                <input type="text" name="nama_kelas" placeholder="Nama kelas" class="form-control">
                <div hidden id="validation_nama_kelas" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3 d-flex flex-column">
                <label for="">Jurusan: </label>
                <select type="text" name="id_jurusan" placeholder="Jurusan kelas" class="form-control">
                    <option value="" disabled selected>-- Pilih Jurusan --</option>
                </select>
                <div hidden id="validation_id_jurusan" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="buttonTambahKelas" class="btn btn-primary">Tambah</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" id="modalEditKelas" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Kelas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_kelas" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama: </label>
                <input type="text" name="nama_kelas" placeholder="Nama kelas" class="form-control">
                <div hidden id="validation_edit_nama_kelas" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3 d-flex flex-column">
                <label for="">Jurusan: </label>
                <select type="text" name="id_jurusan" placeholder="Jurusan kelas" class="form-control">
                    <option value="" disabled selected>-- Pilih Jurusan --</option>
                </select>
                <div hidden id="validation_edit_id_jurusan" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input hidden type="text" id="id_kelas" name="id">
            <button type="submit" id="button_edit_kelas" class="btn btn-primary">Edit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="bg-white w-100 rounded border p-4">
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahKelas" >Tambah Kelas <i class="bi bi-plus"></i></button>
    <table class="table table-bordered w-100" id="table_kelas">
        <thead class="bg-primary">
                <td width="5%">No</td>
                <td>Nama</td>
                <td>Jurusan</td>
                <td width="5%">Aksi</td>
        </thead>
        <tbody></tbody>
    </table>

</div>
@endsection

@section('js')
<x-script />
<script>
        const table_kelas = $("#table_kelas").DataTable({
            ajax: "{{ route('get_kelas') }}",
            responsive: true,
            columns: [
                { data: 'DT_RowIndex', class: 'text-center' },
                { data: 'nama_kelas' },
                { data: 'jurusan' },
                { data: 'aksi' }
            ]
        });

    const configSelect = {
            ajax: {
                url: "{{ route('get_jurusan') }}",
                data: function (params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (res) {
                    const data = res.data
                    let selectData = []
                    data.forEach((val) => {
                        selectData.push({
                            id: val.id,
                            text: val.nama_jurusan
                        })
                    })
                    return {
                        results: selectData
                    };
                }
            }
        }

    function editKelas(e){
        console.log($(e)[0]);
        const data = $(e).data('json')
        console.log("🚀 ~ file: kelas.blade.php ~ line 100 ~ editKelas ~ data", data)
        $("#modalEditKelas").modal('show')
        $("#modalEditKelas").on('shown.bs.modal', () => {

            // initialize
            $("input[name='nama_kelas']").val(data.nama_kelas)
            $("select[name=id_jurusan]").val(data.id_jurusan).trigger('change')
            $("#id_kelas").val(data.id)
            $("#preview_foto_kelas").prop('src', `{{ url('image/kelas/${data.profile}') }}`)

            // submit handler
            $("#form_edit_kelas").off().on('submit',(e) => {
                e.preventDefault()
                $(`input`).removeClass('is-invalid')
                console.log("submit");
                const formData = new FormData($("#form_edit_kelas")[0])
                $('#button_edit_kelas').prop('disabled', true);
                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('edit_kelas') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#button_edit_kelas').prop('disabled', false);
                        console.log("🚀 ~ file: kelas.blade.php ~ line 87 ~ $ ~ res", res)
                        if(res.status){
                            table_kelas.ajax.reload()
                            $("#modalEditKelas").modal('hide')
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
                        $('#button_edit_kelas').prop('disabled', false);
                        console.log(res);
                    }
                })
            })
        })
    }
    function deleteKelas(id) {
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
                    url:`{{ url('kelas/${id}/delete') }}`,
                    success:(res) => {
                        if(res.status){
                            toastr.success(res.message)
                            table_kelas.ajax.reload()
                        }else{
                            toastr.error(res.message)
                        }
                    }
                })
            }
        })
    }
    $(document).ready(() => {
        console.log("HI");
        $('select[name=id_jurusan]').select2()
        $.ajax({
            type:'get',
            url: "{{ route('get_jurusan') }}",
            success: (res) => {
                const data = res.data
                    let selectData = []
                    data.forEach((val) => {
                        selectData.push({
                            id: val.id,
                            text: val.nama_jurusan
                        })
                    })
                    $('select[name=id_jurusan]').select2({data: selectData})
            }
        })
        $("#modalTambahKelas").on('shown.bs.modal', () => {
            $("#formTambahKelas").off().on('submit',(e) => {
                e.preventDefault()
                console.log('submit');
                const formData = new FormData($("#formTambahKelas")[0])
                $('#buttonTambahKelas').prop('disabled', true);

                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('tambah_kelas') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#buttonTambahKelas').prop('disabled', false);
                        console.log("🚀 ~ file: kelas.blade.php ~ line 87 ~ $ ~ res", res)
                        if(res.status){
                            toastr.success(res.message)
                            $("#formTambahKelas").trigger('reset')
                            table_kelas.ajax.reload()
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
                        $('#buttonTambahKelas').prop('disabled', false);

                        console.log(res);
                    }
                })

            })  
        })

    })
</script>
@endsection

