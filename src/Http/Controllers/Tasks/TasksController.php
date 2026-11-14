<?php

namespace NextDeveloper\CRM\Http\Controllers\Tasks;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\Tasks\TasksUpdateRequest;
use NextDeveloper\CRM\Database\Filters\TasksQueryFilter;
use NextDeveloper\CRM\Database\Models\Tasks;
use NextDeveloper\CRM\Services\TasksService;
use NextDeveloper\CRM\Http\Requests\Tasks\TasksCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags;use NextDeveloper\Commons\Http\Traits\Addresses;
class TasksController extends AbstractController
{
    private $model = Tasks::class;

    use Tags;
    use Addresses;
    /**
     * This method returns the list of tasks.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  TasksQueryFilter $filter  An object that builds search query
     * @param  Request          $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TasksQueryFilter $filter, Request $request)
    {
        $data = TasksService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = TasksService::getActions();

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * Makes the related action to the object
     *
     * @param  $objectId
     * @param  $action
     * @return array
     */
    public function doAction($objectId, $action)
    {
        $actionId = TasksService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $tasksId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = TasksService::getByRef($ref);

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method returns the list of sub objects the related object. Sub object means an object which is preowned by
     * this object.
     *
     * It can be tags, addresses, states etc.
     *
     * @param  $ref
     * @param  $subObject
     * @return void
     */
    public function relatedObjects($ref, $subObject)
    {
        $objects = TasksService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created Tasks object on database.
     *
     * @param  TasksCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(TasksCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = TasksService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates Tasks object on database.
     *
     * @param  $tasksId
     * @param  TasksUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($tasksId, TasksUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = TasksService::update($tasksId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates Tasks object on database.
     *
     * @param  $tasksId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($tasksId)
    {
        $model = TasksService::delete($tasksId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * Saves dates and progress of several tasks in one call, all or nothing (leo4#1245).
     * Body: { tasks: [{ id, start_date?, due_date?, progress? }] }
     */
    public function schedule(\NextDeveloper\CRM\Http\Requests\Tasks\TasksScheduleRequest $request)
    {
        $tasks = \NextDeveloper\CRM\Services\TaskScheduleService::bulkSchedule($request->validated()['tasks']);

        return ResponsableFactory::makeResponse($this, array_map(fn ($task) => [
            'id'         => $task->uuid,
            'start_date' => $task->start_date?->toIso8601String(),
            'due_date'   => $task->due_date?->toIso8601String(),
            'progress'   => $task->progress,
        ], array_filter($tasks)));
    }

    /**
     * Links of a task, both the tasks it waits for and the tasks waiting for it (leo4#1245).
     */
    public function dependencies($tasksId)
    {
        return ResponsableFactory::makeResponse($this, \NextDeveloper\CRM\Services\TaskScheduleService::dependenciesOf($tasksId));
    }

    /**
     * Makes the successor wait for the predecessor (leo4#1245).
     * Body: { predecessor_id, successor_id, type?, lag_days? }
     */
    public function addDependency(\NextDeveloper\CRM\Http\Requests\Tasks\TasksDependencyRequest $request)
    {
        $data = $request->validated();

        $link = \NextDeveloper\CRM\Services\TaskScheduleService::addDependency(
            $data['predecessor_id'],
            $data['successor_id'],
            $data['type'] ?? 'finish_to_start',
            (int) ($data['lag_days'] ?? 0),
        );

        return ResponsableFactory::makeResponse($this, [
            'id'             => $link->uuid,
            'predecessor_id' => $data['predecessor_id'],
            'successor_id'   => $data['successor_id'],
            'type'           => $link->type,
            'lag_days'       => $link->lag_days,
        ]);
    }

    /**
     * Removes a link between two tasks (leo4#1245).
     */
    public function removeDependency($dependencyId)
    {
        \NextDeveloper\CRM\Services\TaskScheduleService::removeDependency($dependencyId);

        return $this->noContent();
    }

}
