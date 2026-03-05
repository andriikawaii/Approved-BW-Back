-- Rich text presentation metadata backfill
-- Run manually on builtwell_backend

START TRANSACTION;

UPDATE sections
SET data = JSON_SET(
    COALESCE(data, JSON_OBJECT()),
    '$.style_variant', IFNULL(JSON_UNQUOTE(JSON_EXTRACT(data, '$.style_variant')), 'default'),
    '$.surface', IFNULL(JSON_UNQUOTE(JSON_EXTRACT(data, '$.surface')), 'default'),
    '$.container_width', IFNULL(JSON_UNQUOTE(JSON_EXTRACT(data, '$.container_width')), 'default'),
    '$.spacing', IFNULL(JSON_UNQUOTE(JSON_EXTRACT(data, '$.spacing')), 'normal')
)
WHERE type = 'rich_text';

-- Optional targeted presets by page pattern
UPDATE sections s
INNER JOIN pages p ON p.id = s.page_id
SET s.data = JSON_SET(COALESCE(s.data, JSON_OBJECT()), '$.style_variant', 'legal', '$.container_width', 'narrow')
WHERE s.type = 'rich_text'
  AND p.full_path IN ('/privacy-policy', '/terms-of-service');

UPDATE sections s
INNER JOIN pages p ON p.id = s.page_id
SET s.data = JSON_SET(COALESCE(s.data, JSON_OBJECT()), '$.style_variant', 'links')
WHERE s.type = 'rich_text'
  AND p.full_path = '/sitemap';

UPDATE sections
SET data = JSON_SET(COALESCE(data, JSON_OBJECT()), '$.style_variant', 'process')
WHERE type = 'rich_text'
  AND (
    LOWER(JSON_UNQUOTE(JSON_EXTRACT(data, '$.title'))) LIKE '%process%'
    OR LOWER(JSON_UNQUOTE(JSON_EXTRACT(data, '$.content'))) LIKE '%step 1:%'
  );

COMMIT;
