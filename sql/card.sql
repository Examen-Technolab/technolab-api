SELECT c.id,
c.preview,
c.lastPreview,
p.title,
p.article,
p.price,
p.product,
ds.title,
ds.list,
ds.note,
ds.tab_id
FROM cards c
LEFT JOIN products p on p.id = c.product_id
LEFT JOIN descriptions ds on ds.card_id = c.id
WHERE c.id = :card_id