-- Remove Intro heading from rich_text blocks
START TRANSACTION;

UPDATE sections
SET data = JSON_REMOVE(COALESCE(data, JSON_OBJECT()), '$.title')
WHERE type = 'rich_text'
  AND LOWER(JSON_UNQUOTE(JSON_EXTRACT(data, '$.title'))) = 'intro';

COMMIT;
