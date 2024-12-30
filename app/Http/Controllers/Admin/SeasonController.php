<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Season;
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

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Season::with('roles')->get();

        return view('admin.season.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles=Role::all();
        return view('admin.season.create', compact('roles'));
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
            'email' => ["required", "min:2", "unique:seasons,email"],
            'role' => ["required"],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'telephone' => ["required"],
            'iban_number' => ["required"],
            'position' => ["required"],
            'status' => ["required"]
        ]);

        if($request->hasFile('image')){
            $fileName = time() . '.' . $request->image->extension();
            $path=$request->image->move(public_path('uploads/season/'), $fileName);
            $data['image']='uploads/season/'.$fileName;
        }



        $data=$request->except('_token','role');
        //$data['satus']=Str::slug(isset($request->status) ? 1 : 0);

        $data['password']=bcrypt($request->password);
        $data['created_by']=$user_name.' '.$user_surname;

        $season=Season::create($data);
        $season->syncRoles($request->role);


    return redirect()->route("admin.season.index")->with('mesaj','KAYIT İŞLEMİ BAŞARALI');
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        $season = Season::with('permissions')->findOrFail($id); // Kullanıcıyı ve izinleri çek

        $role = $season->roles->first();
        return response()->json([
            'success' => true,
            'item' => $season,
            'permissions' => $permissions,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $detail=Season::findOrFail($id);
        $roles=Role::all();
        return view('admin.season.create', compact('detail','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user_name=Auth::user()->name;
        $user_surname=Auth::user()->surname;

        $season=Season::find($request->id);
        $email=$request->email;

        $duplicateControl=Season::where('email','!=',$season->email)
                                ->where('email',$email)->first();
        if($duplicateControl)
        {

            session()->flash('error',"Email adresi zaten kayıtlı");
            return redirect()->back();
        }

        $season->email=$request->email;
        $season->name=$request->name;
        $season->surname=$request->surname;
        $season->position=$request->position;
        $season->status=$request->status;
        $season->telephone=$request->telephone;
        $season->iban_number=$request->iban_number;
        $season->updated_by=$user_name.' '.$user_surname;



        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'

          ]);

        if ($request->image!=null)
          {

        $fileName = time() . '.' . $request->image->extension();
        $path=$request->image->move(public_path('uploads/season/'), $fileName);

         $folders="images";
         $publicPath="public/".$folders;
         //$path=$request->image->storeAs('public/season',$fileName);
//         $path=$request->image->move(public_path('images'), $fileName);
            $season->image='uploads/season/'.$fileName;
          }

         $season->save();
         session()->flash('success', "Kullanıcı Güncellendi!");
         $season->syncRoles($request->role);

      return redirect()->route("admin.season.index");

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

        if (Season::where('id', $kisiID)->exists()) {
            Season::where('id', $kisiID)->delete();
            return redirect()
                ->route('admin.season.index')
                ->with('success', 'Kayıt başarıyla silindi.');
        }

        return redirect()
            ->route('admin.season.index')
            ->with('error', 'Kayıt bulunamadı veya silinemedi.');


    }


}
