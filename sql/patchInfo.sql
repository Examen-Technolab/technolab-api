UPDATE descriptions ds
SET ds.card_id=:card_id,
ds.tab_id=(SELECT dst.id FROM description_tabs dst WHERE dst.name = :tab),
ds.title=:title,
ds.list=:list,
ds.note=:note
WHERE ds.id=:id;
