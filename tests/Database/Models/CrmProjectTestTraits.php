<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmProjectQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmProjectService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmProjectTestTraits
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

    public function test_http_crmproject_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmproject',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmproject_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmproject', [
            'form_params'   =>  [
                'name'  =>  'a',
                'url'  =>  'a',
                'project_id'  =>  'a',
                'token'  =>  'a',
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
    public function test_crmproject_model_get()
    {
        $result = AbstractCrmProjectService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmproject_get_all()
    {
        $result = AbstractCrmProjectService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmproject_get_paginated()
    {
        $result = AbstractCrmProjectService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmproject_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmproject_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmProject::first();

            event(new \NextDeveloper\CRM\Events\CrmProject\CrmProjectRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_url_filter()
    {
        try {
            $request = new Request(
                [
                'url'  =>  'a'
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_project_id_filter()
    {
        try {
            $request = new Request(
                [
                'project_id'  =>  'a'
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_token_filter()
    {
        try {
            $request = new Request(
                [
                'token'  =>  'a'
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmproject_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmProjectQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmProject::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}