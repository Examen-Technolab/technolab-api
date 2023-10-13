SELECT 
ds.title,
ds.list,
ds.note
FROM descriptions ds
WHERE ds.card_id = :card_id and ds.tab_id = :tab_id