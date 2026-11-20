<?php

namespace NextDeveloper\CRM\Http\Requests\AccountContactShares;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountContactSharesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            // A share is only created or removed, never edited.
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}