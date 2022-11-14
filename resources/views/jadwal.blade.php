@extends('layouts.app')

@section('header')
    <h1 class="fw-bold">Lihat Jadwal</h1>
@endsection

@section('content')

{{-- modal edit jadwal --}}
<div class="modal fade" id="modal_edit_jadwal" tabindex="-1" role="dialog" aria-labelledby="modal_edit_jadwal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_edit_jadwal">Edit Jadwal</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        
            <form id="form_edit_jadwal" action="">
                <input type="text" hidden name="id">
                <div class="mb-3 d-flex flex-column">
                    <label for="">Guru :</label>
                    <select class="form-control form-control-sm" name="id_guru" id="">
                        <option value="" disabled selected>-- Pilih Guru --</option>
                    </select>
                    <div hidden id="validation_id_guru" class="text-danger text-sm validation">
                    </div>
                </div>
                <div class="mb-3 d-flex flex-column">
                    <label for="">Mapel :</label>
                    <select class="form-control form-control-sm" name="id_mapel" id="">
                        <option value="" disabled selected>-- Pilih Mapel --</option>
                    </select>
                    <div hidden id="validation_id_mapel" class="text-danger text-sm validation">

                    </div>
                </div>
                <div class="mb-3 d-flex flex-column">
                    <label for="">Pilih Hari :</label>
                    <select class="form-control form-control-sm" name="id_hari" id="">
                        <option value="" disabled selected>-- Pilih Hari --</option>
                    </select>
                    <div hidden id="validation_id_hari" class="text-danger text-sm validation">

                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col">
                        <label for="">JP Awal</label>
                        <input name="jam_awal" type="number" class="form-control">
                    </div>
                    <div class="col">
                        <label for="">JP Akhir</label>
                        <input name="jam_akhir" type="number" class="form-control">
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>

{{-- modal add jadwal --}}
<div class="modal fade" id="modal_add_jadwal" tabindex="-1" role="dialog" aria-labelledby="modal_add_jadwal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_add_jadwal">Tambah Jadwal</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        
            <form id="form_tambah_jadwal" action="">
                <input type="text" hidden name="id_kelas">
                <div class="mb-3 d-flex flex-column">
                    <label for="">Ruang Kelas :</label>
                    <select class="form-control form-control-sm w-100" name="id_ruang_kelas" id="">
                        <option value="" disabled selected>-- Pilih Ruang Kelas --</option>
                    </select>
                    <div hidden id="validation_id_ruang_kelas" class="text-danger text-sm validation">
                    </div>
                </div>
                <div class="mb-3 d-flex flex-column">
                    <label for="">Guru :</label>
                    <select class="form-control form-control-sm" name="id_guru" id="">
                        <option value="" disabled selected>-- Pilih Guru --</option>
                    </select>
                    <div hidden id="validation_id_guru" class="text-danger text-sm validation">
                    </div>
                </div>
                <div class="mb-3 d-flex flex-column">
                    <label for="">Mapel :</label>
                    <select class="form-control form-control-sm" name="id_mapel" id="">
                        <option value="" disabled selected>-- Pilih Mapel --</option>
                    </select>
                    <div hidden id="validation_id_mapel" class="text-danger text-sm validation">

                    </div>
                </div>
                <div class="mb-3 d-flex flex-column">
                    <label for="">Pilih Hari :</label>
                    <select class="form-control form-control-sm" name="id_hari" id="">
                        <option value="" disabled selected>-- Pilih Hari --</option>
                    </select>
                    <div hidden id="validation_id_hari" class="text-danger text-sm validation">

                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col">
                        <label for="">JP Awal</label>
                        <input name="jam_awal" type="number" class="form-control">
                    </div>
                    <div class="col">
                        <label for="">JP Akhir</label>
                        <input name="jam_akhir" type="number" class="form-control">
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
      </div>
    </div>
</div>

<div class="bg-white w-100 rounded border p-4">
    <div class="" id="table-wrapper"></div>
</div>

@endsection

@section('js')
<x-script />
<script>

        function random_rgba() {
            var o = Math.round, r = Math.random, s = 255;
            return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + 0.2 + ')';
        }

        function renderTable() {
            // get all jadwal
            $.ajax({
                url: "{{ route('get_all_jadwal') }}",
                type: 'get',
                success: (res) => {
                    const kelas_raw = Object.keys(res)
                    $("#table-wrapper").empty()
                    kelas_raw.forEach((id_kelas) => {
                        const obj = res[id_kelas]
                        const kelas_url = "{{ route('get_kelas_by_id') }}"
                        $.ajax({
                            type: 'get',
                            url: `${kelas_url}?id_kelas=${id_kelas}`,
                            success: (res) => {
                                create_table(`${id_kelas}-table-jadwal`, obj, res.nama_kelas, id_kelas)
                            }
                        })
                    })
                }
            })
        }

        function create_table(id, data, nama_kelas, id_kelas) {
            const container = document.getElementById('table-wrapper')
            const tbl = document.createElement('table')
            const thead = document.createElement('thead')
            const tr = document.createElement('tr')
            const table_id = `table_preview_${id}`
            let hari = @json($master_hari);
            
            const setting_jp = @json($master_setting_jp);
            hari = hari.map((obj, key) => {
                let result = {
                    name: obj.nama_hari,
                    targets: key
                }
                return result
            })

            // render table
            tbl.setAttribute('id', table_id)
            tbl.setAttribute('class', "table table-sm table-bordered mb-3")
            const nama_kelas_element = document.createElement('h3')
            const kelas_url = "{{ route('get_kelas_by_id') }}"
            
            nama_kelas_element.innerHTML = nama_kelas
            container.appendChild(nama_kelas_element)
            container.appendChild(tbl)
            // render thead
            tbl.appendChild(thead)

            // render tr
            thead.setAttribute('class', 'bg-primary text-white text-center')
            thead.appendChild(tr)

            const th_jp = document.createElement('th')
            th_jp.innerHTML = 'JP'
            tr.appendChild(th_jp)
            hari.forEach((h) => {
                const th = document.createElement('th')
                th.innerHTML = h.name
                tr.appendChild(th)
            })


            data.reverse()

            // render data
            let jam_jp = dayjs("07:15", "HH:mm")
            const master_durasi_jp = "{{ $master_setting_jp['durasi_jp'] }}"
            const jeda = JSON.parse('{!! json_encode($master_jeda) !!}')
            let list_mulai_jeda = []
            for (let i = 1; i < setting_jp.jumlah_jp + 1; i++) {
                const tr = document.createElement('tr')
                tr.setAttribute('class', 'bg-white text-black text-canter')
                for (let j = 0; j < hari.length + 1; j++) {

                    // render JP
                    if(j === 0){
                        let durasi_jp = master_durasi_jp
                        const td = tr.insertCell()
                        let isIstirahat = false
                        $(td).addClass("align-middle")
                        td.classList.add('text-center')
                        $(td).html(document.createTextNode(`${jam_jp.format('HH:mm')}`))
                        jam_jp = jam_jp.add(parseInt(master_durasi_jp), 'm')

                        jeda.forEach((jeda) => {
                            if(i == jeda.mulai_jeda -1){
                                isIstirahat = true
                                jam_jp = jam_jp.add(parseInt(jeda.durasi_jeda), 'm')
                            }
                        })


                    }else{

                        const td = document.createElement('td')
                        $(td).addClass("align-middle")
                        td.setAttribute('id', `${id}-${i}-${j}`)
                        let y = i
                        list_mulai_jeda.forEach((lst) => {
                            if(lst <= i){
                                y -= 1
                                td.setAttribute('id', `${id}-${i - 1}-${j}`)
                            }else{
                                td.setAttribute('id', `${id}-${i}-${j}`)
                            }
                        })
                        td.classList.add('text-center')
                        td.classList.add('prop-wrapper')
                        td.classList.add('position-relative')
                        td.classList.add('p-2')
                        const prop = `
                        <div class="prop-center prop-hidden shadow-lg">
                            <button data-hari=${j} data-id-kelas=${id_kelas} data-jp-awal=${y} class="btn btn-primary btn-sm mr-1" onclick="handleAdd(this)">
                                <i class="bi bi-plus-circle-fill"></i>                    
                            </button>
                        </div>
                        `
                        td.innerHTML = `- ${prop}`
                        tr.appendChild(td)              

                    }
                }
                tbl.appendChild(tr)
            }

            let warna = 50
            if (data !== []) {
                data.forEach((obj) => {
                    const elem = $(`#${id}-${obj.jam_awal}-${obj.hari.urut}`)
                    var selisih = obj.jam_akhir - obj.jam_awal + 1
                    const prop = `
                    <div class="prop-center prop-hidden shadow-lg">
                        <button data-id=${obj.id} class="btn button-edit-modal btn-primary btn-sm mr-1" onclick="handleEdit(this)">
                            <i class="bi bi-pencil"></i>                    
                        </button>
                        <button class="btn btn-danger btn-sm mr-1" onclick="handleDelete(${obj.id})">
                            <i class="bi bi-trash"></i>                    
                        </button>
                    </div>
                    `
                    const profileUrl = "{{ url('image/guru/') }}"
                    elem.html(`<img src="${profileUrl+'/'+obj.guru.profile}" class="img-fluid rounded-circle my-2" style="object-fit: cover;width:64px;height:64px;" /> <br> ${obj.mapel.nama_mapel} <br> ${obj.guru.nama} ${prop}`)
                    elem.css('background-color', random_rgba())
                    elem.addClass('text-center align-middle position-relative prop-wrapper')
                    for (let i = 1; i < selisih; i++) {
                        $(`#${id}-${obj.jam_awal + i}-${obj.hari.urut}`).remove()  
                    }
                    elem.prop('rowspan', selisih)
                    warna += 50
                })
            }

        }

        // handle add
        function handleAdd(e) {
            const target = $(e)
            const id_jadwal = target.attr('data-id')
            const url_ajax = `{{ url('jadwal/${id_jadwal}') }}`

            // initialize data
            $.ajax({
                url: url_ajax,
                type: 'get',
                success: (jadwal) => {
                    $("#modal_add_jadwal").modal('show')
                    $("#modal_add_jadwal").on('shown.bs.modal', (e) => {
                        
                        // jam awal
                        $("input[name=jam_awal]").val(target.data('jp-awal'))

                        //jam akhir
                        $("input[name=jam_akhir]").val(jadwal.jam_akhir)

                        //id kelas
                        $("input[name=id_kelas]").val(target.data('id-kelas'))
                        
                        // guru
                        $.ajax({
                            url: "{{ route('get_guru') }}",
                            type: 'get',
                            success: (res) => {
                                let data = [];
                                console.log(res);
                                res.data.forEach(guru => {
                                    data.push({
                                        id: guru.id,
                                        text: guru.nama_raw
                                    })
                                });
                                console.log(data);
                                $("select[name=id_guru]").select2({
                                    data: data
                                })
                                $("select[name=id_guru]").val(jadwal.id_guru).trigger('change')
                            },
    
                        }) 
                        
                        // mapel
                        $.ajax({
                            url: "{{ route('get_mapel') }}",
                            type: 'get',
                            success: (res) => {
                                let data = [];
                                res.data.forEach(mapel => {
                                    data.push({
                                        id: mapel.id,
                                        text: mapel.nama_mapel
                                    })
                                });
                                $("select[name=id_mapel]").select2({
                                    data: data
                                })
                                $("select[name=id_mapel]").val(jadwal.id_mapel).trigger('change')
                            }
                        })

                        // ruang kelas
                        $.ajax({
                            url: "{{ route('get_ruang_kelas') }}",
                            type: 'get',
                            success: (res) => {
                                console.log(res);
                                let data = [];
                                res.data.forEach(ruang => {
                                    data.push({
                                        id: ruang.id,
                                        text: `${ruang.nama} - ${ruang.owner}`
                                    })
                                });
                                $("select[name=id_ruang_kelas]").select2({
                                    data: data
                                })
                            }
                        })

                        // hari
                        $.ajax({
                            url: "{{ route('get_hari') }}",
                            type: 'get',
                            success: (res) => {
                                let data = [];
                                res.data.forEach(hari => {
                                    data.push({
                                        id: hari.urut,
                                        text: hari.nama_hari
                                    })
                                });
                                $("select[name=id_hari]").select2({
                                    data: data
                                })
                                $("select[name=id_hari]").val(target.data('hari')).trigger('change')
                            }
                        })

                    })

                }
            })

            // handle tambah
            $("#form_tambah_jadwal").off().on('submit', (e) => {
                e.preventDefault()
                const formData = new FormData($('#form_tambah_jadwal')[0])
                $.ajax({
                    type: 'post',
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="my-csrf-token"]').attr('content')
                    },
                    url: "{{ route('tambah_jadwal') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        console.log(res);
                        if(res.status){
                            $("#modal_add_jadwal").modal('hide')
                            renderTable()
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
                    },
                    complete: () => {
                        $('#button_edit_guru').prop('disabled', false);
                    }
                })
            })
        }

        // handle edit
        function handleEdit(e) {
            const target = $(e)
            const id_jadwal = target.attr('data-id')
            const url_ajax = `{{ url('jadwal/${id_jadwal}') }}`

            // initialize data
            $.ajax({
                url: url_ajax,
                type: 'get',
                success: (jadwal) => {
                    $("#modal_edit_jadwal").modal('show')
                    $("#modal_edit_jadwal").on('shown.bs.modal', (e) => {
                        // id
                        $("input[name=id]").val(id_jadwal)
                        // jam awal
                        $("input[name=jam_awal]").val(jadwal.jam_awal)

                        //jam akhir
                        $("input[name=jam_akhir]").val(jadwal.jam_akhir)
                        
                        // guru
                        $.ajax({
                            url: "{{ route('get_guru') }}",
                            type: 'get',
                            success: (res) => {
                                let data = [];
                                console.log(res);
                                res.data.forEach(guru => {
                                    data.push({
                                        id: guru.id,
                                        text: guru.nama_raw
                                    })
                                });
                                console.log(data);
                                $("select[name=id_guru]").select2({
                                    data: data
                                })
                                $("select[name=id_guru]").val(jadwal.id_guru).trigger('change')
                            },
    
                        }) 
                        
                        // mapel
                        $.ajax({
                            url: "{{ route('get_mapel') }}",
                            type: 'get',
                            success: (res) => {
                                let data = [];
                                res.data.forEach(mapel => {
                                    data.push({
                                        id: mapel.id,
                                        text: mapel.nama_mapel
                                    })
                                });
                                $("select[name=id_mapel]").select2({
                                    data: data
                                })
                                $("select[name=id_mapel]").val(jadwal.id_mapel).trigger('change')
                            }
                        })

                        // hari
                        $.ajax({
                            url: "{{ route('get_hari') }}",
                            type: 'get',
                            success: (res) => {
                                let data = [];
                                res.data.forEach(hari => {
                                    data.push({
                                        id: hari.id,
                                        text: hari.nama_hari
                                    })
                                });
                                $("select[name=id_hari]").select2({
                                    data: data
                                })
                                $("select[name=id_hari]").val(jadwal.id_hari).trigger('change')
                            }
                        })

                    })

                }
            })

            // handle edit
            $("#form_edit_jadwal").off().on('submit', (e) => {
                e.preventDefault()
                const formData = new FormData($('#form_edit_jadwal')[0])
                $.ajax({
                    type: 'post',
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="my-csrf-token"]').attr('content')
                    },
                    url: "{{ route('edit_jadwal') }}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: (res) => {
                        console.log(res);
                        $("select").removeClass('is-invalid')                        
                        $("input").removeClass('is-invalid')    
                        $(".validation").removeClass('is-invalid')                    
                        if(res.status){
                            $("#modal_edit_jadwal").modal('hide')
                            renderTable()
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
                    },
                    complete: () => {
                        $('#button_edit_guru').prop('disabled', false);
                    }
                })
            })

        }

        function handleDelete(id) {
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
                        url:`{{ url('jadwal/${id}/delete') }}`,
                        success:(res) => {
                            if(res.status){
                                toastr.success(res.message)
                                renderTable()
                            }else{
                                toastr.error(res.message)
                            }
                        }
                    })
                }
            })
        }

        $(document).ready(() => {
            dayjs.extend(window.dayjs_plugin_customParseFormat);

            renderTable()
            $.fn.select2.defaults.set("escapeMarkup", (text) => text)

        })

</script>
@endsection

