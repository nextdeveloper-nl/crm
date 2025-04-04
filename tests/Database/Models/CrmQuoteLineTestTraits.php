<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmQuoteLineQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmQuoteLineService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmQuoteLineTestTraits
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

    public function test_http_crmquoteline_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmquoteline',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmquoteline_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmquoteline', [
            'form_params'   =>  [
                'quantity'  =>  '1',
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
    public function test_crmquoteline_model_get()
    {
        $result = AbstractCrmQuoteLineService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmquoteline_get_all()
    {
        $result = AbstractCrmQuoteLineService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmquoteline_get_paginated()
    {
        $result = AbstractCrmQuoteLineService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmquoteline_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteline_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteLine\CrmQuoteLineRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_quantity_filter()
    {
        try {
            $request = new Request(
                [
                'quantity'  =>  '1'
                ]
            );

            $filter = new CrmQuoteLineQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmQuoteLineQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmQuoteLineQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmQuoteLineQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteLineQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteLineQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteLineQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteLineQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteLineQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteline_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteLineQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteLine::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}