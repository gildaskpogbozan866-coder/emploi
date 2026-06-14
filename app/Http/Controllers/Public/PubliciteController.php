<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Publicite;

class PubliciteController extends Controller
{
    public function actives()
    {
        $publicites = Publicite::actives()
            ->select('id', 'titre', 'image', 'lien')
            ->inRandomOrder()
            ->get()
            ->map(fn($p) => [
                'id'        => $p->id,
                'titre'     => $p->titre,
                'image_url' => asset('storage/' . $p->image),
                'lien'      => $p->lien,
            ]);

        return response()->json($publicites);
    }
}
