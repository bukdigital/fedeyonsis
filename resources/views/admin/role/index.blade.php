@extends('layouts.admin')
@push('styles')
<!-- DataTables -->
<link href="{{asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="{{asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="{{asset('assets/plugins/custombox/custombox.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-header bg-primary text-white mt-0"><i class="dripicons-archive" style="padding: :2%;"> </i>Rol Listesi</h4>
                <br>
                <div class="row button-items" style="margin-bottom: 2%;">
                <div class="col-md-10">
                    <p class="card-text mb-4 font-16">Sistem kullanıcılarına atanmak üzere hazırlanmış rol listesi. Gerekli işlemleri yapmak için ilgili  alanlara tıklayınız.</p>
                </p>
                </div>
                <div class="col-md-2 text-end animation-modal">
                    <a href="{{route('admin.role.create')}}" class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Yeni Rol Ekle">YENİ EKLE</a>

                </div>
                </div>

                <div class="card border mb-5 " style="padding: 1%;">
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr style="color:#334863;">
                            <th>ROL</th>
                            <th>AÇIKLAMA</th>
                            <th>İŞLEMLER</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach($list as $item)
                            <tr>
                                <td style="width:auto;">{{ $item->name }}</td>
                                <td style="width:auto;"><p style="color: #334863;">{!! $item->description !!}</p></td>
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
                                        <a href="{{ route('admin.role.edit', ['id' => $item->id]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle" class="btn btn-outline-purple waves-effect waves-light">
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



<!-- END FORM-->

<!-- DETAIL MODAL-->
<div class="modal  bd-example-modal-xl" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0 text-primary" id="myModalLabel">ROL DETAY BİLGİLERİ </h5>
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
        url: "{{ route('admin.role.detail', ['id' => ':id']) }}".replace(':id', id),
        type: 'GET',
        success: function (response) {
            if (response) {
                const item = response.item;
                const permissions = response.permissions;

                // Permissions verisini sadece isimleriyle listele
                const permissionNames = permissions.map(permission => permission.name).join(', ');

                // Modal içeriğini güncelle
                let modalContent = `
                    <div class="col-md-12 d-flex align-items-center">
                        <dl class="row mb-0">
                            <dt class="col-md-4 col-sm-6 col-xs-12">Rol Adı</dt>
                            <dd class="col-md-8 col-sm-6 col-xs-12"> : ${item.name}</dd>
                            <dt class="col-md-4 col-sm-6 col-xs-12">Rol Açıklaması</dt>
                            <dd class="col-md-8 col-sm-6 col-xs-12"> : ${item.description}</dd>
                            <dt class="col-md-4 col-sm-6 col-xs-12">Rol İzinleri</dt>
                            <dd class="col-md-8 col-sm-6 col-xs-12"> : ${permissionNames}</dd>
                        </dl>
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
$(document).ready(function() {
    // .btnDelete butonuna tıklama olayını dinliyoruz
    $('.btnDelete').on('click', function() {
        let kisiID = $(this).data('id'); // Buton üzerindeki data-id değerini alıyoruz
        let kisiSoyad = $(this).data('name'); // Buton üzerindeki data-name değerini alıyoruz

        // SweetAlert özelleştirilmiş seçenekleri
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success', // Onay butonu stili
                cancelButton: 'btn btn-danger'   // İptal butonu stili
            },
            buttonsStyling: true // Varsayılan buton stillerini etkinleştir
        });

        // SweetAlert uyarı penceresi
        swalWithBootstrapButtons.fire({
            title: `${kisiSoyad} adlı rol silinecek!`, // Başlık
            text: "Bu işlemi yapmak istediğinize emin misiniz?", // Açıklama
            icon: 'warning', // İkon türü
            showCancelButton: true, // İptal butonunu göster
            confirmButtonText: 'Evet, sil!', // Onay buton metni
            cancelButtonText: 'Hayır, vazgeç!', // İptal buton metni
            reverseButtons: true // Buton sıralamasını tersine çevir
        }).then((result) => {
            if (result.isConfirmed) {
                // Silme formunu gönderiyoruz
                $('#deleteForm').attr("action", "/admin/role/delete/" + kisiID); // Formun action değerini ayarla
                $('#deleteForm').submit(); // Formu gönder
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Kullanıcı işlemi iptal ettiğinde gösterilen mesaj
                swalWithBootstrapButtons.fire(
                    'Vazgeçtiniz!',
                    'Seçilen kayıt korunuyor.',
                    'error' // Hata ikonu
                );
            }
        });
    });
});

</script>

@endpush
