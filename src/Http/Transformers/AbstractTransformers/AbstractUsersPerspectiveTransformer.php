<?php

namespace NextDeveloper\CRM\Http\Transformers\AbstractTransformers;

use NextDeveloper\CRM\Database\Models\UsersPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class UsersPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AbstractUsersPerspectiveTransformer extends AbstractTransformer
{

    /**
     * @param UsersPerspective $model
     *
     * @return array
     */
    public function transform(UsersPerspective $model)
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
            'common_country_id'  =>  $commonCountryId ? $commonCountryId->uuid : null,
            'common_language_id'  =>  $commonLanguageId ? $commonLanguageId->uuid : null,
            'iam_updated_at'  =>  $model->iam_updated_at,
            'position'  =>  $model->position,
            'job'  =>  $model->job,
            'job_description'  =>  $model->job_description,
            'hobbies'  =>  $model->hobbies,
            'city'  =>  $model->city,
            'email_risk'  =>  $model->email_risk,
            'relationship_status'  =>  $model->relationship_status,
            'is_evangelist'  =>  $model->is_evangelist,
            'is_single'  =>  $model->is_single,
            'education'  =>  $model->education,
            'child_count'  =>  $model->child_count,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE















}
