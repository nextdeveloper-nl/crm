<?php

namespace NextDeveloper\CRM\Events\UserManagers;

use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\UserManagers;

/**
 * Class UserManagersUpdatedEvent
 *
 * @package NextDeveloper\CRM\Events
 */
class UserManagersUpdatedEvent
{
    use SerializesModels;

    /**
     * @var UserManagers
     */
    public $_model;

    /**
     * @var int|null
     */
    protected $timestamp = null;

    public function __construct(UserManagers $model = null)
    {
        $this->_model = $model;
    }

    /**
     * @param int $value
     *
     * @return AbstractEvent
     */
    public function setTimestamp($value)
    {
        $this->timestamp = $value;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}