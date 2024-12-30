@extends('layouts.admin')

@push('styles')

@endpush

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-header bg-primary text-white mt-0"><i class="dripicons-archive" style="padding: :2%;"> </i>{{isset($detail) ? "LİG GÜNCELLEME  " :"LİG EKLEME " }} İŞLEMİ </h4>
                <br>
                <div class="row button-items" style="margin-bottom: 2%;">
                <div class="col-md-10">
                    <p class="card-text mb-4 font-16">Sezon Lig kategorileri oluşturmak için kullanın. Gerekli işlemleri yapmak için ilgili  alanlara tıklayınız.</p>
                </p>
                </div>
                <div class="col-md-2 text-end ">
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" onclick="geri()">VAZGEÇ</button>
                </div>
                </div>

                <div class="card border mb-5 " style="padding: 1%;">

                    <form action="{{ route('league-categories.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Kategori Adı</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="level">Seviye</label>
                                    <input type="number" name="level" id="level"
                                           class="form-control @error('level') is-invalid @enderror"
                                           value="{{ old('level') }}" required>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Açıklama</label>
                            <textarea name="description" id="description"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Yaş Kategorileri</label>
                                <div class="row">
                                    @foreach($ageCategories as $ageCategory)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       name="age_categories[]"
                                                       value="{{ $ageCategory->id }}"
                                                       class="form-check-input"
                                                       @if(in_array($ageCategory->id, old('age_categories', []))) checked @endif>
                                                <label class="form-check-label">
                                                    {{ $ageCategory->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <h4>Kategori Kuralları</h4>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Maximum Yabancı Oyuncu</label>
                                    <input type="number" name="max_foreign_players"
                                           class="form-control"
                                           value="{{ old('max_foreign_players', 0) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Minimum Kadro</label>
                                    <input type="number" name="min_players_squad"
                                           class="form-control"
                                           value="{{ old('min_players_squad', 0) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Maximum Kadro</label>
                                    <input type="number" name="max_players_squad"
                                           class="form-control"
                                           value="{{ old('max_players_squad', 0) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Düşme Sayısı</label>
                                    <input type="number" name="relegation_count"
                                           class="form-control"
                                           value="{{ old('relegation_count', 0) }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Yükselme Sayısı</label>
                                    <input type="number" name="promotion_count"
                                           class="form-control"
                                           value="{{ old('promotion_count', 0) }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Galibiyet Puanı</label>
                                    <input type="number" name="points_win"
                                           class="form-control"
                                           value="{{ old('points_win', 3) }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Beraberlik Puanı</label>
                                    <input type="number" name="points_draw"
                                           class="form-control"
                                           value="{{ old('points_draw', 1) }}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Mağlubiyet Puanı</label>
                                    <input type="number" name="points_lose"
                                           class="form-control"
                                           value="{{ old('points_lose', 0) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Kaydet</button>
                            <a href="{{ route('league-categories.index') }}" class="btn btn-secondary">İptal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>


</div>
@endsection
@push('js')
<script type="text/javascript">

    function geri(){
        history.back()
    }

</script>
@endpush
