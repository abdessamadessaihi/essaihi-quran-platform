<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class CleanUnverifiedUsers extends Command
{
    // Nom de la commande à taper dans le terminal
    protected $signature = 'users:clean-unverified';

    // Description de la commande
    protected $description = 'Supprime les utilisateurs inscrits manuellement qui n\'ont pas vérifié leur e-mail après 24 heures';

    public function handle()
    {
        // On récupère tous les utilisateurs créés il y a plus de 24 heures ET qui n'ont pas validé leur mail
        $deletedCount = User::whereNull('email_verified_at')
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->delete();

        $this->info("Nettoyage réussi : {$deletedCount} compte(s) non vérifié(s) supprimé(s).");
    }
}