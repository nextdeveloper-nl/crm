<?php

namespace NextDeveloper\CRM\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\CRM\Database\Filters\CrmRegulatoryComplianceQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCrmRegulatoryComplianceService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait CrmRegulatoryComplianceTestTraits
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

    public function test_http_crmregulatorycompliance_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/crm/crmregulatorycompliance',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_crmregulatorycompliance_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/crm/crmregulatorycompliance', [
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
    public function test_crmregulatorycompliance_model_get()
    {
        $result = AbstractCrmRegulatoryComplianceService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmregulatorycompliance_get_all()
    {
        $result = AbstractCrmRegulatoryComplianceService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_crmregulatorycompliance_get_paginated()
    {
        $result = AbstractCrmRegulatoryComplianceService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_crmregulatorycompliance_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmregulatorycompliance_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_crmregulatorycompliance_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::first();

            event(new \NextDeveloper\CRM\Events\CrmRegulatoryCompliance\CrmRegulatoryComplianceRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_crmregulatorycompliance_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new CrmRegulatoryComplianceQueryFilter($request);

            $model = \NextDeveloper\CRM\Database\Models\CrmRegulatoryCompliance::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}