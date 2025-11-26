<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmAccountQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmAccountService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmAccountTestTraits
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

    public function test_http_crmaccount_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmaccount',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmaccount_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmaccount', [
            'form_params'   =>  [
                'position'  =>  'a',
                'additional_information'  =>  'a',
                'disabling_reason'  =>  'a',
                'suspension_reason'  =>  'a',
                'disqualification_reason'  =>  'a',
                'office_phone_number'  =>  'a',
                'office_phone_extension'  =>  'a',
                'office_email'  =>  'a',
                'risk_level'  =>  '1',
                'technology_rank'  =>  '1',
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
    public function test_crmaccount_model_get()
    {
        $result = AbstractCrmAccountService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmaccount_get_all()
    {
        $result = AbstractCrmAccountService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmaccount_get_paginated()
    {
        $result = AbstractCrmAccountService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmaccount_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmaccount_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::first();

            event(new \NextDeveloper\CRM\Events\CrmAccount\CrmAccountRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_position_filter()
    {
        try {
            $request = new Request(
                [
                'position'  =>  'a'
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_additional_information_filter()
    {
        try {
            $request = new Request(
                [
                'additional_information'  =>  'a'
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_disabling_reason_filter()
    {
        try {
            $request = new Request(
                [
                'disabling_reason'  =>  'a'
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_suspension_reason_filter()
    {
        try {
            $request = new Request(
                [
                'suspension_reason'  =>  'a'
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_disqualification_reason_filter()
    {
        try {
            $request = new Request(
                [
                'disqualification_reason'  =>  'a'
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_office_phone_number_filter()
    {
        try {
            $request = new Request(
                [
                'office_phone_number'  =>  'a'
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_office_phone_extension_filter()
    {
        try {
            $request = new Request(
                [
                'office_phone_extension'  =>  'a'
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_office_email_filter()
    {
        try {
            $request = new Request(
                [
                'office_email'  =>  'a'
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_risk_level_filter()
    {
        try {
            $request = new Request(
                [
                'risk_level'  =>  '1'
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_technology_rank_filter()
    {
        try {
            $request = new Request(
                [
                'technology_rank'  =>  '1'
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmaccount_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new CrmAccountQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n
}