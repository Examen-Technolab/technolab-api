INSERT INTO `descriptions` (`id`, `title`, `list`, `note`, `card_id`, `tab_id`)
VALUES (NULL, :title, :list, :note, :card_id, (SELECT dst.id FROM description_tabs dst WHERE dst.name = :tab));