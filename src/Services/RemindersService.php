<?php

namespace NextDeveloper\CRM\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use NextDeveloper\Commons\Common\Enums\GenericErrorCodes;
use NextDeveloper\Commons\Helpers\ObjectHelper;
use NextDeveloper\Communication\Database\Models\Remindables;
use NextDeveloper\Communication\Services\RemindablesService;

/**
 * Reminders on CRM records (accounts, opportunities, quotes, ...).
 *
 * A thin CRM facing layer over Communication's reminders (communication_remindables):
 * it only accepts CRM records, resolves them from their uuid and stores the reminder
 * through RemindablesService. Sending is done by Communication's
 * communication:dispatch-due-reminders command, which notifies the owner in-app and by email.
 */
class RemindersService
{
    /**
     * Creates a reminder on a CRM record.
     *
     * @param  string       $objectType  Full model class (NextDeveloper\CRM\Database\Models\Accounts)
     *                                   or its public form (NextDeveloper\CRM\Accounts)
     * @param  string       $objectId    uuid of the record
     * @param  Carbon       $remindAt    when to remind
     * @param  string|null  $note        what to remember
     * @param  string|null  $iamUserId   uuid of the user to remind; defaults to the caller
     */
    public static function remind(
        string  $objectType,
        string  $objectId,
        Carbon  $remindAt,
        ?string $note = null,
        ?string $iamUserId = null
    ): Remindables {
        $object = self::findObject($objectType, $objectId);

        $data = [
            'object_type'     => get_class($object),
            'object_id'       => $object->id,
            'remind_datetime' => $remindAt,
            'note'            => $note,
        ];

        // Without an explicit user, RemindablesService fills iam_user_id / iam_account_id
        // from UserHelper::me() / UserHelper::currentAccount().
        if ($iamUserId) {
            $data['iam_user_id'] = $iamUserId;
        }

        return RemindablesService::create($data);
    }

    /**
     * Reminders on one CRM record that the caller can see (AuthorizationScope applies).
     * Pending ones only, unless $includeDone is set.
     */
    public static function forObject(string $objectType, string $objectId, bool $includeDone = false): Collection
    {
        $object = self::findObject($objectType, $objectId);

        $query = Remindables::where('object_type', get_class($object))
            ->where('object_id', $object->id)
            ->orderBy('remind_datetime');

        if (! $includeDone) {
            $query->where('is_acknowledged', false)
                  ->where('is_cancelled', false);
        }

        return $query->get();
    }

    /**
     * Snoozes a CRM reminder until the given time. The reminder becomes due again then,
     * even if it was already sent.
     */
    public static function snooze(string $id, Carbon $until): Remindables
    {
        return RemindablesService::snooze(self::findReminder($id)->uuid, $until);
    }

    /**
     * Marks a CRM reminder as done; it will not be sent (again).
     */
    public static function acknowledge(string $id): Remindables
    {
        return RemindablesService::acknowledge(self::findReminder($id)->uuid);
    }

    /**
     * Cancels a CRM reminder so it is never sent.
     */
    public static function cancel(string $id): Remindables
    {
        return RemindablesService::cancel(self::findReminder($id)->uuid);
    }

    /**
     * Finds a reminder by uuid that the caller can see (AuthorizationScope applies) and
     * that is about a CRM record, so these endpoints cannot touch other modules' reminders.
     */
    private static function findReminder(string $id): Remindables
    {
        $reminder = Remindables::where('uuid', $id)->first();

        if (! $reminder || ! str_starts_with((string) $reminder->object_type, 'NextDeveloper\\CRM\\Database\\Models\\')) {
            self::refuse('id', 'We cannot find this reminder. Maybe you do not have the permission to see it?');
        }

        return $reminder;
    }

    /**
     * Resolves a CRM record from its type and uuid. The lookup keeps the model's global
     * scopes, so the caller can only put reminders on records they are allowed to see.
     */
    private static function findObject(string $objectType, string $objectId): mixed
    {
        $class = ltrim($objectType, '\\');

        if (count(explode('\\', $class)) === 3) {
            $class = ltrim(ObjectHelper::getPrivateObjectName($class), '\\');
        }

        if (! str_starts_with($class, 'NextDeveloper\\CRM\\Database\\Models\\') || ! class_exists($class)) {
            self::refuse('object_type', 'object_type must be a CRM model, for example NextDeveloper\\CRM\\Accounts.');
        }

        $object = $class::where('uuid', $objectId)->first();

        if (! $object) {
            self::refuse('object_id', 'object_id must be the id of an existing record.');
        }

        return $object;
    }

    private static function refuse(string $field, string $message): never
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed. Please fix the values you are providing and try again.',
            'code'    => GenericErrorCodes::VALIDATION_FAILED,
            'errors'  => [$field => [$message]],
        ], 422));
    }
}
