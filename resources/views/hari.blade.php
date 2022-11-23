@extends('layouts.app')

@section('header')
<h1 class="fw-bold">Hari</h1>
@endsection

@section('content')

<div class="modal fade" tabindex="-1" id="modalTambahJurusan" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Hari</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formTambahJurusan" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama: </label>
                <input type="text" name="nama_hari" placeholder="Nama hari" class="form-control">
                <div hidden id="validation_nama_hari" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Urut: </label>
                <input type="text" name="urut" placeholder="Hari ke ..." class="form-control">
                <div hidden id="validation_urut" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="buttonTambahJurusan" class="btn btn-primary">Tambah</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalEditJurusan" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Hari</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_hari" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama: </label>
                <input type="text" name="nama_hari" placeholder="Nama hari" class="form-control">
                <div hidden id="validation_edit_nama_hari" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Urut: </label>
                <input type="text" name="urut" placeholder="Hari ke ..." class="form-control">
                <div hidden id="validation_edit_urut" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input hidden type="text" id="id_hari" name="id">
            <button type="submit" id="button_edit_hari" class="btn btn-primary">Edit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="bg-white w-100 rounded border p-4">
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahJurusan" >Tambah Hari <i class="bi bi-plus"></i></button>
    <table class="table table-bordered w-100" id="table_hari">
        <thead class="bg-primary">
                <td width="5%">Urut</td>
                <td>Nama</td>
                <td width="5%">Aksi</td>
        </thead>
        <tbody></tbody>
    </table>

</div>
@endsection

@section('js')
<x-script />
<script>
        const table_hari = $("#table_hari").DataTable({
            ajax: "{{ route('get_hari') }}",
            responsive: true,
            columns: [
                { data: 'urut', class: 'text-center' },
                { data: 'nama_hari' },
                { data: 'aksi' }
            ]
        });

    function editJurusan(e){
        
        const data = $(e).data('json')
        
        $("#modalEditJurusan").modal('show')
        $("#modalEditJurusan").on('shown.bs.modal', () => {

            // initialize
            $("input[name='nama_hari']").val(data.nama_hari)
            $("input[name='urut']").val(data.urut)
            $("#id_hari").val(data.id)

            // submit handler
            $("#form_edit_hari").off().one('submit',(e) => {
                e.preventDefault()
                $(`input`).removeClass('is-invalid')
                
                const formData = new FormData($("#form_edit_hari")[0])
                $('#button_edit_hari').prop('disabled', true);
                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('edit_hari') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#button_edit_hari').prop('disabled', false);
                        
                        if(res.status){
                            table_hari.ajax.reload()
                            $("#modalEditJurusan").modal('hide')
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
                        $('#button_edit_hari').prop('disabled', false);
                        
                    }
                })
            })
        })
    }
    function deleteJurusan(id) {
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
                    url:`{{ url('hari/${id}/delete') }}`,
                    success:(res) => {
                        if(res.status){
                            toastr.success(res.message)
                            table_hari.ajax.reload()
                        }else{
                            toastr.error(res.message)
                        }
                    }
                })
            }
        })
    }
    $(document).ready(() => {
        

        $('#role_input').select2()

        $("#modalTambahJurusan").on('shown.bs.modal', () => {
            $("#formTambahJurusan").off().on('submit',(e) => {
                e.preventDefault()
                
                const formData = new FormData($("#formTambahJurusan")[0])
                $('#buttonTambahJurusan').prop('disabled', true);

                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('tambah_hari') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#buttonTambahJurusan').prop('disabled', false);
                        
                        if(res.status){
                            toastr.success(res.message)
                            $("#formTambahJurusan").trigger('reset')
                            table_hari.ajax.reload()
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
                        $('#buttonTambahJurusan').prop('disabled', false);

                        
                    }
                })

            })  
        })

    })
</script>
@endsection

