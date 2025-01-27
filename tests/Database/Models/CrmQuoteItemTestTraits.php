<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmQuoteItemQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmQuoteItemService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmQuoteItemTestTraits
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

    public function test_http_crmquoteitem_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmquoteitem',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmquoteitem_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmquoteitem', [
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
    public function test_crmquoteitem_model_get()
    {
        $result = AbstractCrmQuoteItemService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmquoteitem_get_all()
    {
        $result = AbstractCrmQuoteItemService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmquoteitem_get_paginated()
    {
        $result = AbstractCrmQuoteItemService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmquoteitem_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmquoteitem_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::first();

            event(new \NextDeveloper\CRM\Events\CrmQuoteItem\CrmQuoteItemRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_quantity_filter()
    {
        try {
            $request = new Request(
                [
                'quantity'  =>  '1'
                ]
            );

            $filter = new CrmQuoteItemQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmQuoteItemQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmQuoteItemQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmQuoteItemQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteItemQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteItemQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteItemQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteItemQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteItemQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmquoteitem_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmQuoteItemQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmQuoteItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}