<?php

namespace NextDeveloper\CRM\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use NextDeveloper\Commons\Database\Models\Languages;
use NextDeveloper\CRM\Database\Filters\UsersQueryFilter;
use NextDeveloper\CRM\Database\Models\Users;
use NextDeveloper\CRM\Services\AbstractServices\AbstractUsersService;

/**
 * This class is responsible from managing the data for Users
 *
 * Class UsersService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class UsersService extends AbstractUsersService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
    public static function get(UsersQueryFilter $filter = null, array $params = []) : Collection|LengthAwarePaginator {
        return parent::get($filter, $params);
    }

    /**
     * Manipulating the function here
     *
     * @param array $data
     * @return Users
     * @throws \Exception
     */
    public static function create(array $data) : Users {
        if(!array_key_exists('common_language_id', $data)) {
            $lang = Languages::withoutGlobalScopes()->where('code', App::currentLocale())->first();

            if($lang == null)
                $lang = Languages::withoutGlobalScopes()->where('code', 'en')->first();

            $data['common_language_id'] = $lang->id;
        }

        return parent::create($data);
    }

    public static function getByEmail($email, $createUser = false) : ?Users {
        $user = Users::where('email', $email)->first();

        return $user;
    }
}
