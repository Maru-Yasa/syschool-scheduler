@extends('layouts.app')

@section('header')
  <h1 class="fw-bold">Profile</h1>
@endsection

@section('content')

<div class="row px-3 mb-3">

    <div class="col-12 p-4 bg-white border rounded">
      <h2><i class="bi bi-person-circle"></i> Ubah User Profile</h2>
      <p class="text-muted">Anda dapat mengedit informasi akun anda disini</p>
      <form id="form_user_profile" action="">
				@csrf
        <div class="mb-3">
          <label for="">Username: </label>
          <input type="text" value="{{ $user->username }}" name="username" placeholder="Username" class="form-control">
          <div hidden id="validation_username" class="text-danger">

          </div>
      </div>
      <div class="mb-3">
          <label for="">Nama: </label>
          <input type="text" value="{{ $user->name }}" name="name" placeholder="Nama user" class="form-control">
          <div hidden id="validation_name" class="text-danger">

          </div>
      </div>
      <div class="mb-3">
          <label for="">Email: </label>
          <input type="text" value="{{ $user->email }}" name="email" placeholder="Email" class="form-control">
          <div hidden id="validation_email" class="text-danger">

          </div>
      </div>
      <div class="mb-3">
          <label for="">Foto User:</label>
          <input type="file" name="profile" class="form-control">
          <div hidden id="validation_edit_profile" class="text-danger">

          </div>
          <br>
          <img id="preview_foto_user" src="{{url('image/user/'.$user->profile)}}" class="img-fluid rounded-circle mx-3" style="object-fit: cover;width:64px;height:64px;" alt="">
      </div>
      <div class="mb-3">
				<button type="submit" id="button_edit_user" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>

</div>

<div class="row px-3 pb-3">
	<div class="col-12 border p-4 bg-white rounded">
		<h2><i class="bi bi-key-fill"></i> Ubah Password</h2>
		<p>Anda bisa mengganti password disini</p>
		<form action="" id="form_ubah_password">
			<div class="mb-3">
				<label for="">Password Lama: </label>
				<input type="password" value="" name="old_password" placeholder="password lama" class="form-control">
				<div hidden id="validation_old_password" class="text-danger">

				</div>
			</div>
			<div class="mb-3">
				<label for="">Password Baru: </label>
				<input type="password" value="" name="new_password" placeholder="password baru" class="form-control">
				<div hidden id="validation_new_password" class="text-danger">

				</div>
			</div>
			<div class="mb-3">
				<button type="submit" id="button_ubah_password" class="btn btn-primary">Ubah</button>
			</div>
		</form>
	</div>
</div>

@if (Auth::user()->id_guru)
	<div class="row px-3 pb-3">
		<div class="col-12 border p-4 bg-white rounded">
			<h2><i class="bi bi-mortarboard-fill"></i> Ubah Profile Guru</h2>
			<p>Anda bisa mengganti profile guru disini</p>
			<form action="" id="form_profile_guru">
				<div class="mb-3">
					<label for="">Nama: </label>
					<input type="text" value="{{ $guru->nama }}" name="nama" placeholder="nama guru" class="form-control">
					<div hidden id="validation_nama" class="text-danger">

					</div>
				</div>
				<div class="mb-3">
          <label for="">Foto Guru:</label>
          <input type="file" name="profile" class="form-control">
          <div hidden id="validation_edit_profile" class="text-danger">

          </div>
          <br>
          <img id="preview_foto_user" src="{{url('image/guru/'.$guru->profile)}}" class="img-fluid rounded-circle mx-3" style="object-fit: cover;width:64px;height:64px;" alt="">
				</div>
				<div class="mb-3">
					<button type="submit" id="button_profile_guru" class="btn btn-primary">Ubah</button>
				</div>
			</form>
		</div>
	</div>
@endif



@endsection

@section('js')
<x-script />

<script>

  $(document).ready(() => {
    
    $("#form_user_profile").on('submit', (e) => {
    	e.preventDefault()
    	const formData = new FormData($("#form_user_profile")[0])
    	$('#button_edit_user').prop('disabled', true);

			$.ajax({
					type: 'post',
					method: 'post',
					url: "{{ route('edit_user_profile') }}",
					processData: false,
					contentType: false,
					data: formData,
					success: (res) => {
							$('#button_edit_user').prop('disabled', false);
							if(res.status){
									toastr.success(res.message)
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
							$('#button_edit_user').prop('disabled', false);

							
					}
			})
    })

		$("#form_ubah_password").on('submit', (e) => {
			e.preventDefault()
    	const formData = new FormData($("#form_ubah_password")[0])
    	$('#button_ubah_password').prop('disabled', true);
			$("#form_ubah_password input").removeClass('is-invalid')
			$(`#form_ubah_password .text-danger`).prop('hidden', true)                               

			$.ajax({
					type: 'post',
					method: 'post',
					url: "{{ route('ubah_password') }}",
					processData: false,
					contentType: false,
					data: formData,
					success: (res) => {
							$('#button_ubah_password').prop('disabled', false);
							if(res.status){
									toastr.success(res.message)
									$("#form_ubah_password").trigger('reset')
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
							$('#button_ubah_password').prop('disabled', false);
					}
			})

		})

		$("#form_profile_guru").on('submit', (e) => {
			e.preventDefault()
    	const formData = new FormData($("#form_profile_guru")[0])
    	$('#button_profile_guru').prop('disabled', true);
			$("#form_profile_guru input").removeClass('is-invalid')
			$(`#form_profile_guru .text-danger`).prop('hidden', true)                               

			$.ajax({
					type: 'post',
					method: 'post',
					url: "{{ route('edit_guru_profile') }}",
					processData: false,
					contentType: false,
					data: formData,
					success: (res) => {
							$('#button_profile_guru').prop('disabled', false);
							if(res.status){
									toastr.success(res.message)
									$("#button_profile_guru").trigger('reset')
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
							$('#button_profile_guru').prop('disabled', false);
					}
			})

		})

  })


</script>

@endsection

