<?php

namespace NextDeveloper\CRM\Events\CrmUser;

use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\CrmUser;

/**
 * Class CrmUserRestoringEvent
 * @package NextDeveloper\CRM\Events
 */
class CrmUserRestoringEvent
{
    use SerializesModels;

    /**
     * @var CrmUser
     */
    public $_model;

    /**
     * @var int|null
     */
    protected $timestamp = null;

    public function __construct(CrmUser $model = null) {
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