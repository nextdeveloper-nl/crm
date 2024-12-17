<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmIndustryQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmIndustryService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmIndustryTestTraits
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

    public function test_http_crmindustry_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmindustry',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmindustry_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmindustry', [
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
    public function test_crmindustry_model_get()
    {
        $result = AbstractCrmIndustryService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmindustry_get_all()
    {
        $result = AbstractCrmIndustryService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmindustry_get_paginated()
    {
        $result = AbstractCrmIndustryService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmindustry_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustrySavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustrySavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustrySavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustrySavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmindustry_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::first();

            event(new \NextDeveloper\CRM\Events\CrmIndustry\CrmIndustryRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmIndustryQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmIndustryQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmIndustryQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmIndustryQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIndustryQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIndustryQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIndustryQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIndustryQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIndustryQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmindustry_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIndustryQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIndustry::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}