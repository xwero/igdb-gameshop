# PIM

## Analyse IGDB APi

Via Oauth2 kan er een access token worden gegenereerd dat nodig is op de API endpoints aan te spreken.
De rate limit van de API endpoint is 4 requests per seconde.
De standaard pagina is 10 met een maximum van 500 (volgens AI), maar deze kunnen een time-out hebben.
De basis API endpoint is /v4/games dat een gepagineerde output heeft.
De post data is een soort van sql query.
Er zijn 355.799 games.  

Er zijn verschillende velden zoals cover en artwork, die ids bevatten in plaats van de eigenlijke inhoud. 
Deze velden hebben een eigen API endpoint.

## Uitvoering import

Mij lijkt het best om het aanroepen van de IGDB API te scheiden van het manipuleren van de lokale database voor twee redenen:

- De IGDB API is het meest foutgevoelig (geen/vervallen access token, API timeouts, netwerk problemen)
- De tijd van onzekerheid duurt langer voor er nieuwe poging ondernomen kan worden indien het nodig is. 
- De code gaat simpeler zijn om te controleren

Door het proces in verschillende stappen onder te verdelen is er sneller zekerheid dat een van data-extractie onderdelen uitgevoerd is of niet.
Er kan een simpele AI agent gemaakt worden om het stappenproces op te volgen, zodat er niet iemand moet zijn die het hele proces in de gaten moet houden tot het volledig uitgevoerd is.

De stappen:

- Alle games worden opgehaald met de velden id en naam van het /v4/games endpoint en in json bestanden opgeslagen.
- Alle cover id worden opgehaald aan de hand van de json bestanden en de cover afbeelding urls worden opgehaald met het /v4/covers. Een csv bestand wordt gemaakt om de cover urls te matchen met de game ids.
- De json en csv bestanden worden uitgelezen en de gegevens worden in de lokale database gezet.

Mochten er meer id velden van IGDB nodig zijn kunnen deze het voorbeeld van de covers volgen.

Om wat marge te nemen van met maximale aantal van 500 games en 4 calls per seconde, lijkt me 400 games en een multi-curl van 3 requests een goede manier om de tijd te verkorten.
Aangezien de post data een soor van sql is kan er een offset en limit worden meegegeven. 
De offset kan gebruikt worden om de json bestanden een naam te geven.
Door de csv bestanden dezelfde naam te geven als de json bestanden is de kans op problemen bij het uitlezen van de beide bestanden kleiner.





