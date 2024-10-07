SELECT c.id,
c.preview,
c.lastPreview,
c.isHidden,
c.ordinal,
p.title,
p.article,
p.price,
p.product,
pt.type
FROM cards c
LEFT JOIN products p on p.id = c.product_id
LEFT JOIN product_types pt on pt.id = p.type_id
WHERE p.product = :product