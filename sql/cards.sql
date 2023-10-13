SELECT c.id,
c.preview,
c.lastPreview,
p.title,
p.article,
p.price,
p.product
FROM cards c
LEFT JOIN products p on p.id = c.product_id
WHERE c.isHidden = 0