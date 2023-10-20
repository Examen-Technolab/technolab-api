SELECT a.type,
a.title,
a.text,
ab.type as children_type,
ab.title as children_title,
ab.text as children_text
FROM about a
LEFT JOIN about ab on ab.id = a.children_id
WHERE a.isChildren = 0