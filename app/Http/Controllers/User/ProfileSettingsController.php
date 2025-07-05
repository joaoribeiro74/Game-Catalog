<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileSettingsController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('profile.settings.edit', compact('user'));
    }

    public function password()
    {
        return view('profile.settings.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Senha alterada com sucesso!');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // dd($request->file('file'));

        $rules = [
            'username' => [
                'sometimes',
                'string',
                'min:4',
                'max:32',
                'regex:/^[a-zA-Z0-9_-]+$/',
                'unique:users,username,' . $user->id,
                function ($attr, $val, $fail) use ($user) {
                    if (
                        $user->username !== $val &&
                        $user->username_changed_at &&
                        $user->username_changed_at->greaterThan(now()->subMonths(3))
                    ) {
                        $fail('Você só pode alterar o nome de usuário a cada 3 meses.');
                    }
                }
            ],
            'display_name' => ['sometimes', 'nullable', 'string', 'min:4', 'max:32'],
            'email' => ['sometimes', 'required', 'email:rfc,dns', 'unique:users,email,' . $user->id],
            'file' => ['sometimes', 'nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];

        if ($request->filled('email') && $request->email !== $user->email) {
            $rules['current_password'] = [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Senha atual incorreta para alterar o e-mail.');
                    }
                },
            ];
        }


        $messages = [
            'username.regex' => 'O nome de usuário só pode conter letras, números, traços e underline.',
            'username.min' => 'O nome de usuário deve ter pelo menos :min caracteres.',
            'username.max' => 'O nome de usuário deve ter no máximo :max caracteres.',

            'display_name.max' => 'O nome de exibição deve ter no máximo :max caracteres.',
            'display_name.min' => 'O nome de exibição deve ter no mínimo :min caracteres.',

            'email.email' => 'Por favor, informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',

            'file.image' => 'O arquivo enviado deve ser uma imagem.',
            'file.max' => 'A imagem não pode ter mais que :max kilobytes.',

            'current_password.required' => 'Você precisa informar a senha atual para alterar o e-mail.',
        ];

        $validated = $request->validate($rules, $messages);

        $changed = false;

        if ($request->filled('username') && $request->username !== $user->username) {
            $user->username = $request->username;
            $user->username_changed_at = now();
            $changed = true;
        }

        if ($request->filled('display_name') && $request->display_name !== $user->display_name) {
            $user->display_name = $request->display_name;
            $changed = true;
        }

        if ($request->filled('email') && $request->email !== $user->email) {
            $user->email = $request->email;
            $changed = true;
        }
        $file = $request->file('file');

        if ($file && $file->isValid()) {
            $filename = $file->hashName();
            $path = 'attachments/' . $filename;

            $file->storeAs('attachments', $filename, 'public');

            $oldAttachment = $user->attachment;

            $user->attachment()->updateOrCreate(
                ['user_id' => $user->id],
                ['filepath' => $path]
            );

            if ($oldAttachment && Storage::disk('public')->exists($oldAttachment->filepath)) {
                Storage::disk('public')->delete($oldAttachment->filepath);
            }

            $changed = true;
        }


        if ($changed) {
            $user->save();
            return back()->with('success', 'Perfil atualizado com sucesso!');
        }

        return back()->with('info', 'Nenhuma alteração detectada.');
    }



    public function checkUsername(Request $request)
    {
        $username = $request->query('username');

        $exists = \App\Models\User::where('username', $username)
            ->where('id', '!=', auth()->id())
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
