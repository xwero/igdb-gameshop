# CRM

Bij de data collectie focus ik mij op de PIM en Shop module aanpassingen.

## Analyse

Tijdelijke gegevens zoals vervallen shopping baskets of verwijderde items worden opgeslagen in CRM tabellen.
De huidige gegevens kunnen uit de shop tabellen.

## Uitvoering

### Tijdelijke gegevens collectie

Bij het opkuisen van de vervallen shopping baskets en het verwijderen van een shopping basket item wordt een event gestuurd met daarin de klant id, het shopping basket item en de actie.
Als er geen klant id is dan wordt het event genegeerd door de handler die de gegevens in de CRM tabel zet.

De handler voegt de klant id, het shopping basket item en de actie data toe aan de CRM shopping basket tabel.

### Basket data collectie endpoint

Ik ga ervan uit dat het endpoint dient om een externe oplossing te voorzien.

Via OAuth moet en een access token worden opgevraagd dat een vervaldatum heeft.

De post data bevat optionele filters:

- één of meerdere categorieën: current, deleted, expired
- een begindatum
- een einddatum
- pagina nummer

Als de categorieên ontbreken dan worden ze allemaal getoond.
Bij het ontbreken van de datums worden alle items opgehaald
Bij het ontbreken van de begindatum of einddatum is er geen afbakening in tijd voor de items.  
Bij het ontbreken van het pagina nummer wordt pagina 1 opgehaald.

Het endpoint geeft een json terug met

-items
    - category
    - datum (voor current items de datum van de API request)
    - de klantgegevens
    - de productgegevens
    - de kortingsgegevens
- paging
  - current
  - total

De items per pagina worden bepaald door capaciteit van de server.



