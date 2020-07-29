<?php

namespace App\Http\Controllers;

use App\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Auth extends Controller
{
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */

    function login(Request $request)
    {
        if (isset($_SESSION['userdata']))
            return response("Anda tidak memiliki akses!", 401);
        $user = $request->user;
        $pass = $request->pass;

        $result = User::where('users.username', $user)
            ->orWhere('users.email', $user)
            ->get();

        if (count($result) == 0)
            $res['massage'] = 'User <b>' . $user . '</b> Tidak ditemukan';

        elseif (count($result) > 1)
            $res['massage'] = 'Terjadi kesalahan, periksa email atau username anda';

        elseif (!Hash::check($pass, $result[0]['password']))
            $res['massage'] = 'Password untuk user <b>' . $user . '</b> Salah!';
        else {
            $res = ['message' => 'Login suksess', 'data' => $result[0]];
            // session()->put('userdata', $result[0]);
            $_SESSION['userdata'] = $result[0];
        }

        return new JsonResponse($res);
    }
    function register(Request $request)
    {
        if (isset($_SESSION['userdata']))
            return response("Anda tidak memiliki akses!", 401);
        $user = new User;
        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->isActive = 1;
        $user->role = 'pembeli';
        $user->jenis_kelamin = $request->kelamin;

        if (isset($request->nohp))
            $user->nohp = $request->nohp;
        if (isset($request->alamat))
            $user->alamat = $request->alamat;
        try {
            $user->save();
            $_SESSION['userdata'] = [
                'nama_lengkap' => $request->nama_lengkap,
                'username' => $request->username,
                'role' => $request->role,
                'email' => $request->email,
            ];
            return new JsonResponse(['message' => 'Registrasi berhasil']);
        } catch (\Throwable $th) {
            return new JsonResponse(['message' => 'Registrasi gagal', 'err' => $th]);
        }
    }
}
