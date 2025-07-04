<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user(); // pega o usuário logado

        $request->validate([
            'file' => 'required|file|mimes:jpg,png,jpeg|max:4096',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $file->storeAs('attachments', $file->hashName(), 'public');

            if ($user->attachment) {
                $user->attachment->update(['filepath' => $file->hashName()]);
            } else {
                $user->attachment()->create([
                    'filepath' => $file->hashName(),
                ]);
            }

            return back()->with('success', 'Arquivo enviado com sucesso!');
        }

        return back()->withErrors('Nenhum arquivo enviado.');
    }

    public function destroy()
    {
        $user = Auth::user();

        if ($user->attachment) {
            Storage::disk('public')->delete('attachments/' . $user->attachment->filepath);

            $user->attachment->delete();

            return back()->with('success', 'Avatar removido com sucesso!');
        }

        return back()->withErrors('Nenhum avatar para remover.');
    }
}
