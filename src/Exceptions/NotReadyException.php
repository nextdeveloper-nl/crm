<?php
/**
 * This file is part of the PlusClouds.Core library.
 *
 * (c) Semih Turna <semih.turna@plusclouds.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace  NextDeveloper\CRM\Exceptions;

use NextDeveloper\Commons\Exceptions\AbstractCommonsException;

/**
 * Class NotFoundException
 * @package  NextDeveloper\Commons\Exceptions
 */
class NotReadyException extends AbstractCommonsException
{
    /**
     * @var string
     */
    protected $defaultMessage = 'This object is not ready to be used. That is why we denied this request.';

    /**
     * @param $message
     * @param $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        $message = $this->defaultMessage . 'Error message is: ' . $message;

        parent::__construct($message, $code, $previous);
    }
}
