<?php

namespace NextDeveloper\CRM\Http\Transformers\AbstractTransformers;

use NextDeveloper\CRM\Database\Models\Notes;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class NotesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AbstractNotesTransformer extends AbstractTransformer
{

    /**
     * @param Notes $model
     *
     * @return array
     */
    public function transform(Notes $model)
    {
                        $crmAccountId = \NextDeveloper\CRM\Database\Models\Accounts::where('id', $model->crm_account_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'crm_account_id'  =>  $crmAccountId ? $crmAccountId->uuid : null,
            'note'  =>  $model->note,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            'deleted_at'  =>  $model->deleted_at,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
