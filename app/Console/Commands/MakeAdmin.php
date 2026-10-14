<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class MakeAdmin extends Command
{
    protected $signature = 'portfolio:make-admin {email : Email admin} {--name=Admin : Nama admin}';

    protected $description = 'Buat atau jadikan pengguna sebagai admin panel portofolio';

    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->secret('Password (minimal 10 karakter)');

        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            ['email' => ['required', 'email'], 'password' => ['required', 'string', 'min:10']],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->name = $user->exists ? $user->name : $this->option('name');
        $user->password = $password; // di-hash otomatis oleh cast 'hashed'
        $user->is_admin = true;
        $user->save();

        $this->info("Admin {$email} siap digunakan. Login di /admin/login");

        return self::SUCCESS;
    }
}
