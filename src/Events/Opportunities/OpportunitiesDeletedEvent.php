<?php

namespace NextDeveloper\CRM\Events\Opportunities;

use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Opportunities;

/**
 * Class OpportunitiesDeletedEvent
 * @package NextDeveloper\CRM\Events
 */
class OpportunitiesDeletedEvent
{
    use SerializesModels;

    /**
     * @var Opportunities
     */
    public $_model;

    /**
     * @var int|null
     */
    protected $timestamp = null;

    public function __construct(Opportunities $model = null) {
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