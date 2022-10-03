@extends('layouts.app')

@section('header')
<h1 class="fw-bold">Semester</h1>
@endsection

@section('content')

<div class="modal fade" tabindex="-1" id="modalTambahSemester" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Semester</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formTambahSemester" action="">
            @csrf
            <div class="mb-3">
                <label for="">Semester ke : </label>
                <input type="number" min="1" name="nama_semester" placeholder="Semester Ke" class="form-control">
                <div hidden id="validation_nama_semester" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Tanggal: </label>
                <input type="date" name="tanggal_semester" placeholder="Tanggal Semester" class="form-control">
                <div hidden id="validation_tanggal
                _semester" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="buttonTambahSemester" class="btn btn-primary">Tambah</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalEditSemester" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Semester</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_semester" action="">
            @csrf
            <div class="mb-3">
                <label for="">Semester: </label>
                <input type="number" min="1" name="nama_semester" placeholder="Semester Ke" class="form-control">
                <div hidden id="validation_edit_nama_semester" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Tanggal Semester: </label>
                <input type="text" name="tanggal_semester" placeholder="Tanggal Semester" class="form-control">
                <div hidden id="validation_edit_tanggal_semester" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input hidden type="text" id="id_semester" name="id">
            <button type="submit" id="button_edit_semester" class="btn btn-primary">Edit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="bg-white w-100 rounded border p-4">
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahSemester" >Tambah Semester <i class="bi bi-plus"></i></button>
    <table class="table table-bordered w-100" id="table_semester">
        <thead class="bg-primary">
                <td width="5%">No</td>
                <td>Semester</td>
                <td>Tanggal</td>
                <td width="5%">Aksi</td>
        </thead>
        <tbody></tbody>
    </table>

</div>
@endsection

@section('js')
<x-script />
<script>
        const table_semester = $("#table_semester").DataTable({
            ajax: "{{ route('get_semester') }}",
            responsive: true,
            columns: [
                { data: 'DT_RowIndex', class: 'text-center' },
                { data: 'nama_semester' },
                { data: 'tanggal_semester' },
                { data: 'aksi' }
            ]
        });

    function editSemester(e){
        console.log($(e)[0]);
        const data = $(e).data('json')
        console.log("🚀 ~ file: semester.blade.php ~ line 100 ~ editSemester ~ data", data)
        $("#modalEditSemester").modal('show')
        $("#modalEditSemester").on('shown.bs.modal', () => {

            // initialize
            $("input[name='nama_semester']").val(data.nama_semester)
            $("input[name='tanggal_semester']").val(data.tanggal_semester)
            $("#id_semester").val(data.id)
            $("#preview_foto_semester").prop('src', `{{ url('image/semester/${data.profile}') }}`)

            // submit handler
            $("#form_edit_semester").off().on('submit',(e) => {
                e.preventDefault()
                $(`input`).removeClass('is-invalid')
                console.log("submit");
                const formData = new FormData($("#form_edit_semester")[0])
                $('#button_edit_semester').prop('disabled', true);
                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('edit_semester') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#button_edit_semester').prop('disabled', false);
                        console.log("🚀 ~ file: semester.blade.php ~ line 87 ~ $ ~ res", res)
                        if(res.status){
                            table_semester.ajax.reload()
                            $("#modalEditSemester").modal('hide')
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
                        $('#button_edit_semester').prop('disabled', false);
                        console.log(res);
                    }
                })
            })
        })
    }
    function deleteSemester(id) {
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
                    url:`{{ url('semester/${id}/delete') }}`,
                    success:(res) => {
                        if(res.status){
                            toastr.success(res.message)
                            table_semester.ajax.reload()
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

        $("#modalTambahSemester").on('shown.bs.modal', () => {
            $("#formTambahSemester").off().on('submit',(e) => {
                e.preventDefault()
                console.log('submit');
                const formData = new FormData($("#formTambahSemester")[0])
                $('#buttonTambahSemester').prop('disabled', true);

                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('tambah_semester') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#buttonTambahSemester').prop('disabled', false);
                        console.log("🚀 ~ file: semester.blade.php ~ line 87 ~ $ ~ res", res)
                        if(res.status){
                            toastr.success(res.message)
                            $("#formTambahSemester").trigger('reset')
                            table_semester.ajax.reload()
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
                        $('#buttonTambahSemester').prop('disabled', false);

                        console.log(res);
                    }
                })

            })  
        })

    })
</script>
@endsection

