<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmMeetingQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmMeetingService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmMeetingTestTraits
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

    public function test_http_crmmeeting_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmmeeting',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmmeeting_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmmeeting', [
            'form_params'   =>  [
                'meeting_note'  =>  'a',
                'outcome'  =>  'a',
                'iam_account_it'  =>  '1',
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
    public function test_crmmeeting_model_get()
    {
        $result = AbstractCrmMeetingService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmmeeting_get_all()
    {
        $result = AbstractCrmMeetingService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmmeeting_get_paginated()
    {
        $result = AbstractCrmMeetingService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmmeeting_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmmeeting_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::first();

            event(new \NextDeveloper\CRM\Events\CrmMeeting\CrmMeetingRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_meeting_note_filter()
    {
        try {
            $request = new Request(
                [
                'meeting_note'  =>  'a'
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_outcome_filter()
    {
        try {
            $request = new Request(
                [
                'outcome'  =>  'a'
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_iam_account_it_filter()
    {
        try {
            $request = new Request(
                [
                'iam_account_it'  =>  '1'
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmmeeting_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmMeetingQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmMeeting::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}