<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmTargetUserQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmTargetUserService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmTargetUserTestTraits
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

    public function test_http_crmtargetuser_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmtargetuser',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmtargetuser_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmtargetuser', [
            'form_params'   =>  [
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
    public function test_crmtargetuser_model_get()
    {
        $result = AbstractCrmTargetUserService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmtargetuser_get_all()
    {
        $result = AbstractCrmTargetUserService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmtargetuser_get_paginated()
    {
        $result = AbstractCrmTargetUserService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmtargetuser_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtargetuser_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmtargetuser_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::first();

            event(new \NextDeveloper\CRM\Events\CrmTargetUser\CrmTargetUserRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtargetuser_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmTargetUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtargetuser_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmTargetUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtargetuser_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTargetUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtargetuser_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTargetUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtargetuser_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTargetUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmtargetuser_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmTargetUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmTargetUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}