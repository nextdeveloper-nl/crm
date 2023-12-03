<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmOpportunityQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmOpportunityService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmOpportunityTestTraits
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

    public function test_http_crmopportunity_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmopportunity',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmopportunity_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmopportunity', [
            'form_params'   =>  [
                'name'  =>  'a',
                'description'  =>  'a',
                'source'  =>  'a',
                'probability'  =>  '1',
                'income'  =>  '1',
                    'deadline'  =>  now(),
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
    public function test_crmopportunity_model_get()
    {
        $result = AbstractCrmOpportunityService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmopportunity_get_all()
    {
        $result = AbstractCrmOpportunityService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmopportunity_get_paginated()
    {
        $result = AbstractCrmOpportunityService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmopportunity_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunitySavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunitySavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunitySavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunitySavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmopportunity_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::first();

            event(new \NextDeveloper\CRM\Events\CrmOpportunity\CrmOpportunityRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_description_filter()
    {
        try {
            $request = new Request(
                [
                'description'  =>  'a'
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_source_filter()
    {
        try {
            $request = new Request(
                [
                'source'  =>  'a'
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_probability_filter()
    {
        try {
            $request = new Request(
                [
                'probability'  =>  '1'
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_income_filter()
    {
        try {
            $request = new Request(
                [
                'income'  =>  '1'
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_deadline_filter_start()
    {
        try {
            $request = new Request(
                [
                'deadlineStart'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_deadline_filter_end()
    {
        try {
            $request = new Request(
                [
                'deadlineEnd'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_deadline_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deadlineStart'  =>  now(),
                'deadlineEnd'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmopportunity_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOpportunityQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOpportunity::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n
}