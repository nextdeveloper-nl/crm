<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmUserQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmUserService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmUserTestTraits
{
    public $http;

    /**
    *   Creating the Guzzle object
    */
    public function setupGuzzle()
    {
        $this->http = new Client([
            'base_uri'  =>  '127.0.0.1:8000'
        ]);
    }

    /**
    *   Destroying the Guzzle object
    */
    public function destroyGuzzle()
    {
        $this->http = null;
    }

    public function test_http_crmuser_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmuser',
            ['http_errors' => false]
        );

        $this->assertContains($response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
        ]);
    }

    public function test_http_crmuser_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request('POST', '/crm/crmuser', [
            'form_params'   =>  [
                'position'  =>  'a',
                'job_description'  =>  'a',
                'hobbies'  =>  'a',
                'city'  =>  'a',
                'child_count'  =>  '1',
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
    public function test_crmuser_model_get()
    {
        $result = AbstractCrmUserService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmuser_get_all()
    {
        $result = AbstractCrmUserService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmuser_get_paginated()
    {
        $result = AbstractCrmUserService::get(null, [
            'paginated' =>  'true'
        ]);

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmuser_event_retrieved_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserRetrievedEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_created_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserCreatedEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_creating_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserCreatingEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_saving_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserSavingEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_saved_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserSavedEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_updating_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserUpdatingEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_updated_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserUpdatedEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_deleting_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserDeletingEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_deleted_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserDeletedEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_restoring_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserRestoringEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_restored_without_object()
    {
        try {
            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserRestoredEvent() );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserRetrievedEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserCreatedEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserCreatingEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserSavingEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserSavedEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserUpdatingEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserUpdatedEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserDeletingEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserDeletedEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserRestoringEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmuser_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUser::first();

            event( new \NextDeveloper\CRM\Events\CrmUser\CrmUserRestoredEvent($model) );
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_position_filter()
    {
        try {
            $request = new Request([
                'position'  =>  'a'
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_job_description_filter()
    {
        try {
            $request = new Request([
                'job_description'  =>  'a'
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_hobbies_filter()
    {
        try {
            $request = new Request([
                'hobbies'  =>  'a'
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_city_filter()
    {
        try {
            $request = new Request([
                'city'  =>  'a'
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_child_count_filter()
    {
        try {
            $request = new Request([
                'child_count'  =>  '1'
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_created_at_filter_start()
    {
        try {
            $request = new Request([
                'created_atStart'  =>  now()
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_updated_at_filter_start()
    {
        try {
            $request = new Request([
                'updated_atStart'  =>  now()
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_deleted_at_filter_start()
    {
        try {
            $request = new Request([
                'deleted_atStart'  =>  now()
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_created_at_filter_end()
    {
        try {
            $request = new Request([
                'created_atEnd'  =>  now()
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_updated_at_filter_end()
    {
        try {
            $request = new Request([
                'updated_atEnd'  =>  now()
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_deleted_at_filter_end()
    {
        try {
            $request = new Request([
                'deleted_atEnd'  =>  now()
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request([
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request([
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmuser_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request([
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
            ]);

            $filter = new CrmUserQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUser::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}