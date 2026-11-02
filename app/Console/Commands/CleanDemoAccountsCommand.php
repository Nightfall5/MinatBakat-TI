<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AuditLog;

class CleanDemoAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minbak:clean-demo-accounts {--force : Jalankan penonaktifan tanpa konfirmasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menonaktifkan seluruh akun pengujian bawaan development sebelum deployment production';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $demoEmails = [
            'superadmin@minbak.ti',
            'ketua@minbak.ti',
            'staff@minbak.ti',
            'mahasiswa@student.ac.id',
        ];

        $users = User::whereIn('email', $demoEmails)->get();

        if ($users->isEmpty()) {
            $this->info('Tidak ditemukan akun demo lama yang aktif.');
            return self::SUCCESS;
        }

        if (!$this->option('force') && !$this->confirm("Ditemukan {$users->count()} akun demo bawaan. Yakin ingin menonaktifkan seluruh akun demo ini?")) {
            $this->info('Operasi dibatalkan.');
            return self::SUCCESS;
        }

        foreach ($users as $user) {
            $user->update(['is_active' => false]);
            AuditLog::log('demo_account_deactivated', "Akun demo {$user->email} dinonaktifkan untuk persiapan production.", 'users', $user->id);
            $this->line("✔ Akun demo [{$user->email}] berhasil dinonaktifkan.");
        }

        $this->info("Berhasil menonaktifkan {$users->count()} akun demo. Pastikan Anda telah membuat akun admin resmi via 'php artisan minbak:create-admin'.");
        return self::SUCCESS;
    }
}
