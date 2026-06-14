<?php

namespace App\Http\Controllers;

use App\Models\Content\Post;
use App\Models\Market\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request, string $type, int $id)
    {

        if (!Auth::check()) {
            return response()->json([
                'login_required' => true,
                'message' => 'To add to favorites, first log in to your account.
                <a href="' . route('auth.login-register.form') . '" class="toast-link">Login / Register</a>'
            ]);
        }

        $model = match ($type) {
            'product' => Product::findOrFail($id),
            'post'    => Post::findOrFail($id),
        };

        $existing = $model->likes()->where('user_id', Auth::id())->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $model->likes()->create(['user_id' => Auth::id()]);
            $liked = true;
        }

        return response()->json(['liked' => $liked]);
    }
}
