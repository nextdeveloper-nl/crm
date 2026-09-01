-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_sales_people_perspective AS
SELECT DISTINCT ON (iu.id) cu.id,
    cu.uuid,
    iu.name,
    iu.surname,
    iu.fullname,
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
    iu.profile_picture_identity,
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
    cu.created_at,
    cu.updated_at
   FROM crm_users cu
     JOIN iam_users iu ON cu.iam_user_id = iu.id
     JOIN iam_account_user iau ON iu.id = iau.iam_user_id
     JOIN iam_role_user iru ON iru.iam_user_id = iu.id
     JOIN iam_roles ir ON ir.id = iru.iam_role_id
  WHERE (ir.name = 'sales-person'::text OR ir.name = 'sales-manager'::text OR ir.name = 'sales-admin'::text) AND iu.deleted_at IS NULL
  ORDER BY iu.id, cu.updated_at DESC;
