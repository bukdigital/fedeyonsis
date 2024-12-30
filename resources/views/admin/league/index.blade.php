@extends('layouts.admin')
@push('styles')
<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css" rel="stylesheet">
<!-- Responsive datatable examples -->
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="{{asset('assets/plugins/custombox/custombox.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-header bg-primary text-white mt-0"><i class="dripicons-archive" style="padding: :2%;"> </i>Kullanıcı Listesi</h4>
                <br>
                <div class="row button-items" style="margin-bottom: 2%;">
                <div class="col-md-10">
                    <p class="card-text mb-4 font-16">Sistemde kayıtlı kullanıcı listesi . İşlem yapmak için ilgili  alanlara tıklayınız.</p>
                </p>
                </div>
                <div class="col-md-2 text-end animation-modal">
                    <a href="{{route('admin.season.create')}}" class="btn btn-primary waves-effect waves-light">YENİ EKLE</a>

                </div>
                </div>

                <div class="card border mb-5 " style="padding: 1%;">
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr style="color:#334863;">
                            <th>ADI SOYADI</th>
                            <th>FOTOĞRAF</th>
                            <th>GÖREVİ</th>
                            <th>E POSTA</th>
                            <th>ROL</th>
                            <th>EKLEYEN</th>
                            <th>EKLEME TARİHİ</th>
                            <th>DÜZENLEYEN</th>
                            <th>DÜZENLEME TARİHİ</th>
                            <th>DURUM</th>
                            <th>İŞLEMLER</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach($list as $item)
                            <tr>
                                <td style="width:auto;">{{ $item->name }} {!! $item->surname !!}</td>
                                <td style="width:50px;" class="text-center">
                                    @if($item->image != null)
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="img-fluid" style="max-width: 80px;max-height: 80px;">
                                    @else
                                    <img src="{{ asset('assets/images/avatar.png') }}" alt="{{ $item->name }}" class="img-fluid" style="max-width: 80px;max-height: 80px;">
                                    @endif
                                </td>
                                <td style="width:auto;">{!! $item->position !!}</td>
                                <td style="width:auto;">{!! $item->email !!}</td>
                                <td style="width:auto;">{!! $item->roles->pluck('name')->join(', ') !!}</td>
                                <td style="width:auto;">{!! $item->created_by !!}</td>
                                <td style="width:auto;">{!! $item->created_at->format('d.m.Y') !!}</td>
                                <td style="width:auto;">{!! $item->updated_by == null ? '<span style="color:red;">---</span>' : $item->updated_by !!}</td>
                                <td style="width:auto;">{!! $item->updated_at == $item->created_at ? '<span style="color:red;">Düzenleme Yapılmamış</span>' : $item->updated_at->format('d.m.Y') !!}</td>
                                <td style="width:auto;">{!! $item->status == 1 ? '<span style="color:green;"><i class="mdi mdi-shield-check"></i> AKTİF</span>' : '<span style="color:red;"><i class="mdi mdi-shield-off"></i> PASİF</span>' !!}</td>
                                <td style="width: 10%;">
                                    <div class="d-flex order-actions button-items">
                                        <a href="javascript:void(0)"
                                            class="btn-detail btn btn-outline-info waves-effect waves-light"
                                            data-id="{{ $item->id }}"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Detay Gör">
                                            <i class="dripicons-preview fs-20"></i>
                                        </a>
                                        <a href="{{ route('admin.season.edit', ['id' => $item->id]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle" class="btn btn-outline-purple waves-effect waves-light">
                                            <i class="dripicons-pencil fs-20"></i>
                                        </a>
                                        <a href="javascript:(0)" class="btnDelete btn btn-outline-danger waves-effect waves-light me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Sil" data-name="{{ $item->name }}" data-id="{{ $item->id }}">
                                            <i class="dripicons-trash me-3 fs-20"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->



<!-- DETAIL MODAL-->
<div class="modal  bd-example-modal-xl" id="detailModal" tabindex="-1" user="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0 text-primary" id="myModalLabel">KULLANICI DETAY BİLGİLERİ </h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- DELETE FORM -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id" id="deleteId">
</form>
@endsection

@push('js')
        <!-- Required datatable js -->
        <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Buttons examples -->
        <script src="{{asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
        <script src="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{asset('assets/plugins/datatables/jszip.min.js') }}"></script>
        <script src="{{asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
        <script src="{{asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
        <script src="{{asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
        <script src="{{asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
        <script src="{{asset('assets/plugins/datatables/buttons.colVis.min.js') }}"></script>
        <!-- Responsive examples -->
        <script src="{{asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{asset('assets/pages/jquery.datatable.init.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{asset('assets/plugins/custombox/custombox.min.js') }}"></script>
        <script src="{{asset('assets/plugins/custombox/custombox.legacy.min.js') }}"></script>
        <script src="{{asset('assets/pages/jquery.modal-animation.js') }}"></script>


        <script>
            $(document).on('click', '.btn-detail', function () {
                const id = $(this).data('id'); // data-id değerini al

                // Modal'ı açmadan önce yükleniyor mesajını göster
                $('#detailModal .modal-body').html('<p>Yükleniyor...</p>');

                // Ajax ile detayları al
                $.ajax({
                    url: "{{ route('admin.season.detail', ['id' => ':id']) }}".replace(':id', id),
                    type: 'GET',
                    success: function (response) {
                        if (response) {
                            const item = response.item;
                            const permissions = response.permissions;
                            const role = response.role;
                            // Permissions verisini sadece isimleriyle listele
                            const permissionNames = permissions.map(permission => permission.name).join(', ');

                            // Resim URL'si kontrolü
                            const imageUrl = item.image && item.image.trim() !== ''
                                ? `{{ url('') }}/${item.image}`
                                : "{{ asset('/assets/images/avatar.png') }}";
                                const statusText = item.status === 1
                        ? '<span style="color: green;">Aktif</span>'
                        : '<span style="color: red;">Pasif</span>';

                            const roleText = role ? role.name : 'Rol bulunamadı';

                            // Modal içeriğini güncelle
                            let modalContent = `
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <img src="${imageUrl}" alt="${item.name}_${item.surname}" class="mx-auto d-block" style="max-width: 400px;max-height: 400px;">
                                            </div><!--end col-->
                                            <div class="col-lg-6 align-self-center">
                                                <div class="single-pro-detail">
                                                    <h3 class="pro-title">${item.name} ${item.surname}</h3>
                                                    <p class="text-muted mb-2">${item.position}</p>

                                                    <div class="col-md-12 d-flex align-items-center">
                                                <dl class="row mb-0">
                                                    <dt class="col-md-4 col-sm-6 col-xs-12">Rol Adı</dt>
                                                    <dd class="col-md-8 col-sm-6 col-xs-12"> : ${roleText}</dd>
                                                    <dt class="col-md-4 col-sm-6 col-xs-12">Durum</dt>
                                                    <dd class="col-md-8 col-sm-6 col-xs-12"> : ${statusText}</dd>
                                                    <dt class="col-md-4 col-sm-6 col-xs-12">E-posta</dt>
                                                    <dd class="col-md-8 col-sm-6 col-xs-12"> : ${item.email}</dd>
                                                    <dt class="col-md-4 col-sm-6 col-xs-12">Telefon</dt>
                                                    <dd class="col-md-8 col-sm-6 col-xs-12"> : ${item.telephone || 'Bilgi yok'}</dd>
                                                    <dt class="col-md-4 col-sm-6 col-xs-12">IBAN</dt>
                                                    <dd class="col-md-8 col-sm-6 col-xs-12"> : ${item.iban_number || 'Bilgi yok'}</dd>

                                                </dl>
                                            </div>
                                                </div>
                                            </div><!--end col-->
                                        </div><!--end row-->
                                    </div><!--end card-body-->
                                </div>
                            `;

                            $('#detailModal .modal-body').html(modalContent);
                        } else {
                            $('#detailModal .modal-body').html('<p>Bilgi alınamadı.</p>');
                        }
                    },
                    error: function () {
                        $('#detailModal .modal-body').html('<p>Bir hata oluştu.</p>');
                    }
                });

                // Modal'ı aç
                $('#detailModal').modal('show');
            });
        </script>



<script>
    $(document).ready(function () {
        $('.btnDelete').on('click', function () {
            let kisiID = $(this).data('id'); // Silinecek kişinin ID'si
            let kisiSoyad = $(this).data('name'); // Silinecek kişinin soyadı

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger',
                },
                buttonsStyling: true,
            });

            swalWithBootstrapButtons
                .fire({
                    title: `"${kisiSoyad}" adlı kayıt silinecek!`,
                    text: 'Bu işlemi yapmak istediğinize emin misiniz?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Evet, sil!',
                    cancelButtonText: 'Hayır, vazgeç!',
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        // ID'yi forma ekle
                        $('#deleteForm').attr('action', `/admin/season/delete/${kisiID}`); // Action'ı güncelle
                        $('#deleteForm').find('#deleteId').val(kisiID); // ID'yi formdaki gizli alana ekle
                        $('#deleteForm').submit(); // Formu gönder
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire(
                            'Vazgeçtiniz!',
                            'Seçilen kayıt korunuyor.',
                            'error'
                        );
                    }
                });
        });
    });
    </script>


@endpush
