<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmCampaignTargetQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmCampaignTargetService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmCampaignTargetTestTraits
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

    public function test_http_crmcampaigntarget_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmcampaigntarget',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmcampaigntarget_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmcampaigntarget', [
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
    public function test_crmcampaigntarget_model_get()
    {
        $result = AbstractCrmCampaignTargetService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmcampaigntarget_get_all()
    {
        $result = AbstractCrmCampaignTargetService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmcampaigntarget_get_paginated()
    {
        $result = AbstractCrmCampaignTargetService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmcampaigntarget_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaigntarget_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmcampaigntarget_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::first();

            event(new \NextDeveloper\CRM\Events\CrmCampaignTarget\CrmCampaignTargetRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaigntarget_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmCampaignTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaigntarget_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmCampaignTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaigntarget_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaigntarget_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaigntarget_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmcampaigntarget_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmCampaignTargetQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmCampaignTarget::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}