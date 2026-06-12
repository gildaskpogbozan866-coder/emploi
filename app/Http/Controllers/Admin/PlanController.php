<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Http\Requests\Admin\PlanRequest;

class PlanController extends Controller
{
    public const FEATURE_KEYS = [
        'cv_limit'         => 'Nombre max de CVs',
        'job_apply_limit'  => 'Candidatures max',
        'job_post_limit'   => 'Offres publiables',
        'candidate_search' => 'Accès CVthèque (0 = non / 1 = oui)',
        'featured_profile' => 'Profil mis en avant (0 / 1)',
        'featured_jobs'    => 'Offres mises en avant',
    ];

    public function index()
    {
        $plans = Plan::withCount([
                        'abonnements',
                        'abonnements as abonnements_actifs_count' => fn ($q) => $q->actif(),
                     ])
                     ->with('features')
                     ->orderBy('target_type')
                     ->orderBy('price')
                     ->get();

        return view('admin.plans.list', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.form', [
            'plan'        => new Plan(),
            'featureKeys' => self::FEATURE_KEYS,
        ]);
    }

    public function store(PlanRequest $request)
    {
        $data = $request->validated();

        $plan = Plan::create([
            'name'          => $data['name'],
            'slug'          => $data['slug'],
            'description'   => $data['description'] ?? null,
            'target_type'   => $data['target_type'],
            'price'         => $data['price'],
            'currency'      => $data['currency'],
            'duration_days' => $data['duration_days'] ?? null,
            'is_free'       => (int) $data['price'] === 0,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        $this->syncFeatures($plan, $request->input('features', []));

        return redirect()->route('admin.plans.list')
                         ->with('success', "Plan « {$plan->name} » créé avec succès.");
    }

    public function edit(Plan $plan)
    {
        $plan->load('features');

        return view('admin.plans.form', [
            'plan'        => $plan,
            'featureKeys' => self::FEATURE_KEYS,
        ]);
    }

    public function update(PlanRequest $request, Plan $plan)
    {
        $data = $request->validated();

        $plan->update([
            'name'          => $data['name'],
            'slug'          => $data['slug'],
            'description'   => $data['description'] ?? null,
            'target_type'   => $data['target_type'],
            'price'         => $data['price'],
            'currency'      => $data['currency'],
            'duration_days' => $data['duration_days'] ?? null,
            'is_free'       => (int) $data['price'] === 0,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        $this->syncFeatures($plan, $request->input('features', []));

        return redirect()->route('admin.plans.list')
                         ->with('success', "Plan « {$plan->name} » mis à jour.");
    }

    public function toggle(Plan $plan)
    {
        $plan->update(['is_active' => ! $plan->is_active]);
        $label = $plan->is_active ? 'activé' : 'désactivé';

        return back()->with('success', "Plan « {$plan->name} » {$label}.");
    }

    public function destroy(Plan $plan)
    {
        if ($plan->abonnements()->exists()) {
            return back()->with('error', 'Impossible de supprimer : ce plan est associé à des abonnements existants.');
        }

        $name = $plan->name;
        $plan->delete();

        return redirect()->route('admin.plans.list')
                         ->with('success', "Plan « {$name} » supprimé.");
    }

    private function syncFeatures(Plan $plan, array $features): void
    {
        $plan->features()->delete();

        foreach ($features as $row) {
            $key = trim($row['key'] ?? '');
            if ($key === '') continue;

            $plan->features()->create([
                'feature_key'   => $key,
                'feature_value' => trim($row['value'] ?? '0'),
            ]);
        }
    }
}
