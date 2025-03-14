<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmEmailTemplateQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmEmailTemplateService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmEmailTemplateTestTraits
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

    public function test_http_crmemailtemplate_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmemailtemplate',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmemailtemplate_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmemailtemplate', [
            'form_params'   =>  [
                'subject'  =>  'a',
                'content'  =>  'a',
                'email_meta'  =>  'a',
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
    public function test_crmemailtemplate_model_get()
    {
        $result = AbstractCrmEmailTemplateService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmemailtemplate_get_all()
    {
        $result = AbstractCrmEmailTemplateService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmemailtemplate_get_paginated()
    {
        $result = AbstractCrmEmailTemplateService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmemailtemplate_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmemailtemplate_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::first();

            event(new \NextDeveloper\CRM\Events\CrmEmailTemplate\CrmEmailTemplateRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_subject_filter()
    {
        try {
            $request = new Request(
                [
                'subject'  =>  'a'
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_content_filter()
    {
        try {
            $request = new Request(
                [
                'content'  =>  'a'
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_email_meta_filter()
    {
        try {
            $request = new Request(
                [
                'email_meta'  =>  'a'
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmemailtemplate_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmEmailTemplateQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmEmailTemplate::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}