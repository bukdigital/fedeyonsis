<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeagueCategory;
use App\Models\AgeCategory;
use App\Models\CategoryRule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;

class LeagueCategoryController extends Controller
{
    /**
     * Tüm kategorileri listele
     */
    public function index()
    {
        $categories = LeagueCategory::with(['ageCategories', 'rules'])
            ->orderBy('level')
            ->get();

        return view('admin.league-categories.index', compact('categories'));
    }

    /**
     * Yeni kategori oluşturma formu
     */
    public function create()
    {
        $ageCategories = AgeCategory::where('status', true)->get();
        return view('admin.league-categories.create', compact('ageCategories'));
    }

    /**
     * Yeni kategori kaydet
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|unique:league_categories,name',
            'description' => 'nullable|string',
            'level' => 'required|integer|min:1',
            'age_categories' => 'required|array',
            'age_categories.*' => 'exists:age_categories,id',
            'max_foreign_players' => 'required|integer|min:0',
            'min_players_squad' => 'required|integer|min:0',
            'max_players_squad' => 'required|integer|min:0',
            'relegation_count' => 'required|integer|min:0',
            'promotion_count' => 'required|integer|min:0',
            'points_win' => 'required|integer|min:0',
            'points_draw' => 'required|integer|min:0',
            'points_lose' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Lig kategorisi oluştur
            $category = LeagueCategory::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'],
                'level' => $validated['level'],
                'status' => true
            ]);

            // Kategori kurallarını oluştur
            $category->rules()->create([
                'max_foreign_players' => $validated['max_foreign_players'],
                'min_players_squad' => $validated['min_players_squad'],
                'max_players_squad' => $validated['max_players_squad'],
                'relegation_count' => $validated['relegation_count'],
                'promotion_count' => $validated['promotion_count'],
                'points_win' => $validated['points_win'],
                'points_draw' => $validated['points_draw'],
                'points_lose' => $validated['points_lose'],
            ]);

            // Yaş kategorilerini ilişkilendir
            $category->ageCategories()->attach($validated['age_categories']);

            DB::commit();

            return redirect()
                ->route('admin.league-categories.index')
                ->with('success', 'Lig kategorisi başarıyla oluşturuldu.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Kategori detayını göster
     */
    public function show(LeagueCategory $category)
    {
        $category->load(['ageCategories', 'rules']);
        return view('admin.league-categories.show', compact('category'));
    }

    /**
     * Kategori düzenleme formu
     */
    public function edit(LeagueCategory $category)
    {
        $category->load(['ageCategories', 'rules']);
        $ageCategories = AgeCategory::where('status', true)->get();

        return view('admin.league-categories.edit', compact('category', 'ageCategories'));
    }

    /**
     * Kategori güncelle
     */
    public function update(Request $request, LeagueCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|unique:league_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'level' => 'required|integer|min:1',
            'age_categories' => 'required|array',
            'age_categories.*' => 'exists:age_categories,id',
            'max_foreign_players' => 'required|integer|min:0',
            'min_players_squad' => 'required|integer|min:0',
            'max_players_squad' => 'required|integer|min:0',
            'relegation_count' => 'required|integer|min:0',
            'promotion_count' => 'required|integer|min:0',
            'points_win' => 'required|integer|min:0',
            'points_draw' => 'required|integer|min:0',
            'points_lose' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $category->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'],
                'level' => $validated['level']
            ]);

            $category->rules()->update([
                'max_foreign_players' => $validated['max_foreign_players'],
                'min_players_squad' => $validated['min_players_squad'],
                'max_players_squad' => $validated['max_players_squad'],
                'relegation_count' => $validated['relegation_count'],
                'promotion_count' => $validated['promotion_count'],
                'points_win' => $validated['points_win'],
                'points_draw' => $validated['points_draw'],
                'points_lose' => $validated['points_lose'],
            ]);

            $category->ageCategories()->sync($validated['age_categories']);

            DB::commit();

            return redirect()
                ->route('league-categories.index')
                ->with('success', 'Lig kategorisi başarıyla güncellendi.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Kategori sil
     */
    public function destroy(LeagueCategory $category)
    {
        try {
            $category->delete();
            return redirect()
                ->route('league-categories.index')
                ->with('success', 'Lig kategorisi başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Silme işlemi başarısız: ' . $e->getMessage());
        }
    }

    /**
     * Kategori durumunu değiştir (aktif/pasif)
     */
    public function toggleStatus(LeagueCategory $category)
    {
        $category->status = !$category->status;
        $category->save();

        return redirect()
            ->route('league-categories.index')
            ->with('success', 'Kategori durumu güncellendi.');
    }
}
