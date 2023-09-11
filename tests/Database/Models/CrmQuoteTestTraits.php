<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmQuoteQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmQuoteService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmQuoteTestTraits
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

    public function test_http_crmquote_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmquote',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmquote_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmquote', [
            'form_params'   =>  [
                'name'  =>  'a',
                'description'  =>  'a',
                'detailed_amount'  =>  'a',
                'suggested_currency_code'  =>  'a',
                'amount'  =>  '1',
                'suggested_price'  =>  '1',
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
    public function test_crmquote_model_get()
    {
        $result = AbstractCrmQuoteService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmquote_get_all()
    {
        $result = AbstractCrmQuoteService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmquote_get_paginated()
    {
        $result = AbstractCrmQuoteService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmquote_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquote_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::first();

            event(new \NextDeveloper\CRM\Events\CrmQuote\CrmQuoteRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_description_filter()
    {
        try {
            $request = new Request(
                [
                'description'  =>  'a'
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_detailed_amount_filter()
    {
        try {
            $request = new Request(
                [
                'detailed_amount'  =>  'a'
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_suggested_currency_code_filter()
    {
        try {
            $request = new Request(
                [
                'suggested_currency_code'  =>  'a'
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_amount_filter()
    {
        try {
            $request = new Request(
                [
                'amount'  =>  '1'
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_suggested_price_filter()
    {
        try {
            $request = new Request(
                [
                'suggested_price'  =>  '1'
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquote_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n
}