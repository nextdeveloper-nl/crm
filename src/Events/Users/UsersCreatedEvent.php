<?php

namespace NextDeveloper\CRM\Events\Users;

use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Users;

/**
 * Class UsersCreatedEvent
 * @package NextDeveloper\CRM\Events
 */
class UsersCreatedEvent
{
    use SerializesModels;

    /**
     * @var Users
     */
    public $_model;

    /**
     * @var int|null
     */
    protected $timestamp = null;

    public function __construct(Users $model = null) {
        $this->_model = $model;
    }

    /**
    * @param int $value
    *
    * @return AbstractEvent
    */
    public function setTimestamp($value) {
        $this->timestamp = $value;

        return $this;
    }

    /**
    * @return int|null
    */
    public function getTimestamp() {
        return $this->timestamp;
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}