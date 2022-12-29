@extends('layouts.app')

@section('header')
<h1 class="fw-bold">Auto Generate Users</h1>
<p>Disini anda dapat membuat banyak user sekaligus untuk guru</p>
@endsection
 
@section('content')

<div class="modal fade" tabindex="-1" id="modal_konfigurasi" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfigurasi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="generate_user" action="">
            @csrf
            <div class="mb-3">
                <label for="">Password Default: </label>
                <input type="text" name="password" placeholder="password default" class="form-control">
                <div hidden id="validation_password" class="text-danger">

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" type="submit" id="" class="btn btn-primary" >Generate</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="bg-white w-100 rounded border p-4 mb-3" id="progress-box" hidden>
    <x-adminlte-progress theme="primary" id="progress" value=0 animated with-label/>
</div>
<div class="bg-white w-100 rounded border p-4">
    <button class="btn btn-primary mb-3" id="button_generate_user" data-toggle="modal" data-target="#modal_konfigurasi"><i class="bi bi-gear-fill"></i> Generate User</button>
    <table class="table table-bordered w-100" id="table_user">
        <thead class="bg-primary">
                <td width="5%">No</td>
                <td>Nama</td>
                <td width="5%">Punya akun</td>
        </thead>
        <tbody></tbody>
    </table>
</div>

@endsection

@section('js')
<x-script />
<script>
    const table_user = $("#table_user").DataTable({
        ajax: "{{ route('auto_generate_user_all_data') }}",
        responsive: true,
        columns: [
            { data: 'DT_RowIndex', class: 'text-center' },
            { data: 'nama' },
            { data: 'isHaveUser' },
        ]
    });

    function downloadURI(uri, name) 
    {
        var link = document.createElement("a");
        // If you don't know the name or want to use
        // the webserver default set name = ''
        link.setAttribute('download', name);
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        link.remove();
    }

    function showProgres(batchId) {
        $("#progress-box").prop('hidden', false)
        const progressInterval = setInterval(() => {
            $.ajax({
                type: 'get',
                method: 'get',
                url: `/tools/autoGenerateUser/batch/${batchId}`,
                processData: false,
                contentType: false,
                success: (res) => {
                    // console.log(res);                    
                    if(res.status){
                        $('.progress-bar').css('width', res.data.progress+'%').attr('aria-valuenow', res.data.progress).html(`${res.data.proccessed_jobs} / ${res.data.total_jobs}`);    
                    }

                    if(res.data.finished){
                        toastr.info("Mohon jangan tinggalkan halaman, masih ada beberapa proses")
                        setTimeout(() => {
                            $("#progress-box").prop('hidden', true)
                            $('.progress-bar').css('width', "0"+'%').attr('aria-valuenow', 0).html(`0 / 0`);    
                            $('#generate_user').prop('disabled', false);
                            table_user.ajax.reload()
                        }, 1000);
                        clearInterval(progressInterval)
                        Swal.fire({
                            title: 'Mohon tunggu sebentar',
                            html: 'sedang mengexport data user',
                            showDenyButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,                            
                            didOpen: () => {
                                Swal.showLoading()
                            },
                        })
                    }
                },
                error: (res) => {
                    console.log(res);
                }
            })
        }, 2000);
    }


    function checkExport(batchId, exportBatchId) {
        const progressIntervalExport = setInterval(() => {
            $.ajax({
                type: 'get',
                method: 'get',
                url: `/tools/autoGenerateUser/batch/${batchId}`,
                processData: false,
                contentType: false,
                success: (res) => {
                    if(res.status){
                    }

                    if(res.data.finished){
                        toastr.success("Berhasil export users")
                        setTimeout(() => {
                            swal.close();
                            const uri = "{{ route('auto_generate_user_export') }}"
                            downloadURI(`${uri}?id_batch=${exportBatchId}`, '')
                            $("#button_generate_user").prop('disabled', false)
                        }, 1000);
                        clearInterval(progressIntervalExport)
                    }
                },
                error: (res) => {
                    console.log(res);
                }
            })
        }, 2000);
    }

    $(document).ready(() => {

        $("#generate_user").on('submit', (e) => {
            e.preventDefault()
            const formData = new FormData($("#generate_user")[0])
            $("#button_generate_user").prop('disabled', true)
            $(`#generate_user .text-danger`).prop('hidden', true)  
            $(`#generate_user input`).removeClass('is-invalid')                             
            $.ajax({
                type: 'post',
                method: 'post',
                url: "{{ route('auto_generate_user') }}",
                processData: false,
                contentType: false,
                data: formData,
                success: (res) => {                    
                    $("#modal_konfigurasi").modal('hide')
                    if(res.status){
                        toastr.success(res.message)
                        showProgres(res.data.id_batch)
                        checkExport(res.data.big_insert_id, res.data.id_batch)
                    }else{
                        toastr.error(res.message)
                        console.log(res.messages);
                        $("#button_generate_user").prop('disabled', false)
                        Object.keys(res.messages).forEach((value, key) => {
                            $(`*[name=${value}]`).addClass('is-invalid')
                            $(`#validation_${value}`).html(res.messages[value])
                            $(`#validation_${value}`).prop('hidden', false)                               
                        })
                    }
                },
                error: (res) => {
                    console.log(res);
                    $('#button_generate_user').prop('disabled', false);
                }
            })
        })

    })

</script>
@endsection

