<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            // Se o usuário já tiver uma foto, deleta a antiga
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Salva a nova imagem
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

        return redirect()->back()->with('success', 'Foto de perfil atualizada com sucesso!');
    }
}
