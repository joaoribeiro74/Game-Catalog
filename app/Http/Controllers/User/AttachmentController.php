<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function destroy(Request $request)
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
