UPDATE web w
SET w.date = :dateData,
w.title = :title,
w.subtitle = :subtitle,
w.about = :about,
w.link = :link,
w.video = :video
WHERE w.id = :webId;