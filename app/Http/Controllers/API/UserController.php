<?php

namespace App\Http\Controllers\API;

use App\User;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::with('roles')->latest()->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required'

        ]);

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->photo = empty($request['photo']) ? 'user.png' : $request['photo'];
        $user->role_id = $request['role_id'];
        $user->password = Hash::make($request['password']);
        $user->save();

        return $user;

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return auth('api')->user();
    }

    /**
     * Upload File.
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6',
            'role_id' => 'required'
        ]);

        $currentPhoto = $user->photo;
        if ($request->photo != $currentPhoto) {
            $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            file_exists(public_path('img/profile/')) ? true : mkdir(public_path('img/profile/'), 0777, true);
            \Image::make($request->photo)->save(public_path('img/profile/') . $name);

            //verifica si el archivo ya existe y lo remplaza
            $userPhoto = public_path('img/profile/').$currentPhoto;
            file_exists($userPhoto) ? @unlink($userPhoto) : null;


        }
        $user->photo = $name;
        $user->update($request->all());

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6',
            'role_id' => 'required'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'photo' => empty($request['photo']) ? 'user.png' : $request->photo,
            'password' => Hash::make($request['password']),
        ]);

        $user->role_id = $request->role_id;
        $user->save();
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // delete the user
        $user->status = 0;
        $user->save();
    }
}
