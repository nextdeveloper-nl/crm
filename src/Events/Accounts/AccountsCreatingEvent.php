<?php

namespace NextDeveloper\CRM\Events\Accounts;

use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Accounts;

/**
 * Class AccountsCreatingEvent
 * @package NextDeveloper\CRM\Events
 */
class AccountsCreatingEvent
{
    use SerializesModels;

    /**
     * @var Accounts
     */
    public $_model;

    /**
     * @var int|null
     */
    protected $timestamp = null;

    public function __construct(Accounts $model = null) {
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