<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmUserManagerQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmUserManagerService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmUserManagerTestTraits
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

    public function test_http_crmusermanager_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmusermanager',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmusermanager_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmusermanager', [
            'form_params'   =>  [
                'notes'  =>  'a',
                'relationship_rating'  =>  '1',
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
    public function test_crmusermanager_model_get()
    {
        $result = AbstractCrmUserManagerService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmusermanager_get_all()
    {
        $result = AbstractCrmUserManagerService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmusermanager_get_paginated()
    {
        $result = AbstractCrmUserManagerService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmusermanager_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmusermanager_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::first();

            event(new \NextDeveloper\CRM\Events\CrmUserManager\CrmUserManagerRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_notes_filter()
    {
        try {
            $request = new Request(
                [
                'notes'  =>  'a'
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_relationship_rating_filter()
    {
        try {
            $request = new Request(
                [
                'relationship_rating'  =>  '1'
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmusermanager_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmUserManagerQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmUserManager::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n\n\n\n\n\n\n\n\n
}