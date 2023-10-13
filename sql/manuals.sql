SELECT m.title,
m.subtitle,
m.text,
m.link,
m.img,
pt.type
FROM manuals m 
left join product_types pt on pt.id = m.type_id