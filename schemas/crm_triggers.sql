-- PostgreSQL
-- TRIGGERS (introspected live from leo_v4 via pg_trigger/pg_proc; no triggers
-- are defined directly on any crm_* table. These two live on iam_accounts and
-- iam_users and seed crm_accounts/crm_users on creation, mirroring the
-- equivalent pattern used by the Accounting/IAAS/Communication modules.
-- NOTE: this module had no schemas/ folder before this file; the CRM tables
-- themselves are not yet documented here either.)

create or replace function create_crm_account()
    returns trigger
    language plpgsql
as
$function$
BEGIN
    insert into crm_accounts (iam_account_id)
    values (new.id)
    ON CONFLICT DO NOTHING;

    return new;
end;
$function$;

create trigger trigger_create_crm_account
    after insert
    on iam_accounts
    for each row
execute function create_crm_account();

create or replace function create_crm_user()
    returns trigger
    language plpgsql
as
$function$
BEGIN
    insert into crm_users (iam_user_id)
    values (new.id)
    ON CONFLICT DO NOTHING;

    return new;
end;
$function$;

create trigger trigger_create_crm_user
    after insert
    on iam_users
    for each row
execute function create_crm_user();
