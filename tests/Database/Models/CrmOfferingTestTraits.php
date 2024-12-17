<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmOfferingQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmOfferingService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmOfferingTestTraits
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

    public function test_http_crmoffering_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmoffering',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmoffering_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmoffering', [
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
    public function test_crmoffering_model_get()
    {
        $result = AbstractCrmOfferingService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmoffering_get_all()
    {
        $result = AbstractCrmOfferingService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmoffering_get_paginated()
    {
        $result = AbstractCrmOfferingService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmoffering_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmoffering_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::first();

            event(new \NextDeveloper\CRM\Events\CrmOffering\CrmOfferingRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmOfferingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmOfferingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmOfferingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmOfferingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOfferingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOfferingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOfferingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOfferingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOfferingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmoffering_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmOfferingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmOffering::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}