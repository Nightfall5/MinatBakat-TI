<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\AuditLog;

class CreateSuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minbak:create-admin 
                            {--email= : Email akun super admin} 
                            {--name= : Nama lengkap pengurus}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat akun Super Administrator secara aman untuk inisialisasi sistem di lingkungan production';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('====================================================');
        $this->info('  MINBAK TI — Inisialisasi Akun Super Administrator  ');
        $this->info('====================================================');

        $name = $this->option('name') ?: $this->ask('Masukkan nama lengkap Administrator');
        $email = $this->option('email') ?: $this->ask('Masukkan alamat email Administrator');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
        ], [
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $password = $this->secret('Masukkan kata sandi (minimal 8 karakter, input tersembunyi)');
        $confirmPassword = $this->secret('Konfirmasi kata sandi');

        if ($password !== $confirmPassword) {
            $this->error('Kata sandi dan konfirmasi kata sandi tidak cocok.');
            return self::FAILURE;
        }

        $pwdValidator = Validator::make(['password' => $password], [
            'password' => ['required', Password::min(8)->letters()->numbers()],
        ]);

        if ($pwdValidator->fails()) {
            foreach ($pwdValidator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $phone = $this->ask('Masukkan nomor telepon / WhatsApp (opsional)', null);

        $user = User::create([
            'name' => strip_tags($name),
            'email' => strtolower(trim($email)),
            'password' => Hash::make($password),
            'role' => 'super_admin',
            'phone' => $phone ? strip_tags($phone) : null,
            'is_active' => true,
        ]);

        AuditLog::log(
            'production_admin_provisioned',
            "Akun Super Administrator pertama ({$user->email}) berhasil dibuat melalui CLI.",
            'users',
            $user->id
        );

        $this->info("✔ Akun Super Administrator [{$user->email}] berhasil dibuat secara aman!");
        return self::SUCCESS;
    }
}
