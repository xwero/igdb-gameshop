# Shop

De aanname is dat er meer code gaat zijn voor de shop.

## Shopping basket

### Analyse

Een shopping basket is altijd gekoppeld aan een klant.
Een klant kan anoniem zijn of kan aangemeld zijn.
Er is een shopping basket per klant.
Bij het legen van de shopping basket verdwijnt ook de link met de klant.

Een shopping basket heeft een vervaldatum.
Er wordt enkel een shopping basket aangemaakt vanaf het eerste item is toegevoegd.

### Uitvoering

Elke shopping basket heeft een uniek niet sequentieel nummer.

Alle endpoints calls moeten komen van een url in een whitelist.
De endpoints met klant acties moeten een CSRF token hebben als extra beveiliging.
Indien deze beveiligingsdata niet aanwezig is wordt een 403 status terug gegeven door de endpoints


### Shopping basket inhoud endpoint

Indien er geen of onbestaand shopping basket nummer wordt doorgegeven wordt er een 404 status terug gegeven door het endpoint.

Bij een bestaand nummer wordt er een json terug gegeven met daarin
- producten
    - het product
    - het aantal per product
    - de prijs per product
    - de prijs op basis van het aantal
    - korting
- korting totaal
- het BTW totaal
- het totaal van de producten zonder BTW
- het totaal van de producten met BTW

### Shopping basket product toevoegen endpoint

De gegevens in de post data zijn:

- product id
- aantal
- shopping basket nummer (optioneel)

Indien geen shopping basket nummer wordt toegevoegd wordt er gekeken indien de klant is ingelogd.
Als de klant is ingelogd wordt gekeken of er een shopping basket gelinkt is aan de klant. 
Bij een gelinkte shopping basket wordt het item toegevoegd.

Als de klant niet is ingelogd wordt er een nieuwe shopping basket aangemaakt en wordt het item toegevoegd.

Bij het toevoegen wordt er gekeken indien er kortingen zijn en deze worden toegevoegd als extra bij het shopping basket item.

In beide gevallen wordt de shopping basket nummer terug gestuurd.

### Shopping basket product verwijderen

De gegevens in de post data zijn:

- product id
- shopping basket nummer

Indien het product id niet gevonden wordt geeft het endpoint een 404 terug.

Als er nog andere producten in de shopping basket zitten wordt het shopping basket nummer doorgegeven.
Bij een lege shopping basket wordt het shopping basket nummer leeg gelaten.