@extends('layouts.admin')
@push('styles')

@endpush

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if ($errors->has('password'))
    <small class="text-danger">{{ $errors->first('password') }}</small>
                @endif

                <h4 class="card-header bg-primary text-white mt-0"><i class="mdi mdi-face-recognition" style="padding: :2%;"> </i>{{isset($detail) ? "KULLANICI GÜNCELLEME  " :"KULLANICI EKLEME " }} İŞLEMİ </h4>
                <br>
                <div class="row button-items" style="margin-bottom: 2%;">
                <div class="col-md-10">
                    <p class="card-text mb-4 font-16">Sistem <b>{{isset($detail) ? "KULLANICI GÜNCELLEME  " :"KULLANICI EKLEME " }} </b> işlemi. Gerekli işlemleri yapmak için ilgili  alanlara tıklayınız.</p>
                </p>
                </div>
                <div class="col-md-2 text-end ">
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" onclick="geri()">VAZGEÇ</button>
                </div>
                </div>

                <div class="card border mb-5 " style="padding: 1%;">

                    <form id="createuserForm" method="POST" action="{{ isset($detail) ? route('admin.user.update',['id'=>$detail->id]) : route('admin.user.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label text-right">Kullanıcı Adı</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="{{ isset($detail) ? $detail->name : ""}}" id="name" name="name">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="telephone" class="col-sm-2 col-form-label text-right">Telefon</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="tel" value="{{ isset($detail) ? $detail->telephone : ""}}" id="telephone" name="telephone">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label for="password-input" class="col-sm-2 col-form-label text-right">Şifre</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="password" name="password" value="" id="password-input">
                                        @if( isset($detail) ? $detail->password : "" )
                                        <p class="text-danger">Şifre değiştirmek istemiyorsanız boş bırakınız.</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="position" class="col-sm-2 col-form-label text-right">Görevi</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="{{ isset($detail) ? $detail->position : ""}}" id="position" name="position">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="iban_number" class="col-sm-2 col-form-label text-right">IBAN Numarası</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="iban_number" type="text" value="{{ isset($detail) ? $detail->iban_number : ""}}" id="iban_number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="image" class="col-sm-2 col-form-label text-right">Kullanıcı Resmi </label>
                                    <div class="col-sm-10">
                                        <input class="form-control" name="image" type="file" value="{{ isset($detail) ? $detail->image: ""}}" id="image">
                                    </div>
                                </div>

                            </div>


                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label for="surname" class="col-sm-2 col-form-label text-right">Kullanıcı Soyadı</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" value="{{ isset($detail) ? $detail->surname : ""}}" id="surname" name="surname">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control " id="email" value="{{ isset($detail) ? $detail->email : ""}}" name="email">

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="confirm-password-input" class="col-sm-2 col-form-label text-right">Şifre Doğrulama</label>
                                    <div class="col-sm-10">
                                        <input class="form-control"  type="password" id="confirm-password-input" name="password_confirmation" placeholder="Şifreyi Tekrar Girin">
                                        <small id="password-feedback" class="form-text"></small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-right">Durumu</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="status">
                                            <option>KULLANICI DURUMU</option>
                                            <option value="1" {{isset($detail) && $detail->status==1 ? 'selected': ""}}>AKTİF</option>
                                            <option value="0" {{isset($detail) && $detail->status==0 ? 'selected': ""}}>PASİF</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label text-right">Kullanıcı Rolü</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="role">
                                            @foreach ($roles as $role)

                                                    <option value="{{$role->name}}" {{isset($user) && $user->role->name==$role->name ? 'selected': ""}}>{{$role->name}}</option>


                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="fro_profile-main-pic">
                                        @if(@isset($detail) && $detail->image)
                                        <div class="col-md-6">
                                            @if(isset($detail) && $detail->image)
                                            <div class="profile-main-pic">
                                                <img src="{{ asset($detail->image) }}" alt="{{ $detail->name }}" width="150px" >
                                            </div>
                                            @else
                                                <img src="{{ asset('public/assets/images/avatar.png') }}" alt="Yüklenmiş Görsel Bulunmuyor" width="100px">
                                            @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{ isset($detail) ? "GÜNCELLE":"EKLE" }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>

@endsection

@push('js')

<script type="text/javascript">

    function geri(){
        history.back()
    }

</script>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password-input');
    const confirmPasswordInput = document.getElementById('confirm-password-input');
    const feedback = document.getElementById('password-feedback');

    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (confirmPassword === '') {
            feedback.textContent = ''; // Doğrulama alanı boşsa mesaj gösterme
            confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
        } else if (password === confirmPassword) {
            feedback.textContent = 'Şifreler eşleşiyor!';
            feedback.style.color = 'green';
            confirmPasswordInput.classList.add('is-valid');
            confirmPasswordInput.classList.remove('is-invalid');
        } else {
            feedback.textContent = 'Şifreler eşleşmiyor!';
            feedback.style.color = 'red';
            confirmPasswordInput.classList.add('is-invalid');
            confirmPasswordInput.classList.remove('is-valid');
        }
    });
});

</script>
@endpush
