<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Role::all();
        $permissions = Permission::all();
        return view('admin.role.index', compact('list','permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = DB::table('permissions')->get();
        return view('admin.role.create',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name|max:255',
            'description' => 'required',
            'permissions' => 'required|array|min:1', // En az bir izin seçilmeli
            'permissions.*' => 'integer|exists:permissions,id', // Geçerli izin ID kontrolü
        ]);

        // Yeni bir rol oluştur
        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'guard_name' => 'web', // Varsayılan olarak "web" kullanılıyor
        ]);

        $permissionNames = Permission::whereIn('id', $validated['permissions'])->pluck('name')->toArray();
        $role->syncPermissions($permissionNames);

        session()->flash('success', 'Rol başarıyla eklendi.');
        return redirect()->route("admin.role.index");
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        $item = Role::with('permissions:id,name')->findOrFail($id); // permissions sadece id ve name ile yüklensin
        $permissions = $item->permissions; // ilişkili izinler
        return response()->json([
            'item' => $item,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $detail = Role::with('permissions:id,name')->findOrFail($id); // permissions sadece id ve name ile yüklensin
        $permissions = DB::table('permissions')->get();

        return view('admin.role.create', compact('detail','permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);
        $detail = Role::with('permissions:id,name')->findOrFail($id);
        $request->validate([
            'name' => 'required|unique:roles,name,' . $detail->id . '|max:255',
        ]);

        $role->update(['name' => $request->name]);
        return redirect()->route('admin.role.index')->with('success', 'Rol başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->back()->with('success', 'Rol başarıyla silindi.');
    }
}
