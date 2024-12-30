@extends('layouts.admin')
@push('styles')

@endpush

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-header bg-primary text-white mt-0"><i class="dripicons-archive" style="padding: :2%;"> </i>{{isset($detail) ? "ROL/YETKİ GÜNCELLEME  " :"ROL/YETKİ EKLEME " }} İŞLEMİ </h4>
                <br>
                <div class="row button-items" style="margin-bottom: 2%;">
                <div class="col-md-10">
                    <p class="card-text mb-4 font-16">Sistem kullanıcılarına atanmak üzere hazırlanmış rol listesi. Gerekli işlemleri yapmak için ilgili  alanlara tıklayınız.</p>
                </p>
                </div>
                <div class="col-md-2 text-end ">
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" onclick="geri()">VAZGEÇ</button>
                </div>
                </div>

                <div class="card border mb-5 " style="padding: 1%;">

                    <form id="createRoleForm" method="POST" action="{{ isset($detail) ? route('admin.role.edit',['id'=>$detail->id]) : route('admin.role.create')}}">
                        @csrf
                        <div class="mb-3">
                            <label for="role_name" class="form-label">Rol Adı</label>
                            <input type="text" class="form-control" id="role_name" name="name" value="{{ isset($detail) ? $detail->name : ""}}" required>
                        </div>
                        <div class="mb-3">
                            <label for="role_description" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="role_description" name="description" required>{{ isset($detail) ? $detail->description : ""}}</textarea>
                        </div>

                        <div class="col-md-9">
                            <label for="role_permissions" class="form-label">Rol İzinleri</label>
                            <br>
                            <div class="form-check-inline my-2">
                                @foreach($permissions as $permission)
                                <div class="custom-control custom-checkbox ">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="customCheck{{$permission->id}}"
                                           name="permissions[]"
                                           value="{{$permission->id}}"
                                           @if(isset($detail) && $detail->permissions->contains('id', $permission->id)) checked @endif>
                                            <label class="custom-control-label pr-2" for="customCheck{{$permission->id}}">
                                            {{$permission->name}}
                                    </label>
                                </div>
                                @endforeach
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
@endpush
