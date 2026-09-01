-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_target_users_perspective AS
SELECT iu.id,
    iu.uuid,
    iu.pronoun,
    iu.name,
    iu.surname,
    iu.fullname,
    iu.birthday,
    iu.email,
    iu.phone_number,
    iu.common_country_id,
    iu.common_language_id,
    iu.tags AS user_tags,
    iu.about,
    cu.id AS crm_user_id,
    cu."position",
    cu.job,
    cu.job_description,
    cu.hobbies,
    cu.city,
    cu.relationship_status,
    cu.is_evangelist,
    cu.is_single,
    cu.education_level,
    cu.child_count,
    cu.tags AS crm_tags,
    cu.is_suspended,
    ct.id AS crm_target_id,
    ct.name AS target_name,
    ct.description AS target_description,
    ct.iam_account_id,
    ct.iam_user_id,
    ctu.created_at,
    ctu.updated_at
   FROM crm_target_users ctu
     JOIN crm_users cu ON ctu.crm_user_id = cu.id AND cu.deleted_at IS NULL
     JOIN iam_users iu ON cu.iam_user_id = iu.id
     JOIN crm_targets ct ON ctu.crm_target_id = ct.id
  WHERE ct.deleted_at IS NULL AND iu.deleted_at IS NULL;
