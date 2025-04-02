<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmTargetQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmTargetService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmTargetTestTraits
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

    public function test_http_crmtarget_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmtarget',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmtarget_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmtarget', [
            'form_params'   =>  [
                'name'  =>  'a',
                'description'  =>  'a',
                'type'  =>  'a',
                'list_user_count'  =>  '1',
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
    public function test_crmtarget_model_get()
    {
        $result = AbstractCrmTargetService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmtarget_get_all()
    {
        $result = AbstractCrmTargetService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmtarget_get_paginated()
    {
        $result = AbstractCrmTargetService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmtarget_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtarget_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmTarget\CrmTargetRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_description_filter()
    {
        try {
            $request = new Request(
                [
                'description'  =>  'a'
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_type_filter()
    {
        try {
            $request = new Request(
                [
                'type'  =>  'a'
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_list_user_count_filter()
    {
        try {
            $request = new Request(
                [
                'list_user_count'  =>  '1'
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtarget_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}