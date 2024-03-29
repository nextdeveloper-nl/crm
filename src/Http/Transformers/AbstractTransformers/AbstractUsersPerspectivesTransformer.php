<?php

namespace NextDeveloper\CRM\Http\Transformers\AbstractTransformers;

use NextDeveloper\CRM\Database\Models\UsersPerspectives;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class UsersPerspectivesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AbstractUsersPerspectivesTransformer extends AbstractTransformer
{

    /**
     * @param UsersPerspectives $model
     *
     * @return array
     */
    public function transform(UsersPerspectives $model)
    {
                        $commonCountryId = \NextDeveloper\Commons\Database\Models\Countries::where('id', $model->common_country_id)->first();
                    $commonLanguageId = \NextDeveloper\Commons\Database\Models\Languages::where('id', $model->common_language_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'name'  =>  $model->name,
            'surname'  =>  $model->surname,
            'fullname'  =>  $model->fullname,
            'email'  =>  $model->email,
            'about'  =>  $model->about,
            'pronoun'  =>  $model->pronoun,
            'birthday'  =>  $model->birthday,
            'nin'  =>  $model->nin,
            'cell_phone'  =>  $model->cell_phone,
            'common_country_id'  =>  $commonCountryId ? $commonCountryId->uuid : null,
            'common_language_id'  =>  $commonLanguageId ? $commonLanguageId->uuid : null,
            'iam_updated_at'  =>  $model->iam_updated_at ? $model->iam_updated_at->toIso8601String() : null,
            'position'  =>  $model->position,
            'job'  =>  $model->job,
            'job_description'  =>  $model->job_description,
            'hobbies'  =>  $model->hobbies,
            'city'  =>  $model->city,
            'email_risk'  =>  $model->email_risk,
            'relationship_status'  =>  $model->relationship_status,
            'is_evangelist'  =>  $model->is_evangelist == 1 ? true : false,
            'martial_status'  =>  $model->martial_status,
            'education'  =>  $model->education,
            'child_count'  =>  $model->child_count,
            'created_at'  =>  $model->created_at ? $model->created_at->toIso8601String() : null,
            'updated_at'  =>  $model->updated_at ? $model->updated_at->toIso8601String() : null,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE




}
