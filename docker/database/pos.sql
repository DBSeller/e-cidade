-- pos.sql
SELECT public.fc_set_pg_search_path();
GRANT all ON plugins TO plugin;
SELECT fc_grant_revoke('grant', 'plugin', 'select', '%', '%');
VACUUM VERBOSE ANALYZE;