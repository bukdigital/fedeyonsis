<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = User::with('roles')->get();

        return view('admin.user.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles=Role::all();
        return view('admin.user.create', compact('roles'));
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
            'email' => ["required", "min:2", "unique:users,email"],
            'role' => ["required"],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'telephone' => ["required"],
            'iban_number' => ["required"],
            'position' => ["required"],
            'status' => ["required"]
        ]);

        if($request->hasFile('image')){
            $fileName = time() . '.' . $request->image->extension();
            $path=$request->image->move(public_path('uploads/user/'), $fileName);
            $data['image']='uploads/user/'.$fileName;
        }



        $data=$request->except('_token','role');
        //$data['satus']=Str::slug(isset($request->status) ? 1 : 0);

        $data['password']=bcrypt($request->password);
        $data['created_by']=$user_name.' '.$user_surname;

        $user=User::create($data);
        $user->syncRoles($request->role);


    return redirect()->route("admin.user.index")->with('mesaj','KAYIT İŞLEMİ BAŞARALI');
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        $user = User::with('permissions')->findOrFail($id); // Kullanıcıyı ve izinleri çek
        $permissions = $user->permissions; // İlgili izinler
        $role = $user->roles->first();
        return response()->json([
            'success' => true,
            'item' => $user,
            'permissions' => $permissions,
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $detail=User::findOrFail($id);
        $roles=Role::all();
        return view('admin.user.create', compact('detail','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user_name=Auth::user()->name;
        $user_surname=Auth::user()->surname;

        $user=User::find($request->id);
        $email=$request->email;

        $duplicateControl=User::where('email','!=',$user->email)
                                ->where('email',$email)->first();
        if($duplicateControl)
        {

            session()->flash('error',"Email adresi zaten kayıtlı");
            return redirect()->back();
        }

        $user->email=$request->email;
        $user->name=$request->name;
        $user->surname=$request->surname;
        $user->position=$request->position;
        $user->status=$request->status;
        $user->telephone=$request->telephone;
        $user->iban_number=$request->iban_number;
        $user->updated_by=$user_name.' '.$user_surname;
        if($request->password != null)
        {
          $user->password=bcrypt($request->password);
        }
        else
        {
          unset($user->password);
        }


        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'

          ]);

        if ($request->image!=null)
          {

        $fileName = time() . '.' . $request->image->extension();
        $path=$request->image->move(public_path('uploads/user/'), $fileName);

         $folders="images";
         $publicPath="public/".$folders;
         //$path=$request->image->storeAs('public/user',$fileName);
//         $path=$request->image->move(public_path('images'), $fileName);
            $user->image='uploads/user/'.$fileName;
          }

         $user->save();
         session()->flash('success', "Kullanıcı Güncellendi!");
         $user->syncRoles($request->role);

      return redirect()->route("admin.user.index");

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

        if (User::where('id', $kisiID)->exists()) {
            User::where('id', $kisiID)->delete();
            return redirect()
                ->route('admin.user.index')
                ->with('success', 'Kayıt başarıyla silindi.');
        }

        return redirect()
            ->route('admin.user.index')
            ->with('error', 'Kayıt bulunamadı veya silinemedi.');


    }


}
