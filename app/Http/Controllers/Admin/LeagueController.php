<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\League;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Response;
use RealRashid\SweetAlert\Facades\Alert;

class LeagueController extends Controller
{

    public function index()
    {
        $list = League::with('roles')->get();

        return view('admin.league.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles=Role::all();
        return view('admin.league.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_name=Auth::user()->name;
        $user_surname=Auth::user()->surname;
        $request->validate([
            'name' => ["required", "min:2"],
            'surname' => ["required", "min:2"],
            'password' => ["required", "min:6", 'confirmed'],
            'email' => ["required", "min:2", "unique:leagues,email"],
            'role' => ["required"],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'telephone' => ["required"],
            'iban_number' => ["required"],
            'position' => ["required"],
            'status' => ["required"]
        ]);

        if($request->hasFile('image')){
            $fileName = time() . '.' . $request->image->extension();
            $path=$request->image->move(public_path('uploads/league/'), $fileName);
            $data['image']='uploads/league/'.$fileName;
        }



        $data=$request->except('_token','role');
        //$data['satus']=Str::slug(isset($request->status) ? 1 : 0);

        $data['password']=bcrypt($request->password);
        $data['created_by']=$user_name.' '.$user_surname;

        $league=League::create($data);
        $league->syncRoles($request->role);


    return redirect()->route("admin.league.index")->with('mesaj','KAYIT İŞLEMİ BAŞARALI');
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        $league = League::with('permissions')->findOrFail($id); // Kullanıcıyı ve izinleri çek

        $role = $league->roles->first();
        return response()->json([
            'success' => true,
            'item' => $league,
            'permissions' => $permissions,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $detail=League::findOrFail($id);
        $roles=Role::all();
        return view('admin.league.create', compact('detail','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user_name=Auth::user()->name;
        $user_surname=Auth::user()->surname;

        $league=League::find($request->id);
        $email=$request->email;

        $duplicateControl=League::where('email','!=',$league->email)
                                ->where('email',$email)->first();
        if($duplicateControl)
        {

            session()->flash('error',"Email adresi zaten kayıtlı");
            return redirect()->back();
        }

        $league->email=$request->email;
        $league->name=$request->name;
        $league->surname=$request->surname;
        $league->position=$request->position;
        $league->status=$request->status;
        $league->telephone=$request->telephone;
        $league->iban_number=$request->iban_number;
        $league->updated_by=$user_name.' '.$user_surname;



        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'

          ]);

        if ($request->image!=null)
          {

        $fileName = time() . '.' . $request->image->extension();
        $path=$request->image->move(public_path('uploads/league/'), $fileName);

         $folders="images";
         $publicPath="public/".$folders;
         //$path=$request->image->storeAs('public/league',$fileName);
//         $path=$request->image->move(public_path('images'), $fileName);
            $league->image='uploads/league/'.$fileName;
          }

         $league->save();
         session()->flash('success', "Kullanıcı Güncellendi!");
         $league->syncRoles($request->role);

      return redirect()->route("admin.league.index");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        $kisiID = $request->id;

        if (League::where('id', $kisiID)->exists()) {
            League::where('id', $kisiID)->delete();
            return redirect()
                ->route('admin.league.index')
                ->with('success', 'Kayıt başarıyla silindi.');
        }

        return redirect()
            ->route('admin.league.index')
            ->with('error', 'Kayıt bulunamadı veya silinemedi.');


    }


}
