<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmCampaignQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmCampaignService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmCampaignTestTraits
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

    public function test_http_crmcampaign_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmcampaign',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmcampaign_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmcampaign', [
            'form_params'   =>  [
                'name'  =>  'a',
                'description'  =>  'a',
                'status'  =>  'a',
                    'start_date'  =>  now(),
                    'end_date'  =>  now(),
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
    public function test_crmcampaign_model_get()
    {
        $result = AbstractCrmCampaignService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmcampaign_get_all()
    {
        $result = AbstractCrmCampaignService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmcampaign_get_paginated()
    {
        $result = AbstractCrmCampaignService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmcampaign_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaign_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaign\CrmCampaignRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_description_filter()
    {
        try {
            $request = new Request(
                [
                'description'  =>  'a'
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_status_filter()
    {
        try {
            $request = new Request(
                [
                'status'  =>  'a'
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_start_date_filter_start()
    {
        try {
            $request = new Request(
                [
                'start_dateStart'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_end_date_filter_start()
    {
        try {
            $request = new Request(
                [
                'end_dateStart'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_start_date_filter_end()
    {
        try {
            $request = new Request(
                [
                'start_dateEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_end_date_filter_end()
    {
        try {
            $request = new Request(
                [
                'end_dateEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_start_date_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'start_dateStart'  =>  now(),
                'start_dateEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_end_date_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'end_dateStart'  =>  now(),
                'end_dateEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaign_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaign::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}