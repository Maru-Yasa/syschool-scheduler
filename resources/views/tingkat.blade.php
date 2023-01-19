@extends('layouts.app')

@section('header')
<h1 class="fw-bold">Tingkat Kelas</h1>
@endsection

@section('content')

<div class="modal fade" id="modalTambahKelas" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Tingkat Kelas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formTambahKelas" action=""> 
            @csrf
            <div class="mb-3">
                <label for="">Nama Tingkat: </label>
                <input type="text" name="tingkat" placeholder="Nama kelas" class="form-control">
                <div hidden id="validation_tingkat" class="text-danger validation">

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
          <h5 class="modal-title">Edit TingkatKelas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_kelas" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama Tingkat: </label>
                <input type="text" name="tingkat" placeholder="Nama kelas" class="form-control">
                <div hidden id="validation_edit_tingkat" class="text-danger validation">

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
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahKelas" >Tambah TingkatKelas <i class="bi bi-plus"></i></button>
    <table class="table table-bordered w-100" id="table_tingkat">
        <thead class="bg-primary">
                <td width="5%">No</td>
                <td>Nama Tingkat</td>
                <td width="5%">Aksi</td>
        </thead>
        <tbody></tbody>
    </table>

</div>
@endsection

@section('js')
<x-script />
<script>
        const table_tingkat = $("#table_tingkat").DataTable({
            ajax: "{{ route('get_tingkat') }}",
            responsive: true,
            columns: [
                { data: 'DT_RowIndex', class: 'text-center' },
                { data: 'tingkat' },
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

    function editTingkatKelas(e){
        
        const data = $(e).data('json')
        
        $("#modalEditKelas").modal('show')
        $("#modalEditKelas").on('shown.bs.modal', () => {

            // initialize
            $("input[name='tingkat']").val(data.tingkat)
            $("select[name=id_jurusan]").val(data.id_jurusan).trigger('change')
            $("#id_kelas").val(data.id)
            $("#preview_foto_kelas").prop('src', `{{ url('image/kelas/${data.profile}') }}`)

            // submit handler
            $("#form_edit_kelas").off().on('submit',(e) => {
                e.preventDefault()
                $(`input`).removeClass('is-invalid')
                
                const formData = new FormData($("#form_edit_kelas")[0])
                $('#button_edit_kelas').prop('disabled', true);
                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('edit_tingkat') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#button_edit_kelas').prop('disabled', false);
                        
                        if(res.status){
                            table_tingkat.ajax.reload()
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
                        
                    }
                })
            })
        })
    }
    function deleteTingkatKelas(id) {
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
                    url:`{{ url('tingkat/${id}/delete') }}`,
                    success:(res) => {
                        if(res.status){
                            toastr.success(res.message)
                            table_tingkat.ajax.reload()
                        }else{
                            toastr.error(res.message)
                        }
                    }
                })
            }
        })
    }
    $(document).ready(() => {
        
        $("#modalTambahKelas").on('shown.bs.modal', () => {
            $("#formTambahKelas").off().on('submit',(e) => {
                e.preventDefault()
                
                const formData = new FormData($("#formTambahKelas")[0])
                $('#buttonTambahKelas').prop('disabled', true);

                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('tambah_tingkat') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#buttonTambahKelas').prop('disabled', false);
                        
                        if(res.status){
                            toastr.success(res.message)
                            $("#formTambahKelas").trigger('reset')
                            table_tingkat.ajax.reload()
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
                        $('#buttonTambahKelas').prop('disabled', false);

                        
                    }
                })

            })  
        })

    })
</script>
@endsection

