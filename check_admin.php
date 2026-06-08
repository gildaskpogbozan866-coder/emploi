<?php
$u = App\Models\User::where('email', 'admin@emploibougebenin.com')->first();

if (!$u) {
    echo "Admin introuvable\n";
    exit;
}

$raw = $u->getRawOriginal('password');
echo "password en base : " . ($raw ? substr($raw, 0, 30) . '...' : 'NULL') . "\n";
echo "check Admin@2026 : " . (Illuminate\Support\Facades\Hash::check('Admin@2026', $raw ?? '') ? 'OK' : 'FAIL') . "\n";
