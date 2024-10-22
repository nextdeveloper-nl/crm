<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmIdealCustomerProfileQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmIdealCustomerProfileService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmIdealCustomerProfileTestTraits
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

    public function test_http_crmidealcustomerprofile_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmidealcustomerprofile',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmidealcustomerprofile_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmidealcustomerprofile', [
            'form_params'   =>  [
                'company_size'  =>  'a',
                'additional_notes'  =>  'a',
                'growth_stage'  =>  'a',
                'business_model'  =>  'a',
                'name'  =>  'a',
                'description'  =>  'a',
                'technology_rank'  =>  '1',
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
    public function test_crmidealcustomerprofile_model_get()
    {
        $result = AbstractCrmIdealCustomerProfileService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmidealcustomerprofile_get_all()
    {
        $result = AbstractCrmIdealCustomerProfileService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmidealcustomerprofile_get_paginated()
    {
        $result = AbstractCrmIdealCustomerProfileService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmidealcustomerprofile_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmidealcustomerprofile_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::first();

            event(new \NextDeveloper\CRM\Events\CrmIdealCustomerProfile\CrmIdealCustomerProfileRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_company_size_filter()
    {
        try {
            $request = new Request(
                [
                'company_size'  =>  'a'
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_additional_notes_filter()
    {
        try {
            $request = new Request(
                [
                'additional_notes'  =>  'a'
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_growth_stage_filter()
    {
        try {
            $request = new Request(
                [
                'growth_stage'  =>  'a'
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_business_model_filter()
    {
        try {
            $request = new Request(
                [
                'business_model'  =>  'a'
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_description_filter()
    {
        try {
            $request = new Request(
                [
                'description'  =>  'a'
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_technology_rank_filter()
    {
        try {
            $request = new Request(
                [
                'technology_rank'  =>  '1'
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmidealcustomerprofile_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmIdealCustomerProfileQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmIdealCustomerProfile::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}