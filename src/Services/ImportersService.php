<?php

namespace NextDeveloper\CRM\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use NextDeveloper\CRM\Services\AbstractServices\AbstractImportersService;

/**
 * This class is responsible from managing the data for Importers
 *
 * Class ImportersService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class ImportersService extends AbstractImportersService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * object_id may be sent as the linked object's uuid (public API) or as its integer id (internal callers);
     * it is stored as the integer id.
     */
    public static function create(array $data)
    {
        return parent::create(self::resolveLinkedObject($data));
    }

    public static function update($id, array $data)
    {
        return parent::update($id, self::resolveLinkedObject($data));
    }

    /**
     * Converts a uuid object_id into the integer id of the model named by object_type.
     * The lookup runs through the model's normal scopes, so a caller can only link objects they may see.
     */
    private static function resolveLinkedObject(array $data): array
    {
        if (!isset($data['object_id']) || !is_string($data['object_id']) || !Str::isUuid($data['object_id'])) {
            return $data;
        }

        $class = $data['object_type'] ?? null;

        // Only real Eloquent models; object_type comes from the request
        if (!$class || !class_exists($class) || !is_subclass_of($class, Model::class)) {
            throw new \InvalidArgumentException('object_type must be a model class when object_id is a uuid.');
        }

        $object = $class::where('uuid', $data['object_id'])->first();

        if (!$object) {
            throw new \InvalidArgumentException('The linked object could not be found.');
        }

        $data['object_id'] = $object->id;

        return $data;
    }

    /**
     * Public representation of object_id: the linked object's uuid (never the integer id).
     */
    public static function linkedObjectUuid(?string $class, ?int $id): ?string
    {
        if (!$class || !$id || !class_exists($class) || !is_subclass_of($class, Model::class)) {
            return null;
        }

        return $class::withoutGlobalScopes()->where('id', $id)->value('uuid');
    }
}