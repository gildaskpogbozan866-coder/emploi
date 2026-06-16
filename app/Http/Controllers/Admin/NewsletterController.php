<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq->where('email', 'like', "%$q%")
                ->orWhere('prenom', 'like', "%$q%"));
        }

        $abonnes = $query->paginate(30)->withQueryString();

        $stats = [
            'total'    => NewsletterSubscriber::count(),
            'actifs'   => NewsletterSubscriber::actifs()->count(),
            'inactifs' => NewsletterSubscriber::where('statut', 'inactif')->count(),
            'recents'  => NewsletterSubscriber::actifs()->recents(7)->count(),
        ];

        return view('admin.newsletter.index', compact('abonnes', 'stats'));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Abonné supprimé.');
    }

    public function toggleStatut(NewsletterSubscriber $subscriber)
    {
        $subscriber->update([
            'statut' => $subscriber->statut === 'actif' ? 'inactif' : 'actif',
        ]);
        return back()->with('success', 'Statut mis à jour.');
    }

    public function export()
    {
        $abonnes = NewsletterSubscriber::actifs()->orderBy('email')->get();

        $csv = "Email,Prénom,Source,Date d'inscription\n";
        foreach ($abonnes as $ab) {
            $csv .= "\"{$ab->email}\",\"{$ab->prenom}\",\"{$ab->source}\",\"{$ab->created_at->format('d/m/Y')}\"\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="newsletter-abonnes-' . date('Y-m-d') . '.csv"',
        ]);
    }
}
