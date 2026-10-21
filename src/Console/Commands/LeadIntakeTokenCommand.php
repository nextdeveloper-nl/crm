<?php

namespace NextDeveloper\CRM\Console\Commands;

use Illuminate\Console\Command;
use NextDeveloper\CRM\Database\Models\Importers;
use NextDeveloper\CRM\Services\LeadIntakeService;

/**
 * Issues (or rotates) the secret token of an 'api' or 'ideasoft' lead importer, used by POST /public/lead-intake/{importer_id}.
 */
class LeadIntakeTokenCommand extends Command
{
    protected $signature = 'crm:lead-intake-token {importer : uuid of a crm_importers row with source_type api or ideasoft}';

    protected $description = 'Issues a new lead intake token for an api or ideasoft importer (the previous token stops working).';

    public function handle(): int
    {
        $importer = Importers::withoutGlobalScopes()
            ->where('uuid', $this->argument('importer'))
            ->whereIn('source_type', LeadIntakeService::SOURCE_TYPES)
            ->first();

        if (!$importer) {
            $this->error('No importer with source_type "api" or "ideasoft" and this uuid.');
            return self::FAILURE;
        }

        $this->info('Token (shown once): ' . LeadIntakeService::issueToken($importer));

        return self::SUCCESS;
    }
}
