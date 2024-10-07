UPDATE cards c
LEFT JOIN products p on p.id = c.product_id
SET p.product = :product, 
p.type_id = (SELECT pt.id FROM product_types pt WHERE pt.type = :productType), 
p.title = :title,
p.article = :article, 
p.price = :price,
c.preview = :preview,
c.lastPreview = :lastPreview,
c.isHidden = :isHidden,
c.ordinal = :ordinal
WHERE c.id = :cardId;
