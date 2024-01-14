DELETE p, c
FROM cards c
LEFT JOIN products p on p.id = c.product_id
WHERE c.id = :id;