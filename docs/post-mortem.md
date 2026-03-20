# Post mortem

## Ideeên voor het project

Ik ben het vorige jaar beginnen te veranderen van design patterns en best practices voor enige waarheid aan te nemen naar een meer taal en tool featured gefocuste stijl.
De grootste reden is het feit dat de taal en de tools zoals databases de functionaliteit de grenzen van design patterns beginnen te overstijgen.
Een voorbeeld hiervan is de pipe operator in PHP 8.5. Ik zie het als een betere versie van het Fluent API/Builder patroon omdat de functies niet gebonden zijn aan één class en dat tijdens de flow het datatype kan veranderen.

## De eerste dag

Nadat ik de opdracht had gelezen had ik al snel door dat de features niet alleen geshieden waren door hun MOCOW niveau maar ook door hun functionaliteit.

De reden om de migratie in een PIM module te zetten heeft vooral te maken met het idee dat de informatie van IGDB niet volledig is om een-op-een te gebruiken in de shop. De meest essentieële data die mist zijn de prijzen.

De reden waarom ik het sales endpoint in een CRM module heb gezet is gebaseerd op het feit dat om zoveel mogelijk informatie te hebben acties uit het verleden bewaard moeten worden naast de huidige staat van de klant.

Door de redelijk ruime limiet wou ik het koppelen aan een eigen experiment met AI code generatie.

## De tweede dag

Door omstandigheden heb ik beslist om prioriteit te geven aan mijn ideeën te documenteren zodat er meer is dan enkel de code om een oordeel te vellen.

## De derde dag

Ik heb besloten het AI experiment te laten. Het enige wat ik bewaard heb is AI een prompt te voorzien om het schijven van de code te versnellen.

[createIGDBGamesCommand.md](../.ai/modules/PIM/createIGDBGamesCommand.md) heeft heel wat gaten op het gebied van het aanmaken van classes omdat er nog veel kleine dingen niet duidelijk waren. 
Deze werden snel duidelijk na de generatie van de code.  Een voorbeeld is de [TempFiles](../src/Shared/Data/TempFiles.php) class. Door deze functionaliteit af te scheiden van de command kon ik alles groeperen wat te maken had met de tijdelijke bestanden.

## De vierde dag

Door het werk aan de games command was het veel duidelijker wat de AI prompt moest zijn voor het covers command om zoveel mogelijk code te genereren.

Omdat er zoveel code gegenereerd was de tweede dag heb ik code gemist waardoor ik mijn review proces verstrengd heb. Er is nog steeds het gevoel dat ik dingen gemist heb door met AI te werken.

Ik heb tijd gehad om de igdb data naar database AI informatie te schrijven en genereren. Maar ik had niet meer het vermogen om de code goed te reviewen.

## De vijfde dag

De AI model heeft bij het generen van de igdb data naar database code veel code fouten gemaakt zoals rij per rij toevoegen in de database en testen gewoon niet toegevoegd. 
Dit is de eerst keer dat ik veel code heb moeten schijven. 

## Conclusie

AI is ongelofelijk snel als een typist met code kennis, maar je moet de hand goed vasthouden om het review werk te minimaliseren.
Ik heb geen vertrouwen om code over te slaan omdat AI niet consistent is. 
Een voorbeeld in dit project waren de TempFile class testen. Bij de eerste tests was de variable van het test specifieke customDir en bij de tweede tests was het tempDir.
De betere oplossing is de `beforeEach` en `afterEach` functies te gebruiken.

Hoewel ik nooit zal kunnen tippen aan de snelheid van code genereren moet er veel aangepast en verwijderd worden. Ik heb het gevoel dat na aanpassingen het een 50/50 splitsing is.

Ik leer ook zelf bij door code te generen. Zo had ik nooit gedacht aan `stream_context_create` om een http request te doen.



