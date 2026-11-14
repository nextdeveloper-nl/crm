# CRM multi-tenancy: stop iam_account duplication and cross-tenant visibility

## Context
CRM became a multi-tenant service. Two problems:
1. Every iam_account gets a crm_account (DB trigger `trigger_create_crm_account`, `CRM/schemas/crm_triggers.sql`), so `crm_accounts_perspective` lists every iam_account. Several CRM paths also create new iam_accounts / claim domains with weak dedupe, so one company appears several times.
2. Tenants see each other's CRM records. No tenant key exists. `crm_accounts.iam_account_id` is the *customer*; ownership only exists via `crm_account_managers`. `SalesAdminRole` (and `MarketingManagerRole`) have no tenant filter, many CRM tables have no tenant column, and importers run under shared accounts.

Decisions (from user): add a new owner column on `crm_accounts`; KEEP the trigger, but trigger-created rows are owned by `iam_accounts.id = 5` (platform tenant). Services-created rows are owned by the acting tenant.

Paths: CRM = `/home/cevatkelle/Documents/Applications/NextDeveloper/CRM`, IAM = `.../NextDeveloper/IAM`, API = main project.

## Branching (user request)
Major update: do all work on a dedicated feature branch, not on dev/master. Proposed name `feat/crm-multi-tenancy`, created in each repo touched (CRM vendor repo, main project, IAM if edited). Save this plan into the CRM module first at `CRM/docs/features/multi-tenancy-plan.md` as the first commit on that branch. Merge via PR per each repo's release workflow in memory (main project: feature PR into dev, then dev->master). Land in the phase order below, one PR per phase if possible so Phase 4-A security fixes can ship early.

### Repo state found at start (2026-11-14)
- CRM vendor repo: on `master`, UNRESOLVED merge conflicts (`UU`) in `src/Actions/Quotes/Approve.php` and `ConvertQuoteToInvoice.php` (leftover stash/merge). Branch `feat/crm-multi-tenancy` must be cut from clean `origin/master`; use a separate git worktree (or plumbing) so the conflicted working tree is not touched, and resolve conflicts in the feature branch deliberately.
- Main project: on `fix/neutralize-restored-user-identity` with uncommitted edits to 4 pusher drivers + 1 untracked test (other work). Create `feat/crm-multi-tenancy` from `github/dev` in a worktree; stage explicit paths only; do not touch those files except `EventCrmOpportunityPusher.php`, which needs care (merge with the existing uncommitted edit, ask first).
- Remotes: main project push to `github`; CRM has only `origin` (nextdeveloper-nl/crm).

## Step 1 (first implementation step)
Worktrees + branches, commit plan doc to `CRM/docs/features/multi-tenancy-plan.md`, then Phase 4-A items 1-5 (unscoped write endpoints) with tests.

## Constraints (CLAUDE.md)
No migrations, no direct SQL by me: schema changes delivered as DDL files in `CRM/schemas/` for the user to apply. Columns `text`/`timestamptz`; logic in Services, not controllers; no iam_account_id/iam_user_id filters on perspectives that bypass AuthorizationScope; comment all changes; vendor repos committed/pushed separately (CRM release workflow per memory), stage explicit paths.

## Phase 0 - Schema (DDL for user to apply)
- `crm_accounts`: add `owner_iam_account_id bigint` (FK `iam_accounts.id`), default via trigger = 5.
- Update `trigger_create_crm_account` to insert `(iam_account_id, owner_iam_account_id) values (new.id, 5)`.
- Add `owner_iam_account_id`-style tenant key to tables lacking account columns: `crm_campaign_targets`, `crm_target_users` (derive via parent campaign/target) - verify per table before deciding; global reference tables (`crm_industries`, `crm_offerings`, ...) stay shared.
- Rebuild `crm_accounts_perspective` to expose `owner_iam_account_id`.
- Backfill script (review only): existing crm_accounts -> owner 5, then reassign owner for customers that are managed by another tenant via `crm_account_managers`.
- Dedupe report query (read-only) of iam_accounts sharing `common_domain_id` / normalised name; merge is a separate manual step.

## Phase 1 - Central tenant boundary
- New `CRM/src/Database/Scopes/TenantScope.php`: for `crm_accounts` and `crm_accounts_perspective` adds `owner_iam_account_id = UserHelper::currentAccount()->id`; for other crm tables with `iam_account_id` (owner semantics: opportunities, quotes, campaigns, targets, calls, importers, user_managers) adds `iam_account_id = currentAccount`; child tables scoped through parent. Register in `CRM/config/crm.php` global scopes (lines 4-10) next to AuthorizationScope. Independent of which role wins (AuthorizationScope stops at first role).
- Keep Elasticsearch path equivalent (`ElasticAuthorizationScopeResolver`) if CRM indexes are used.
- Fix role leaks as defence in depth: `SalesAdminRole::apply` (:31-45) and `MarketingManagerRole` add tenant predicate; SalesManager/SalesPerson/SDR/BDR add `iam_account_id = current` to the user-id fallback.
- Platform tenant (id 5) staff keep a documented, explicit bypass (config `crm.platform_tenant_id`).
- Audit `withoutGlobalScope(s)` callers and add explicit tenant checks: `CrmHelper` (getCrmAccountById, getCrmAccount, getAccountManagers, getCustomerByCrmAccountId), `ImportersService:83`, `QuotesService:54,62,75`, `LeadImporterService`, API `CRM/AccountsService::getMyCustomers`.
- Hide trigger-created, ownerless-by-tenant rows: tenants never see rows owned by 5 unless they are tenant 5.

## Phase 2 - Stop iam_account duplication
One shared find-or-create in a service (e.g. `CRM/src/Services/CustomerAccountResolverService.php`, reusing IAM `AccountsService`, `createOwnerless`): lookup order = existing domain (`common_domains.name` -> `iam_accounts.common_domain_id`) then normalised name+type, then create. Use from:
- `LeadImporterService` (:338-421): resolve by email domain / existing user; set `crm_accounts.owner_iam_account_id = $importer->iam_account_id`; stop creating personal accounts for phone-only placeholder users where an account can be reused.
- API `app/Services/CRM/AccountsService::store` (:128-190): pre-check via resolver; remove double `Events::fire('created:...IAM\Accounts')` (:164); stop adding the acting user as member of customer accounts (use `createOwnerless` path); set owner.
- `GetLeadsSheetImporter` (:102+, :40-42): dedupe by domain, never reassign `Domains.iam_account_id` of another account.
- `ContactImportController` (:294-316): look up existing account by `account_name`/domain before `createAccount`.
- IAM `AccountsService::update` (~:115): fix `updateQuietly(['iam_account_id', $account->id])` bug (list, not key=>value).
- `crm_account_managers` auto-row in `OpportunitiesService::create` (:110-131): stop it granting cross-tenant access (only same-tenant).
- `LeadNurtureOpportunityService:112-121`: set owner on created crm_account; avoid single shared pipeline account leaking customers.
- `SyncSameDomainUsersJob` (IAM): do not attach users across tenants' customer accounts.
- Model/Service: add `owner_iam_account_id` to `CRM/src/Database/Models/Accounts.php`, AbstractTransformer, create/update requests, `AccountsService::create` stamping from `UserHelper::currentAccount()->id` (system paths pass tenant explicitly).

## Phase 3 - iam_accounts visibility
- `IAM AccountsService::get` (:48-54) already limits to owned accounts; fix leakage via membership (stop creating `iam_account_user` rows for tenant users on customer accounts - Phase 2) and review `IamSuccessManagerRole` membership-based listing.
- Tenants reach customer data through `crm_accounts_perspective`, not `iam_accounts`.

## Verification
- Unit/feature tests (Pest) in CRM: two tenants A,B; A's sales-admin/manager/person list crm_accounts, opportunities, quotes, campaigns, perspectives -> never B's rows; platform tenant 5 sees its own; trigger-created rows have owner 5.
- Resolver tests: same domain twice -> one iam_account; domain collision never reassigns another account's domain.
- Run importer (Facebook/lead intake) in a sandbox for tenant A twice with same company -> one iam_account, owner A.
- Review DDL + backfill + dedupe report on a staging DB before prod; then `graphify update .`.
- Release: CRM per `project_commons`-style vendor workflow in memory (verify CRM's), main project feature PR into dev then dev->master.

## Phase 4 - Additional isolation gaps (second audit, 2026-11-14)

### A. Exploitable now (fix first, independent of schema)
1. `app/Services/CRM/MassMailingService.php:107-146`: Targets/TargetUsers/EmailTemplates/Channels fetched `withoutGlobalScope`, no ownership check (route `routes/api.php:813`). Also forces platform from-address (:68, :173).
2. `app/Http/Controllers/Marketing/CampaignEmailQueueController.php`: `linkTargets` (:419-427), `resolveBody`/`templateWarnings` (:333, :361), `resolveRecipients` (:500-523) take foreign target/template UUIDs unscoped.
3. `app/Http/Controllers/CRM/ContactImportController.php:74,168,227,243,236`: Targets fetched unscoped; anyone can add/remove contacts on another tenant's list.
4. `CRM/src/Services/CampaignTargetsService.php:25-31`: links target to campaign with scopes off.
5. `app/Actions/CRM/Campaigns/CustomizeCampaignMailContent.php:95-148`: `withoutGlobalScopes()` on templates, targets, users, accounts.
6. `app/Database/Models/LeoActiveCustomersPerspective.php` (empty `registerScopes`) + `ActiveCustomersService::get()`, route `/leo/crm/active-customers`: every customer's balance/credit exposed. `LeoAccountsPerspective::currentAccount()` (:128) strips scope; merged into `app/Services/CRM/AccountsService.php:38`.
7. `app/Services/CRM/MetricService.php:139-144`: platform-wide growth/paying-customer views with scopes removed.
8. `PartnersStatisticsService` cache key `DistributorStats:<partner uuid>` with no viewer check (:19). Copy `CampaignAnalyticsService` pattern (explicit `currentAccount()` + `authorize()`).
9. Role gaps: MarketingManager unscoped on campaigns/targets/campaign_targets/target_users (`MarketingManagerRole.php:38-44`) and `crm_accounts`; SalesDev/BDR read `crm_sales_people_perspective` unscoped (`SalesDevelopmentRepresentative.php:48-50`); notes/calls/meetings/tasks scoped by `iam_user_id` only (multi-tenant users see all); dead branch `property_exists('iam_account_id', $model)` args reversed (`SalesManagerRole.php:217`, `MarketingManagerRole.php:141`); SalesManager `crm_users` UserManagers check lacks account filter (:205-214); SalesAdmin can mutate shared `crm_industries`/`crm_offerings`.
10. Views with no `iam_user_id` column skip the fallback filter entirely: `crm_opportunities_performance`, `crm_account_communications_perspective`, `crm_campaign_target_users_perspective`, `crm_account_managers_performance`, `crm_user_emails_perspective` (joins by email across tenants), `crm_sales_people_perspective` (exposes nin/birthday).
11. `crm_account_managers.iam_account_id` nullable and `WatchAccount` writes the customer's own account -> make NOT NULL, fix semantics.

### B. Tenant-aware import/automation
- `LeadImporterService` runs as admin; `OpportunitiesService::create` (:77-141, :190-227) picks responsible user and notifies from the admin's (platform) account -> tenant leads land on platform staff. `preAssigned` accepts any user UUID.
- Pipelines/stages resolved by UUID unscoped (`LeadImporterService.php:265-276, 306-310`); `ImportersService::update` has no pipeline accessibility check.
- IAM user / crm_account matching global (`:306-310, :351-357, :410-412`); placeholder emails `lead-<phone>@leads.invalid` shared across tenants.
- `app/Pushers/Drivers/EventCrmOpportunityPusher.php`: campaign/stage ids from provider_metadata unscoped.
- `app/Jobs/IAM/Accounts/NewAccountCreated.php` -> `LeadNurtureOpportunityService`: every new iam_account (including tenant-created customers) becomes a platform lead on hard-coded pipeline `73df5d6b-...`.
- `CRM/config/crm.php:22-40` static Facebook Page id + platform pipeline applied to whichever channel connects that Page (`FacebookLeadImportService.php:116-160`); webhook resolves tenant by pageId, first match wins.
- `CampaignsService::findByFacebookAdId` (:114-125) maps global tags, no account filter.
- `CrmAccountDisabled` handler (cancels invoices, deactivates IAM account, cuts IaaS limits) triggered by any manager via DisableAccount/SuspendAccount -> tenants must not disable platform customers' real service; platform-only.
- `QuotesService::convertToInvoice` (:54,62,75,186) unscoped, notifies `accounting-admin` platform-wide (:123-128).
- Scheduled commands (`app/Console/Kernel.php:34-37,165`): `crm:generate-sales-campaign-opportunities` looks up crm_account by customer id alone (`GenerateSalesCampaignOpportunities.php:114`); `FixCampaignFlowLinksCommand` iterates all pipelines.

### C. Collisions / schema
- `crm_accounts`: no unique on `iam_account_id`; trigger `ON CONFLICT DO NOTHING` is a no-op. Need `UNIQUE (iam_account_id, owner_iam_account_id)`; every `where iam_account_id ... first()` (LeadImporterService:410, GenerateSalesCampaignOpportunities:114, CrmHelper:225-236, LeadNurtureOpportunityService, EventCrmOpportunityPusher) must include owner. Views joining `crm_accounts ON iam_account_id` (accounts_perspective, users_perspective, account_communications_perspective, daily_new_accounts_performance, Leo perspectives) fan out -> rebuild.
- `crm_users`: no unique on `iam_user_id`; `CrmHelper::getCrmUserOfIamUser` (:344) uses IAM id as CRM id (latent bug). Contacts are tenant data: needs per-tenant link.
- `crm_target_users`: no PK/uuid/deleted_at/unique `(crm_target_id, crm_user_id)`.
- Shared catalogs (`crm_industries/offerings/technologies/sector_focus/regulatory_compliance`): writes platform-only. Platform-only aggregates (daily/weekly/monthly new accounts, paying customers, leo_active_customers, stats_performances): restrict to platform admin or rebuild per tenant. No DDL in repo for several Leo perspectives -> add.
- Account state flags (`is_suspended`, `is_disabled`, `is_service_enabled`, `risk_level`, `technology_rank`) stay platform-level on the customer, not per tenant.

### D. Secrets
- `crm_importers.credentials_enc` not actually encrypted (request accepts any string, cast `string`) -> encrypt or drop.
- `crm_projects.token` plaintext, output by `AbstractProjectsTransformer.php:69`; project `url` host unrestricted (SSRF) -> encrypt, validate host.
- `ImportersService::resolveLinkedObject` (:172-194): integer `object_id` skips scope check -> enforce for ints. `updateSystemState` (:79-103) needs tenant check.
- Lead intake tokens OK (hash only); add per-tenant revoke/IP allow-list; audit `crm:lead-intake-token`.

### E. Misc
- `CRM/src/Actions/Quotes/ConvertQuoteToInvoice.php` has git conflict markers (lines 5-12, 61-77) - file does not parse; fix before testing quotes.
- Elasticsearch: no CRM model synced, no role implements `RoleToElasticFilterInterface`; required before any CRM indexing.
- Transformer caches safe while payload not viewer-specific.
- `SalePersonAccountService.php:162-221` writes roles/domains with scopes off for any account; re-check once tenants exist.

Implementation order: (1) A.1-A.5 unscoped writes, (2) A.6-A.8 Leo/metrics/stats, (3) owner semantics + trigger + unique indexes + views, (4) importer/nurture/Facebook tenant-awareness, (5) role gaps, (6) secrets, (7) fix ConvertQuoteToInvoice conflict markers.

## Open items for implementation start
- Confirm iam_account 5 is the platform account in prod.
- Whether child tables lacking tenant columns (`crm_campaign_targets`, `crm_target_users`) get columns or are scoped via parent joins.
