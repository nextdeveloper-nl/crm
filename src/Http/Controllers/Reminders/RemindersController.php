<?php

namespace NextDeveloper\CRM\Http\Controllers\Reminders;

use Carbon\Carbon;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\CRM\Http\Requests\Reminders\RemindersCreateRequest;
use NextDeveloper\CRM\Http\Requests\Reminders\RemindersIndexRequest;
use NextDeveloper\CRM\Http\Requests\Reminders\RemindersSnoozeRequest;
use NextDeveloper\CRM\Services\RemindersService;

/**
 * Reminders on CRM records. Only validates and delegates to RemindersService; the reminders
 * themselves are Communication's remindables.
 */
class RemindersController extends AbstractController
{
    /**
     * Lists the reminders of one CRM record (pending only, unless include_done is set).
     */
    public function index(RemindersIndexRequest $request)
    {
        $data = RemindersService::forObject(
            $request->validated()['object_type'],
            $request->validated()['object_id'],
            $request->boolean('include_done')
        );

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * Creates a reminder on a CRM record for the caller, or for iam_user_id if given.
     */
    public function store(RemindersCreateRequest $request)
    {
        $data = $request->validated();

        $model = RemindersService::remind(
            $data['object_type'],
            $data['object_id'],
            Carbon::parse($data['remind_datetime']),
            $data['note'] ?? null,
            $data['iam_user_id'] ?? null
        );

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * Snoozes a reminder until snooze_datetime; it is sent again at that time.
     */
    public function snooze($ref, RemindersSnoozeRequest $request)
    {
        $model = RemindersService::snooze($ref, Carbon::parse($request->validated()['snooze_datetime']));

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * Marks a reminder as done.
     */
    public function acknowledge($ref)
    {
        $model = RemindersService::acknowledge($ref);

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * Cancels a reminder so it is never sent.
     */
    public function cancel($ref)
    {
        $model = RemindersService::cancel($ref);

        return ResponsableFactory::makeResponse($this, $model);
    }
}
