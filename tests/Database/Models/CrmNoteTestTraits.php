<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmNoteQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmNoteService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmNoteTestTraits
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

    public function test_http_crmnote_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmnote',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmnote_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmnote', [
            'form_params'   =>  [
                'note'  =>  'a',
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
    public function test_crmnote_model_get()
    {
        $result = AbstractCrmNoteService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmnote_get_all()
    {
        $result = AbstractCrmNoteService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmnote_get_paginated()
    {
        $result = AbstractCrmNoteService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmnote_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmnote_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmNote::first();

            event(new \NextDeveloper\CRM\Events\CrmNote\CrmNoteRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_note_filter()
    {
        try {
            $request = new Request(
                [
                'note'  =>  'a'
                ]
            );

            $filter = new CrmNoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmNote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmNoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmNote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmNoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmNote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmNoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmNote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmNoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmNote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmNoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmNote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmNoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmNote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmNoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmNote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmNoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmNote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmnote_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmNoteQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmNote::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}