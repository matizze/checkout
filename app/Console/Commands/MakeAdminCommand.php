<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class MakeAdminCommand extends Command
{
    protected $signature = 'make:admin {name?} {email?} {password?}';

    protected $description = 'Create a new admin user';

    public function handle()
    {
        $admin = User::query()->where('role', 'admin')->first();

        if ($admin) {
            $this->alert('Admin already exists');

            return;
        }

        $arg = [
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => $this->argument('password'),
        ];

        $config = [
            'name' => config('app.admin.name'),
            'email' => config('app.admin.email'),
            'password' => config('app.admin.password'),
        ];

        $name = $arg['name'] ?: $config['name'] ?: text(label: 'What name of admin?', required: true);

        $email = $arg['email'] ?: $config['email'] ?: text(label: 'What email of admin?', required: true);

        $password = $arg['password'] ?: $config['password'] ?: password(
            label: 'What password of admin?',
            required: true
        );

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'admin',
        ]);

        $this->newLine();
        $this->line('==============================');
        $this->info('Admin created successfully');
        $this->line('==============================');
        $this->line("Name     : {$name}");
        $this->line("Email    : {$email}");
        $this->line("Password : {$password}");
        $this->line('Role     : admin');
        $this->line('==============================');
        $this->newLine();
    }
}
