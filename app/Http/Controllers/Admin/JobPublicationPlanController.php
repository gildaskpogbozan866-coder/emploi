<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPublicationPlan;
use Illuminate\Http\Request;

class JobPublicationPlanController extends Controller
{
    public function index()
    {
        $plans = JobPublicationPlan::withCount('offres')
            ->orderByRaw('is_free DESC')
            ->orderBy('price')
            ->get();

        return view('admin.publication-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.publication-plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'duration_days' => 'nullable|integer|min:1|max:3650',
            'price'         => 'required|integer|min:0',
            'is_active'     => 'boolean',
        ]);

        JobPublicationPlan::create([
            'name'          => $data['name'],
            'duration_days' => $data['duration_days'] ?? null,
            'price'         => $data['price'],
            'is_free'       => (int) $data['price'] === 0,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.publication-plans.index')
            ->with('success', "Plan « {$data['name']} » créé.");
    }

    public function edit(JobPublicationPlan $publicationPlan)
    {
        return view('admin.publication-plans.edit', compact('publicationPlan'));
    }

    public function update(Request $request, JobPublicationPlan $publicationPlan)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'duration_days' => 'nullable|integer|min:1|max:3650',
            'price'         => 'required|integer|min:0',
            'is_active'     => 'boolean',
        ]);

        $publicationPlan->update([
            'name'          => $data['name'],
            'duration_days' => $data['duration_days'] ?? null,
            'price'         => $data['price'],
            'is_free'       => (int) $data['price'] === 0,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.publication-plans.index')
            ->with('success', "Plan « {$data['name']} » mis à jour.");
    }

    public function toggle(JobPublicationPlan $publicationPlan)
    {
        $publicationPlan->update(['is_active' => ! $publicationPlan->is_active]);
        $label = $publicationPlan->is_active ? 'activé' : 'désactivé';

        return back()->with('success', "Plan « {$publicationPlan->name} » {$label}.");
    }

    public function destroy(JobPublicationPlan $publicationPlan)
    {
        if ($publicationPlan->offres()->exists()) {
            return back()->with('error', 'Impossible de supprimer : des offres utilisent ce plan.');
        }

        $name = $publicationPlan->name;
        $publicationPlan->delete();

        return redirect()->route('admin.publication-plans.index')
            ->with('success', "Plan « {$name} » supprimé.");
    }
}
