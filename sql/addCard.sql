INSERT INTO `products` (`id`, `product`, `type_id`, `title`, `article`, `price`)
VALUES (NULL, :product, 
    (SELECT pt.id FROM product_types pt WHERE pt.type = :productType),
    :title, :article, :price);

INSERT INTO `cards` (`id`, `product_id`, `preview`, `lastPreview`, `isHidden`, `ordinal`) 
VALUES (NULL, 
    (SELECT p.id FROM products p WHERE p.product = :product),
    :preview, :lastPreview, :isHidden, 1000);