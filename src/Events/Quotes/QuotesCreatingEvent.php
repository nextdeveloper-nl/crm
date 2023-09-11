<?php

namespace NextDeveloper\CRM\Events\Quotes;

use Illuminate\Queue\SerializesModels;
use NextDeveloper\CRM\Database\Models\Quotes;

/**
 * Class QuotesCreatingEvent
 *
 * @package NextDeveloper\CRM\Events
 */
class QuotesCreatingEvent
{
    use SerializesModels;

    /**
     * @var Quotes
     */
    public $_model;

    /**
     * @var int|null
     */
    protected $timestamp = null;

    public function __construct(Quotes $model = null)
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