@extends('layouts.app')

@section('header')
<h1 class="fw-bold">Jurusan</h1>
@endsection

@section('content')

<div class="modal fade" tabindex="" id="modalTambahJurusan" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Jurusan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="formTambahJurusan" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama: </label>
                <input type="text" name="nama_jurusan" placeholder="Nama jurusan" class="form-control">
                <div hidden id="validation_nama_jurusan" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3 d-flex flex-column">
                <label for="">Icon Jurusan: </label>
                <select type="text" id="jurusan_icon" name="icon" placeholder="Icon jurusan" class="form-control">
                    <option value="" disabled selected>-- Pilih Icon --</option>
                </select>
                <div hidden id="validation_icon" class="text-danger validation">

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

<div class="modal fade" tabindex="" id="modalEditJurusan" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Jurusan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_edit_jurusan" action="">
            @csrf
            <div class="mb-3">
                <label for="">Nama: </label>
                <input type="text" name="nama_jurusan" placeholder="Nama jurusan" class="form-control">
                <div hidden id="validation_edit_nama_jurusan" class="text-danger validation">

                </div>
            </div>
            <div class="mb-3 d-flex flex-column">
                <label for="">Icon Jurusan: </label>
                <select type="text" id="jurusan_icon" name="icon" placeholder="Icon jurusan" class="jurusan_icon form-control">
                    <option value="" disabled selected>-- Pilih Icon --</option>
                </select>
                <div hidden id="validation_edit_icon" class="text-danger validation">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input hidden type="text" id="id_jurusan" name="id">
            <button type="submit" id="button_edit_jurusan" class="btn btn-primary">Edit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="bg-white w-100 rounded border p-4">
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambahJurusan" >Tambah Jurusan <i class="bi bi-plus"></i></button>
    <table class="table table-bordered w-100" id="table_jurusan">
        <thead class="bg-primary">
                <td width="5%">No</td>
                <td width="5%">Icons</td>
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
        const table_jurusan = $("#table_jurusan").DataTable({
            ajax: "{{ route('get_jurusan') }}",
            responsive: true,
            columns: [
                { data: 'DT_RowIndex', class: 'text-center' },
                { data: 'icon', class: 'text-center' },
                { data: 'nama_jurusan' },
                { data: 'aksi' }
            ]
        });

    function editJurusan(e){
        console.log($(e)[0]);
        const data = $(e).data('json')
        console.log("🚀 ~ file: jurusan.blade.php ~ line 100 ~ editJurusan ~ data", data)
        $("#modalEditJurusan").modal('show')
        $("#modalEditJurusan").on('shown.bs.modal', () => {

            // initialize
            $("input[name='nama_jurusan']").val(data.nama_jurusan)
            $("#id_jurusan").val(data.id)
            $("#preview_foto_jurusan").prop('src', `{{ url('image/jurusan/${data.profile}') }}`)
            $("[id=jurusan_icon]").val(data.icon).trigger('change')


            // submit handler
            $("#form_edit_jurusan").off().on('submit',(e) => {
                e.preventDefault()
                $(`input`).removeClass('is-invalid')
                console.log("submit");
                const formData = new FormData($("#form_edit_jurusan")[0])
                $('#button_edit_jurusan').prop('disabled', true);
                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('edit_jurusan') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#button_edit_jurusan').prop('disabled', false);
                        console.log("🚀 ~ file: jurusan.blade.php ~ line 87 ~ $ ~ res", res)
                        if(res.status){
                            table_jurusan.ajax.reload()
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
                        $('#button_edit_jurusan').prop('disabled', false);
                        console.log(res);
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
                    url:`{{ url('jurusan/${id}/delete') }}`,
                    success:(res) => {
                        if(res.status){
                            toastr.success(res.message)
                            table_jurusan.ajax.reload()
                        }else{
                            toastr.error(res.message)
                        }
                    }
                })
            }
        })
    }

    function renderInputIcon(id) {
        let biIcons = [];
        $.ajax({
            type: 'get', 
            url: "https://gist.githubusercontent.com/Maru-Yasa/0660e0f13ebe6dbebfcaf65f32ef9479/raw/1ec4305acd6da2f0e220a72e64e384d83d2b4971/bi-icons.json",
            success: (res) => {                
                const icons = JSON.parse(res).icons
                icons.forEach((val) => {
                    biIcons.push({
                        id: val,
                        text: `<i class="bi ${val}"></i>`
                    })
                })
                $(`[id=${id}]`).select2({
                    data: biIcons,
                    escapeMarkup: function (text) { return text; },
                })

            }
        })
    }

    $(document).ready(() => {
        console.log("HI");

        renderInputIcon('jurusan_icon')

        $("#modalTambahJurusan").on('shown.bs.modal', () => {
            $("#formTambahJurusan").off().on('submit',(e) => {
                e.preventDefault()
                console.log('submit');
                const formData = new FormData($("#formTambahJurusan")[0])
                $('#buttonTambahJurusan').prop('disabled', true);

                $.ajax({
                    type: 'post',
                    method: 'post',
                    url: "{{ route('tambah_jurusan') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        $(`input`).removeClass('is-invalid')
                        $('.validation').empty().prop('hidden', true)
                        $('#buttonTambahJurusan').prop('disabled', false);
                        console.log("🚀 ~ file: jurusan.blade.php ~ line 87 ~ $ ~ res", res)
                        if(res.status){
                            toastr.success(res.message)
                            $("#formTambahJurusan").trigger('reset')
                            table_jurusan.ajax.reload()
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
                        $('#buttonTambahJurusan').prop('disabled', false);

                        console.log(res);
                    }
                })

            })  
        })

    })
</script>
@endsection

