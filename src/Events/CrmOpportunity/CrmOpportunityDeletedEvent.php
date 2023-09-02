<?php

namespace NextDeveloper\CRM\Events\CrmOpportunity;

use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\CrmOpportunity;

/**
 * Class CrmOpportunityDeletedEvent
 * @package NextDeveloper\CRM\Events
 */
class CrmOpportunityDeletedEvent
{
    use SerializesModels;

    /**
     * @var CrmOpportunity
     */
    public $_model;

    /**
     * @var int|null
     */
    protected $timestamp = null;

    public function __construct(CrmOpportunity $model = null) {
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