<?php

namespace App\Console\Commands;

use App\Models\Revision;
use Illuminate\Console\Command;

class MarkOverdueRevisions extends Command
{
    protected $signature   = 'revisions:mark-overdue';
    protected $description = 'تعليم المراجعات المتأخرة';

    public function handle(): void
    {
        $count = Revision::where('status', 'pending')
            ->whereDate('scheduled_date', '<', today())
            ->update(['status' => 'overdue']);

        $this->info("✅ تم تعليم {$count} مراجعة كمتأخرة");
    }
}