<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmCallQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmCallService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmCallTestTraits
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

    public function test_http_crmcall_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmcall',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmcall_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmcall', [
            'form_params'   =>  [
                'description'  =>  'a',
                'disposition'  =>  'a',
                'from_number'  =>  'a',
                'to_number'  =>  'a',
                'call_direction'  =>  'a',
                'name'  =>  'a',
                'iam_account_it'  =>  '1',
                'duration'  =>  '1',
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
    public function test_crmcall_model_get()
    {
        $result = AbstractCrmCallService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmcall_get_all()
    {
        $result = AbstractCrmCallService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmcall_get_paginated()
    {
        $result = AbstractCrmCallService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmcall_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcall_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCall::first();

            event(new \NextDeveloper\CRM\Events\CrmCall\CrmCallRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_description_filter()
    {
        try {
            $request = new Request(
                [
                'description'  =>  'a'
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_disposition_filter()
    {
        try {
            $request = new Request(
                [
                'disposition'  =>  'a'
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_from_number_filter()
    {
        try {
            $request = new Request(
                [
                'from_number'  =>  'a'
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_to_number_filter()
    {
        try {
            $request = new Request(
                [
                'to_number'  =>  'a'
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_call_direction_filter()
    {
        try {
            $request = new Request(
                [
                'call_direction'  =>  'a'
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_iam_account_it_filter()
    {
        try {
            $request = new Request(
                [
                'iam_account_it'  =>  '1'
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_duration_filter()
    {
        try {
            $request = new Request(
                [
                'duration'  =>  '1'
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcall_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCallQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCall::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}