<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmAccountManagerQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmAccountManagerService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmAccountManagerTestTraits
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

    public function test_http_crmaccountmanager_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmaccountmanager',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmaccountmanager_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmaccountmanager', [
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
    public function test_crmaccountmanager_model_get()
    {
        $result = AbstractCrmAccountManagerService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmaccountmanager_get_all()
    {
        $result = AbstractCrmAccountManagerService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmaccountmanager_get_paginated()
    {
        $result = AbstractCrmAccountManagerService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmaccountmanager_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccountmanager_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccountmanager_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::first();

            event(new \NextDeveloper\CRM\Events\CrmAccountManager\CrmAccountManagerRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccountmanager_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmAccountManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccountmanager_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmAccountManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccountmanager_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmAccountManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccountmanager_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmAccountManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccountmanager_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmAccountManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccountmanager_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmAccountManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccountManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n\n\n\n\n\n\n
}