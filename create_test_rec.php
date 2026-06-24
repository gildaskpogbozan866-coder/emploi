<?php
// Script bootstrap Laravel minimal pour créer un user test
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'test.del.' . time() . '@example.com';

$u = User::create([
    'prenom'             => 'Test',
    'nom'                => 'Suppression',
    'email'              => $email,
    'password'           => Hash::make('TestPass123!'),
    'role'               => 'recruteur',
    'email_verified_at'  => now(),
    'actif'              => true,
]);
$u->syncRoles(['recruteur']);

echo $email . "\n";
