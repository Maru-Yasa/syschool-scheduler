@extends('layouts.app')

@section('header')
    <h1>Hai {{ $user->name }}, {{ $greeting }}</h1>
    @if (!$user->id_guru)
        <div class="alert bg-danger mt-3"><i class="bi bi-exclamation-triangle-fill mr-2"></i> Akun anda tidak berelasi dengan guru</div>
    @endif
@endsection

@section('content')

<div class="pb-3">

    <x-adminlte-profile-widget class="col-12 px-0 elevation-0" name="{{ $user->name }}" desc="{{ $user->role }}" theme="primary"
    img="{{url('image/user/'.$user->profile)}}">
        <x-adminlte-profile-col-item class="text-primary border-right" icon="fas fa-lg fa-clock"
            title="Total Jam Mengajar" text="{{round($total_jam_mengajar)}} jam" size=4 badge="primary"/>
        <x-adminlte-profile-col-item class="text-danger border-right" icon="fas fa-lg fa-clock" title="Total JP"
            text="{{$total_JP}} JP" size=4 badge="danger"/>
        <x-adminlte-profile-col-item class="text-warning" icon="fas fa-lg fa-users" title="{{ $semester_sekarang->nama_semester }}"
            text="{{ \Carbon\Carbon::parse($semester_sekarang->tanggal_semester)->formatLocalized('%d %B %Y') }}" size=4 badge="warning"/>
    </x-adminlte-profile-widget>

    {{-- <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><span id="display_durasi_jp">{{ $total_jam_mengajar }}</span> Jam</h3>
                    <p>Total Jam Mengajar</p>
                </div>
                <div class="icon">
                    <i class="fa fa-clock"></i>
                </div>
                <a href="#" class="small-box-footer">
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><span id="display_jumlah_jp">{{ $total_JP }}</span> JP</h3>
                    <p>Total Jumlah Jam Pembelajaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="#" class="small-box-footer">
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><span id="display_jumlah_jp">{{ $semester_sekarang->nama_semester }}</span></h3>
                    <p>{{ \Carbon\Carbon::parse($semester_sekarang->tanggal_semester)->formatLocalized('%d %B %Y') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <a href="#" class="small-box-footer">
                </a>
            </div>
        </div>
    </div> --}}

    <div class="row px-3">
        <div class="col-12 border p-4 bg-white">
            <button id="print_jadwal_guru" class="btn btn-success"><i class="bi bi-printer-fill"></i> Print jadwal</button>
        </div>
    </div>

    <div class="row px-3">
        <div id="table-wrapper" class="bg-white w-100 p-4 mt-4 rounded border">
        </div>
    </div>
    <iframe id='iframe' src="" class="w-100 d-none" style="width: 100%; height: 100%;" frameborder="0"></iframe>

</div>

@endsection

@section('js')
<x-script />

<script>
    
    function random_rgba() {
        var o = Math.round, r = Math.random, s = 255;
        return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + 0.2 + ')';
    }

    function create_table_guru_based(id, data, id_guru) {
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
        const nama_kelas = document.createElement('h3')
        nama_kelas.innerHTML = data[0].guru.nama
        
        container.appendChild(nama_kelas)
        container.appendChild(tbl)
    
        // render thead
        tbl.appendChild(thead)
    
        // render tr
        thead.setAttribute('class', 'bg-primary text-white')
        thead.appendChild(tr)
    
        const th_jp = document.createElement('th')
        $(th_jp).addClass('text-center')
        th_jp.innerHTML = 'JP'
        tr.appendChild(th_jp)
        hari.forEach((h) => {
            const th = document.createElement('th')
            th.innerHTML = h.name
            tr.appendChild(th)
        })
    
    
        data.reverse()
    
        // render data
        let jam_jp = dayjs("{{ $master_setting_jp['mulai_jp'] }}", "HH:mm")
        const master_durasi_jp = "{{ $master_setting_jp['durasi_jp'] }}"
        const jeda = JSON.parse('{!! json_encode($master_jeda) !!}')
        let list_mulai_jeda = []
        console.log("{{ $master_setting_jp['mulai_jp'] }}");
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
                    td.setAttribute('id', `${id}-${i}-${j}`)
                    td.classList.add('text-center')
                    td.innerHTML = '-'
                    tr.appendChild(td)              
                }
            }
            tbl.appendChild(tr)
        }
    
        data.forEach((obj) => {
            const elem = $(`#${id}-${obj.jam_awal}-${obj.hari.urut}`)
            const selisih = obj.jam_akhir - obj.jam_awal + 1
            if(obj.id_guru == id_guru){
                const profileUrl = "{{ url('image/guru/') }}"
                elem.html(`${obj.kelas.nama_kelas} <br> ${obj.mapel.nama_mapel} <br> ${obj.ruang_kelas.nama}`)
                // elem.css('background-color', 'rgba(0,123,255, 0.1)')
                elem.css('background-color', random_rgba())
            }else{
                elem.css('background-color', 'rgba(217, 83, 79, 0.1)	')
            }
            elem.addClass('text-center align-middle')
            for (let i = 1; i < selisih; i++) {
                $(`#${id}-${obj.jam_awal + i}-${obj.hari.urut}`).remove()  
            }
            elem.prop('rowspan', selisih)
        })
    
    }
    
    function printDiv(source) {
        
        let mywindow = window.open(`${window.location.origin}/cetak/semuaJadwal`, 'PRINT', 'height=650,width=900,top=100,left=150');
        mywindow.document.write(source);
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();
        mywindow.close();

        return true;
    }
    $(document).ready(() => {
        var preview_jadwal_url = "{{ route('get_jadwal_by_id_guru', $user->id_guru) }}"
        dayjs.extend(window.dayjs_plugin_customParseFormat);
        $.ajax({
            url: preview_jadwal_url,
            type: "GET",
            success: (res) => {
                console.log(res);
                const guru_raw = Object.keys(res);
                $('#table-wrapper').children().remove()
                // res[parseInt(kelas_raw)]
                guru_raw.forEach((id_guru) => {
                    const obj = res[parseInt(id_guru)]
                    create_table_guru_based(`${id_guru}`, obj, "{{ $user->id_guru }}")
                })
            }
        })
        
        $("#print_jadwal_guru").on('click', (e) => {
            e.preventDefault()
            $("#iframe").prop('src', "{{ route('cetak_jadwal_by_id_guru', $user->id_guru) }}")
            $("#iframe").on('load', () => {
                printDiv(document.getElementById('iframe').contentDocument.querySelectorAll('body')[0].innerHTML)                    
            })
        })

    })

        
</script>

@endsection
