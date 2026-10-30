-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)
--
-- communication_messages has no crm_account_id column - the only link back to a
-- CRM account is crm_accounts.iam_account_id = communication_messages.iam_account_id.
-- Joins directly on iam_account_id (not via communication_threads, unlike
-- crm_user_emails_perspective.sql) because FlowAutomationEmailPusher writes
-- messages without a communication_thread_id - a thread-based join would silently
-- drop every Flow-automation-sent email from this view.

CREATE OR REPLACE VIEW crm_account_communications_perspective AS
SELECT cm.id,
    cm.uuid,
    ca.id AS crm_account_id,
    ca.uuid AS crm_account_uuid,
    cm.direction,
    ch.configuration ->> 'from_address'::text AS from_email_address,
    COALESCE(cm.recipient, iu.email) AS recipient,
    COALESCE(ct.subject, cm.metadata ->> 'subject'::text) AS subject,
    cm.body,
    cm.content_type,
    cm.crm_campaign_id IS NOT NULL AS is_marketing_email,
    cm.status AS message_status,
    cm.deliver_at,
    cm.delivered_at,
    cm.read_at,
    cm.failed_at,
    cm.failure_reason,
    cm.iam_account_id,
    cm.created_at,
    cm.updated_at,
    cm.deleted_at
   FROM crm_accounts ca
     JOIN communication_messages cm ON cm.iam_account_id = ca.iam_account_id AND cm.deleted_at IS NULL
     LEFT JOIN communication_channels ch ON ch.id = cm.communication_channel_id AND ch.deleted_at IS NULL
     LEFT JOIN communication_threads ct ON ct.id = cm.communication_thread_id AND ct.deleted_at IS NULL
     LEFT JOIN iam_users iu ON iu.id = cm.sent_by_user_id
  WHERE ca.deleted_at IS NULL;
