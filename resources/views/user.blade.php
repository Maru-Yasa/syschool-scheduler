@extends('layouts.app')

@section('header')
<h1 class="fw-bold">User</h1>
@endsection
 
@section('content')

<div class="modal fade" tabindex="-1" id="modalTambahUser" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formTambahUser" action="">
            @csrf
            <div class="mb-3">
                <label for="">Username: </label>
                <input type="text" name="username" placeholder="Username" class="form-control">
                <div hidden id="validation_username" class="text-danger">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Nama: </label>
                <input type="text" name="name" placeholder="Nama user" class="form-control">
                <div hidden id="validation_name" class="text-danger">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Email: </label>
                <input type="text" name="email" placeholder="Email" class="form-control">
                <div hidden id="validation_name" class="text-danger">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Password: </label>
                <input type="text" name="password" placeholder="Password" class="form-control">
                <div hidden id="validation_name" class="text-danger">

                </div>
            </div>
            <div class="mb-3 d-flex flex-column">
                <label for="">Role: </label>
                <select class="form-control" name="role" id="role_input">
                    <option selected value="admin">Admin</option>
                    <option value="guru">Guru</option>
                </select>
                <div hidden id="validation_name" class="text-danger">

                </div>
            </div>
            <div class="mb-3 d-flex flex-column" id="guru_input_container">
                <label for="">Guru: </label>
                <select class="form-control" name="id_guru" id="guru_input">
                    @foreach ($master_guru as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                    @endforeach
                </select>
                <div hidden id="validation_name" class="text-danger">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Foto User:</label>
                <input type="file" name="profile" class="form-control">
                <div hidden id="validation_profile" class="text-danger">

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="buttonTambahUser" class="btn btn-primary">Tambah</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalEditUser" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_user" action="">
            @csrf
            <div class="mb-3">
                <label for="">Username: </label>
                <input type="text" name="username" placeholder="Username" class="form-control">
                <div hidden id="validation_username" class="text-danger">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Nama: </label>
                <input type="text" name="name" placeholder="Nama user" class="form-control">
                <div hidden id="validation_name" class="text-danger">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Email: </label>
                <input type="text" name="email" placeholder="Email" class="form-control">
                <div hidden id="validation_email" class="text-danger">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Password: </label>
                <input type="text" name="password" placeholder="Password" class="form-control">
                <div hidden id="validation_password" class="text-danger">

                </div>
            </div>
            <div class="mb-3 d-flex flex-column">
                <label for="">Role: </label>
                <select class="form-control" name="role" id="role_input">
                    <option selected value="admin">Admin</option>
                    <option value="guru">Guru</option>
                </select>
                <div hidden id="validation_role" class="text-danger">

                </div>
            </div>
            <div class="mb-3 d-flex flex-column" id="guru_input_container">
                <label for="">Guru: </label>
                <select class="form-control" name="id_guru" id="guru_input">
                    @foreach ($master_guru as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                    @endforeach
                </select>
                <div hidden id="validation_name" class="text-danger">

                </div>
            </div>
            <div class="mb-3">
                <label for="">Foto User:</label>
                <input type="file" name="profile" class="form-control">
                <div hidden id="validation_edit_profile" class="text-danger">

                </div>
                <br>
                <img id="preview_foto_user" src="" class="img-fluid rounded-circle mx-3" style="object-fit: cover;width:64px;height:64px;" alt="">
            </div>
        </div>
        <div class="modal-footer">
            <input hidden type="text" id="id_user" name="id">
            <button type="submit" id="button_edit_user" class="btn btn-primary">Edit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="bg-white w-100 rounded border p-4">
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahUser" >Tambah User <i class="bi bi-plus"></i></button>
    <table class="table table-bordered w-100" id="table_user">
        <thead class="bg-primary">
                <td width="5%">No</td>
                <td>Nama</td>
                <td>Username</td>
                <td>Guru</td>
                <td>Role</td>
                <td width="5%">Aksi</td>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection

@section('js')
<x-script />
<script>
        const table_user = $("#table_user").DataTable({
            ajax: "{{ route('get_user') }}",
            responsive: true,
            columns: [
                { data: 'DT_RowIndex', class: 'text-center' },
                { data: 'nama' },
                { data: 'username' },
                { data: 'guru' },
                { data: 'role' },
                { data: 'aksi' }
            ]
        });

    function editUser(e){
        
        const data = $(e).data('json')
        
        $("#modalEditUser").modal('show')
        $("#modalEditUser").on('shown.bs.modal', () => {

            // initialize
            $("#form_edit_user input[name='name']").val(data.name)
            $("#form_edit_user input[name='username']").val(data.username)
            $("#form_edit_user input[name='password']").val(data.password)
            $("#form_edit_user input[name='email']").val(data.email)

            $("#form_edit_user select[name='role']").val(data.role).trigger('change')
            $("#form_edit_user select[name='id_guru']").val(data.id_guru).trigger('change')
            
            $("#id_user").val(data.id)
            $("#preview_foto_user").prop('src', `{{ url('image/user/${data.profile}') }}`)

            // submit handler
            $("#button_edit_user").off().on('click', (e) => {
                const formData = new FormData($("#form_edit_user")[0])
                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('edit_user') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        
                        if(res.status){
                            table_user.ajax.reload()
                            $("#modalEditUser").modal('hide')
                            toastr.success(res.message)
                        }else{
                            toastr.error(res.message)
                            Object.keys(res.messages).forEach((value, key) => {
                                $(`#form_edit_user *[name=${value}]`).addClass('is-invalid')
                                
                                $(`#form_edit_user #validation_${value}`).html(res.messages[value])
                                $(`#form_edit_user #validation_${value}`).prop('hidden', false)                               
                            })
                        }
                    },
                    error: (res) => {
                        $('#button_edit_user').prop('disabled', false);
                        
                    },
                    complete: () => {
                        $('#button_edit_user').prop('disabled', false);
                    }
                })
                $('#button_edit_user').prop('disabled', true);
            })
        })
    }
    function deleteUser(id) {
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
                    url:`{{ url('user/${id}/delete') }}`,
                    success:(res) => {
                        if(res.status){
                            toastr.success(res.message)
                            table_user.ajax.reload()
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
        $('#guru_input').select2()

        $('select').select2()

        $("#guru_input").prop('disabled', true)
        $("#role_input").on('select2:select', function(e){
            if(e.target.value === 'guru'){
                $("#guru_input").prop('disabled', false)
            }else{
                $("#guru_input").prop('disabled', true)
            }
        })

        $("#modalTambahUser").on('shown.bs.modal', () => {
            $("#formTambahUser").off().one('submit',(e) => {
                e.preventDefault()
                
                const formData = new FormData($("#formTambahUser")[0])
                $('#buttonTambahUser').prop('disabled', true);

                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('tambah_user') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $('#buttonTambahUser').prop('disabled', false);
                        
                        if(res.status){
                            toastr.success(res.message)
                            $("#formTambahUser").trigger('reset')
                            table_user.ajax.reload()
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
                        $('#buttonTambahUser').prop('disabled', false);

                        
                    }
                })

            })  
        })

    })
</script>
@endsection

