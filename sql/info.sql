SELECT 
ds.id,
ds.title,
ds.list,
ds.note,
dst.name as tab
FROM descriptions ds
LEFT JOIN description_tabs dst on dst.id = ds.tab_id
WHERE ds.card_id = :card_id and dst.name = :tab