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
        $user = Auth::user();

        $request->validate([
            'file' => 'required|file|mimes:jpg,png,jpeg|max:2048',
        ]);

        if (!$request->hasFile('file')) {
            return back()->withErrors(['file' => 'Nenhum arquivo enviado.']);
        }

        $file = $request->file('file');

        $filename = $file->hashName();

        $path = 'attachments/' . $filename;

        $file->storeAs('attachments', $filename, 'public');

        $user->attachment()->updateOrCreate([], [
            'filepath' => $path,
        ]);
        
        return back()->with('success', 'Arquivo enviado com sucesso!');
    }

    public function destroy()
    {
        $user = Auth::user();

        if ($user->attachment) {
            Storage::disk('public')->delete($user->attachment->filepath);

            $user->attachment->delete();

            return back()->with('success', 'Avatar removido com sucesso!');
        }

        return back()->withErrors('Nenhum avatar para remover.');
    }
}
