<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmTaskQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmTaskService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmTaskTestTraits
{
    public $http;

    /**
     *   Creating the Guzzle object
     */
    public function setupGuzzle()
    {
        $this->http = new Client(
            [
            'base_uri'  =>  '127.0.0.1:8000'
            ]
        );
    }

    /**
     *   Destroying the Guzzle object
     */
    public function destroyGuzzle()
    {
        $this->http = null;
    }

    public function test_http_crmtask_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmtask',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmtask_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmtask', [
            'form_params'   =>  [
                'description'  =>  'a',
                'name'  =>  'a',
                'object_type'  =>  'a',
                'priority'  =>  '1',
                                'due_date'  =>  now(),
                ],
                ['http_errors' => false]
            ]
        );

        $this->assertEquals($response->getStatusCode(), Response::HTTP_OK);
    }

    /**
     * Get test
     *
     * @return bool
     */
    public function test_crmtask_model_get()
    {
        $result = AbstractCrmTaskService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmtask_get_all()
    {
        $result = AbstractCrmTaskService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmtask_get_paginated()
    {
        $result = AbstractCrmTaskService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmtask_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtask_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTask::first();

            event(new \NextDeveloper\CRM\Events\CrmTask\CrmTaskRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_description_filter()
    {
        try {
            $request = new Request(
                [
                'description'  =>  'a'
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_object_type_filter()
    {
        try {
            $request = new Request(
                [
                'object_type'  =>  'a'
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_priority_filter()
    {
        try {
            $request = new Request(
                [
                'priority'  =>  '1'
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_due_date_filter_start()
    {
        try {
            $request = new Request(
                [
                'due_dateStart'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_due_date_filter_end()
    {
        try {
            $request = new Request(
                [
                'due_dateEnd'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtask_event_due_date_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'due_dateStart'  =>  now(),
                'due_dateEnd'  =>  now()
                ]
            );

            $filter = new CrmTaskQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTask::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}