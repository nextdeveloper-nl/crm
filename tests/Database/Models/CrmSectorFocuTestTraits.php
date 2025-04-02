<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmSectorFocuQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmSectorFocuService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmSectorFocuTestTraits
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

    public function test_http_crmsectorfocu_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmsectorfocu',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmsectorfocu_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmsectorfocu', [
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
    public function test_crmsectorfocu_model_get()
    {
        $result = AbstractCrmSectorFocuService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmsectorfocu_get_all()
    {
        $result = AbstractCrmSectorFocuService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmsectorfocu_get_paginated()
    {
        $result = AbstractCrmSectorFocuService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmsectorfocu_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmsectorfocu_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmsectorfocu_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::first();

            event(new \NextDeveloper\CRM\Events\CrmSectorFocu\CrmSectorFocuRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmsectorfocu_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmSectorFocuQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmSectorFocu::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}