-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_user_emails_perspective AS
SELECT cm.id,
    cm.uuid,
    cu."position",
    cu.job,
    cu.tags AS crm_tags,
    cu.is_suspended,
    iu.id AS iam_user_id,
    iu.fullname,
    iu.email,
    iu.phone_number,
    cm.id AS communication_message_id,
    cm.uuid AS communication_message_uuid,
    ch.configuration ->> 'from_address'::text AS from_email_address,
    ct.subject,
    cm.body,
    cm.content_type,
    cm.crm_campaign_id IS NOT NULL AS is_marketing_email,
    cm.deliver_at,
    cm.delivered_at,
    cm.status AS message_status,
    cm.iam_account_id,
    cm.created_at,
    cm.updated_at,
    cm.deleted_at
   FROM crm_users cu
     JOIN iam_users iu ON cu.iam_user_id = iu.id
     JOIN communication_contact_identifiers cci ON cci.identifier = iu.email AND cci.channel_type = 'email'::text
     JOIN communication_threads ct ON ct.communication_contact_id = cci.communication_contact_id AND ct.deleted_at IS NULL
     JOIN communication_channels ch ON ch.id = ct.communication_channel_id AND ch.deleted_at IS NULL
     JOIN communication_messages cm ON cm.communication_thread_id = ct.id AND cm.deleted_at IS NULL
  WHERE cu.deleted_at IS NULL AND iu.deleted_at IS NULL;
