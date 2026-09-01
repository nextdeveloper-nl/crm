-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_users_perspective AS
SELECT cu.id,
    cu.uuid,
    iu.name,
    iu.surname,
    iu.fullname,
    ia.name AS iam_account_name,
    ia.iam_account_type_id,
    iat.name AS iam_account_type,
    iu.email,
    iu.about,
    iu.pronoun,
    iu.birthday,
    iu.nin,
    iu.common_country_id,
    ( SELECT n_cc.name
           FROM common_countries n_cc
          WHERE n_cc.id = iu.common_country_id) AS country,
    iu.common_language_id,
    ( SELECT n_cl.name
           FROM common_languages n_cl
          WHERE n_cl.id = iu.common_language_id) AS language,
    iu.updated_at AS iam_updated_at,
    iu.phone_number,
    iu.is_registered,
    iu.is_nin_verified,
    iu.is_email_verified,
    iu.is_phone_number_verified,
    iu.is_profile_verified,
    cu."position",
    cu.job,
    cu.job_description,
    cu.hobbies,
    cu.city,
    cu.risk AS email_risk,
    cu.relationship_status,
    cu.is_evangelist,
    cu.is_single,
    cu.education_level AS education,
    cu.child_count,
    iu.id AS iam_user_id,
    iau.iam_account_id,
    ca.id AS crm_account_id,
    cu.tags,
    cu.created_at,
    cu.updated_at
   FROM crm_users cu
     JOIN iam_users iu ON cu.iam_user_id = iu.id
     JOIN iam_account_user iau ON iu.id = iau.iam_user_id
     JOIN iam_accounts ia ON iau.iam_account_id = ia.id
     JOIN iam_account_types iat ON ia.iam_account_type_id = iat.id
     JOIN crm_accounts ca ON ca.iam_account_id = iau.iam_account_id
  WHERE iu.deleted_at IS NULL;
