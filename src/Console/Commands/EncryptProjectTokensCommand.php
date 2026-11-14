<?php

namespace NextDeveloper\CRM\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

/**
 * One-off data migration for crm_projects.token, which used to be stored in plain text.
 * Encrypts every value that isn't already encrypted with the app key. Safe to run again.
 */
class EncryptProjectTokensCommand extends Command
{
    protected $signature = 'crm:encrypt-project-tokens {--dry-run : Only count the plain-text tokens}';

    protected $description = 'Encrypts crm_projects.token values that are still stored in plain text.';

    public function handle(): int
    {
        $plain = 0;
        $alreadyEncrypted = 0;

        DB::table('crm_projects')
            ->whereNotNull('token')
            ->where('token', '<>', '')
            ->orderBy('id')
            ->select(['id', 'token'])
            ->chunkById(200, function ($rows) use (&$plain, &$alreadyEncrypted) {
                foreach ($rows as $row) {
                    $encrypted = self::encryptIfPlain($row->token);

                    if ($encrypted === $row->token) {
                        $alreadyEncrypted++;

                        continue;
                    }

                    $plain++;

                    if (!$this->option('dry-run')) {
                        DB::table('crm_projects')->where('id', $row->id)->update(['token' => $encrypted]);
                    }
                }
            });

        $this->info(($this->option('dry-run') ? 'Would encrypt ' : 'Encrypted ') . "{$plain} token(s); {$alreadyEncrypted} already encrypted.");

        return self::SUCCESS;
    }

    /**
     * Returns the value encrypted the way the model's `encrypted` cast expects it,
     * or unchanged when it already decrypts with the app key.
     */
    public static function encryptIfPlain(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        try {
            Crypt::decryptString($value);

            return $value;
        } catch (DecryptException) {
            return Crypt::encryptString($value);
        }
    }
}
