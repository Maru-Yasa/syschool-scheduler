@extends('layouts.app')

@section('header')
<h1 class="fw-bold">Ruang Kelas</h1>
@endsection
 
@section('content')

<div class="modal fade" tabindex="-1" id="modalTambahRuangKelas" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Ruang Kelas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formTambahRuangKelas" acion="">
            @csrf
            <div class="mb-3">
                <label for="">Nama Ruang Kelas : </label>
                <input type="text" name="nama" placeholder="Nama mata pelajaran" class="form-control">
                <div hidden id="validation_nama" class="text-danger">

                </div>
            </div>
            <div class="mb-3 d-flex flex-column">
                <label for="">Jurusan: </label>
                <select type="text" name="owner" placeholder="Jurusan kelas" class="form-control">
                    <option value="" disabled selected>-- Pilih Jurusan --</option>
                    <option value="-">-</option>
                </select>
                <div hidden id="validation_owner" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="buttonTambahRuangKelas" class="btn btn-primary">Tambah</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalEditRuangKelas" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Ruang Kelas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_ruang_kelas" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama Ruang Kelas : </label>
                <input type="text" name="nama" placeholder="Nama mata pelajaran" class="form-control">
                <div hidden id="validation_edit_nama" class="text-danger">

                </div>
            </div>
            <div class="mb-3 d-flex flex-column">
                <label for="">Jurusan: </label>
                <select type="text" name="owner" placeholder="Jurusan kelas" class="form-control">
                    <option value="" disabled selected>-- Pilih Jurusan --</option>
                </select>
                <div hidden id="validation_owner" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input hidden type="text" id="ruang_kelas" name="id">
            <button type="submit" id="button_edit_ruangKelas" class="btn btn-primary">Edit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="bg-white w-100 rounded border p-4">
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahRuangKelas" >Tambah Ruang Kelas <i class="bi bi-plus"></i></button>
    <table class="table table-bordered w-100" id="table_ruang_kelas">
        <thead class="bg-primary">
                <td width="5%">No</td>
                <td>Ruang Kelas</td>
                <td>Owner</td>
                <td width="5%">Aksi</td>
        </thead>
        <tbody></tbody>
    </table>

</div>
@endsection

@section('js')
<x-script /> 
<script>
        const table_ruang_kelas = $("#table_ruang_kelas").DataTable({
            ajax: "{{ route('get_ruang_kelas') }}",
            responsive: true,
            columns: [
                { data: 'DT_RowIndex', class: 'text-center' },
                { data: 'nama' },
                { data: 'owner' },
                { data: 'aksi' }
            ]
        });

    function editRuangKelas(e){
        
        const data = $(e).data('json')
        
        $("#modalEditRuangKelas").modal('show')
        $("#modalEditRuangKelas").on('shown.bs.modal', () => {

            // initialize
            $("input[name='nama']").val(data.nama)
            $("#id_ruang_kelas").val(data.id)
            $("#preview_foto_guru").prop('src', `{{ url('image/guru/${data.profile}') }}`)

            // submit handler
            $("#button_edit_guru").off().on('click', (e) => {
                
                const formData = new FormData($("#form_edit_guru")[0])
                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('edit_ruang_kelas') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        
                        if(res.status){
                            table_ruang_kelas.ajax.reload()
                            $("#modalEditRuang").modal('hide')
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
                        $('#button_edit_ruang_kelas').prop('disabled', false);
                        
                    },
                    complete: () => {
                        $('#button_edit_ruang_kelas').prop('disabled', false);
                    }
                })
                $('#button_edit_ruang_kelas').prop('disabled', true);
            })
        })
    }
    function deleteRuangKelas(id) {
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
                    url:`{{ url('ruang_kelas/${id}/delete') }}`,
                    success:(res) => {
                        if(res.status){
                            toastr.success(res.message)
                            table_ruang_kelas.ajax.reload()
                        }else{
                            toastr.error(res.message)
                        }
                    }
                })
            }
        })
    }
    $(document).ready(() => {
        

        $('select[name=owner]').select2()
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
                    $('select[name=owner]').select2({data: selectData})
            }
        })
        $("#modalTambahRuangKelas").on('shown.bs.modal', () => {
            $("#formTambahRuangKelas").off().on('submit', (e) => {
                e.preventDefault()
                
                const formData = new FormData($("#formTambahRuangKelas")[0])
                $('#buttonTambahRuang').prop('disabled', true);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="my-csrf-token"]').attr('content')
                    },
                    type: 'post',
                    method: 'post',
                    url: "{{ route('tambah_ruang_kelas') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $('#buttonTambahRuang').prop('disabled', false);
                        
                        if(res.status){
                            toastr.success(res.message)
                            $("#formTambahRuang").trigger('reset')
                            table_ruang_kelas.ajax.reload()
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
                        $('#buttonTambahRuang').prop('disabled', false);

                        
                    }
                })

            })  
        })

    })
</script>
@endsection

