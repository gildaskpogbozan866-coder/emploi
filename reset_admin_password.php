<?php
$u = App\Models\User::where('email', 'admin@emploibougebenin.com')->first();
$u->password = 'password';
$u->save();
echo "Password mis à jour. Nouveau mot de passe : password\n";
