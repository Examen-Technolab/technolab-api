SELECT ds.title, 
ds.list, 
ds.note, 
ds.tab_id 
FROM descriptions ds 
WHERE ds.card_id = 1 and ds.tab_id=1;