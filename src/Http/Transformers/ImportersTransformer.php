<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Importers;
use NextDeveloper\CRM\Services\ImportersService;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractImportersTransformer;

/**
 * Class ImportersTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class ImportersTransformer extends AbstractImportersTransformer
{

    /**
     * @param Importers $model
     *
     * @return array
     */
    public function transform(Importers $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Importers', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $this->withIntakeFields($transformed, $model);
        }

        $transformed = parent::transform($model);

        // Public API exposes the linked object's uuid, not its integer id
        $transformed['object_id'] = ImportersService::linkedObjectUuid($model->object_type, $model->object_id);

        // The token hash is a secret: never in the response, never in the cache
        unset($transformed['config']['intake_token_hash']);

        Cache::set(
            CacheHelper::getKey('Importers', $model->uuid, 'Transformed'),
            $transformed
        );

        return $this->withIntakeFields($transformed, $model);
    }

    /**
     * Intake fields added after the cache: whether a token exists, the endpoint url and, only on the create response,
     * the plain token (transient attribute set by ImportersService::create, shown once and never cached).
     */
    private function withIntakeFields(array $transformed, Importers $model): array
    {
        unset($transformed['config']['intake_token_hash']);

        $transformed['has_intake_token'] = !empty($model->config['intake_token_hash']);
        $transformed['intake_url'] = ImportersService::intakeUrl($model);

        if (!empty($model->intake_token_plain)) {
            $transformed['intake_token'] = $model->intake_token_plain;
        }

        return $transformed;
    }
}
