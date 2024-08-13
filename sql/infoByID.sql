SELECT 
ds.id,
ds.title,
ds.list,
ds.note
FROM descriptions ds
WHERE ds.id = :id