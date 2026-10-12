<?php

namespace NextDeveloper\CRM\Jobs\Importers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use NextDeveloper\CRM\Database\Models\Importers;
use NextDeveloper\CRM\Services\LeadImporterService;

/**
 * Imports one normalized lead through LeadImporterService.
 * Source adapters dispatch this job directly (no event handler); the importer is looked up by id so the
 * payload stays small and the job always sees the current importer config.
 */
class ImportLeadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // The service records failures on the run itself (and the hash index lets a failed lead be retried), so no queue retries
    public int $tries = 1;

    public function __construct(private int $importerId, private array $lead)
    {
    }

    public function handle(): void
    {
        $importer = Importers::withoutGlobalScopes()->find($this->importerId);

        if (!$importer || $importer->status !== 'active') {
            Log::info(__METHOD__ . '| Skipping lead, importer missing or not active | importer: ' . $this->importerId);
            return;
        }

        LeadImporterService::import($importer, $this->lead);
    }
}
