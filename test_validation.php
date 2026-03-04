<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$validator = validator(
    [],
    [
        'name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'lowercase', 'min:3', 'max:30', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:users'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
        'phone' => ['nullable', 'string', 'max:20'],
        'password' => ['required', 'confirmed'],
    ]
);

if ($validator->fails()) {
    echo "Fails!\n";
    print_r($validator->errors()->toArray());
} else {
    echo "Passes!";
}
