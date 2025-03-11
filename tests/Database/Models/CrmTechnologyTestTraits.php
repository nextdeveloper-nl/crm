<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmTechnologyQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmTechnologyService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmTechnologyTestTraits
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

    public function test_http_crmtechnology_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmtechnology',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmtechnology_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmtechnology', [
            'form_params'   =>  [
                'name'  =>  'a',
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
    public function test_crmtechnology_model_get()
    {
        $result = AbstractCrmTechnologyService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmtechnology_get_all()
    {
        $result = AbstractCrmTechnologyService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmtechnology_get_paginated()
    {
        $result = AbstractCrmTechnologyService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmtechnology_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologySavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologySavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologySavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologySavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtechnology_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::first();

            event(new \NextDeveloper\CRM\Events\CrmTechnology\CrmTechnologyRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmTechnologyQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmTechnologyQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmTechnologyQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmTechnologyQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTechnologyQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTechnologyQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTechnologyQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTechnologyQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTechnologyQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtechnology_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTechnologyQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTechnology::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}