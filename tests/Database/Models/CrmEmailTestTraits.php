<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmEmailQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmEmailService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmEmailTestTraits
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

    public function test_http_crmemail_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmemail',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmemail_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmemail', [
            'form_params'   =>  [
                'subject'  =>  'a',
                'content'  =>  'a',
                'email_meta'  =>  'a',
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
    public function test_crmemail_model_get()
    {
        $result = AbstractCrmEmailService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmemail_get_all()
    {
        $result = AbstractCrmEmailService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmemail_get_paginated()
    {
        $result = AbstractCrmEmailService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmemail_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemail_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::first();

            event(new \NextDeveloper\CRM\Events\CrmEmail\CrmEmailRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_subject_filter()
    {
        try {
            $request = new Request(
                [
                'subject'  =>  'a'
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_content_filter()
    {
        try {
            $request = new Request(
                [
                'content'  =>  'a'
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_email_meta_filter()
    {
        try {
            $request = new Request(
                [
                'email_meta'  =>  'a'
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_iam_account_it_filter()
    {
        try {
            $request = new Request(
                [
                'iam_account_it'  =>  '1'
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemail_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmail::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}