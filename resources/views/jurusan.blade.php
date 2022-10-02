@extends('layouts.app')

@section('header')
<h1 class="fw-bold">Jurusan</h1>
@endsection

@section('content')

<div class="modal fade" tabindex="-1" id="modalTambahGuru" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Guru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formTambahGuru" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama: </label>
                <input type="text" name="nama" placeholder="Nama guru" class="form-control">
                <div hidden id="validation_nama" class="text-danger">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Foto Guru:</label>
                <input type="file" name="profile" class="form-control">
                <div hidden id="validation_profile" class="text-danger">

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="buttonTambahGuru" class="btn btn-primary">Tambah</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalEditGuru" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Guru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_guru" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama: </label>
                <input type="text" name="nama" placeholder="Nama guru" class="form-control">
                <div hidden id="validation_edit_nama" class="text-danger">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Foto Guru:</label>
                <input type="file" name="profile" class="form-control">
                <div hidden id="validation_edit_profile" class="text-danger">

                </div>
                <br>
                <img id="preview_foto_guru" src="" class="img-fluid rounded-circle mx-3" style="object-fit: cover;width:64px;height:64px;" alt="">
            </div>
        </div>
        <div class="modal-footer">
            <input hidden type="text" id="id_guru" name="id">
            <button type="submit" id="button_edit_guru" class="btn btn-primary">Edit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="bg-white w-100 rounded border p-4">
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahGuru" >Tambah Guru <i class="bi bi-plus"></i></button>
    <table class="table table-bordered w-100" id="table_guru">
        <thead class="bg-primary">
                <td width="5%">No</td>
                <td>Nama</td>
                <td width="5%">Aksi</td>
        </thead>
        <tbody></tbody>
    </table>

</div>
@endsection

@section('js')
<script>
        const table_guru = $("#table_guru").DataTable({
            ajax: "{{ route('get_guru') }}",
            responsive: true,
            columns: [
                { data: 'DT_RowIndex', class: 'text-center' },
                { data: 'nama' },
                { data: 'aksi' }
            ]
        });

    function editGuru(e){
        console.log($(e)[0]);
        const data = $(e).data('json')
        console.log("🚀 ~ file: guru.blade.php ~ line 100 ~ editGuru ~ data", data)
        $("#modalEditGuru").modal('show')
        $("#modalEditGuru").on('shown.bs.modal', () => {

            // initialize
            $("input[name='nama']").val(data.nama)
            $("#id_guru").val(data.id)
            $("#preview_foto_guru").prop('src', `{{ url('image/guru/${data.profile}') }}`)

            // submit handler
            $("#form_edit_guru").submit((e) => {
                e.preventDefault()
                console.log("submit");
                const formData = new FormData($("#form_edit_guru")[0])
                $('#button_edit_guru').prop('disabled', true);
                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('edit_guru') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $('#button_edit_guru').prop('disabled', false);
                        console.log("🚀 ~ file: guru.blade.php ~ line 87 ~ $ ~ res", res)
                        if(res.status){
                            table_guru.ajax.reload()
                            $("#modalEditGuru").modal('hide')
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
                        $('#button_edit_guru').prop('disabled', false);
                        console.log(res);
                    }
                })
            })
        })
    }
    function deleteGuru(id) {
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
                    url:`{{ url('guru/${id}/delete') }}`,
                    success:(res) => {
                        if(res.status){
                            toastr.success(res.message)
                            table_guru.ajax.reload()
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

        $('#role_input').select2()

        $("#modalTambahGuru").on('shown.bs.modal', () => {
            $("#formTambahGuru").submit((e) => {
                e.preventDefault()
                console.log('submit');
                const formData = new FormData($("#formTambahGuru")[0])
                $('#buttonTambahGuru').prop('disabled', true);

                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('tambah_guru') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $('#buttonTambahGuru').prop('disabled', false);
                        console.log("🚀 ~ file: guru.blade.php ~ line 87 ~ $ ~ res", res)
                        if(res.status){
                            toastr.success(res.message)
                            $("#formTambahGuru").trigger('reset')
                            table_guru.ajax.reload()
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
                        $('#buttonTambahGuru').prop('disabled', false);

                        console.log(res);
                    }
                })

            })  
        })

    })
</script>
@endsection

