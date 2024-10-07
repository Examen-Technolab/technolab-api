UPDATE events ev
SET ev.code = :code, 
ev.logo = :logo, 
ev.title = :title,
ev.date = :dateString, 
ev.lastDay = :lastDay,
ev.btnText = :btnText,
ev.btnLink = :btnLink,
ev.withPage = :withPage,
ev.isLinkResult = :isLinkResult,
ev.about = :about,
ev.links = :links,
ev.description = :description
WHERE ev.id = :eventId;