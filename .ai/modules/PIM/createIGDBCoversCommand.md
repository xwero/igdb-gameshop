# create IGDBCoversCommand

The flow of the command is:

1. Get all the games_ prefixed files from the temporary file directory with the Xwero\IgdbGameshop\Shared\Data\TempFiles class getGames method.
2. Create a chunked array with 3 files per chunk and loop over the chunked array.
3. For each file in the chunk, get the part of the filename between games_ and .json. 
4. Get the file content, transform the json into an array, and when the cover key value if it is not empty add the id key to an array.
5. Store the filename part and game array in the Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTO and add all three to a Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTOCollection.
6. Add the Xwero\IgdbGameshop\PIM\DTO\GameCoversDTOCollection to the Xwero\IgdbGameshop\PIM\Data\IGDBCovers class fetchMultipleCovers method.
7. When fetchMultipleCovers returns an empty Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTOCollection renew the access token and try a maximum times of 3 before stopping the command.
8. When the Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTOCollection has values add the collection to the Xwero\IgdbGameshop\Shared\Data\TempFiles class multiStoreCovers method.
9. After the chunked array loop is completed show a success message.

For the creation of the Xwero\IgdbGameshop\PIM\Commands\IGDBCoversCommand class follow the example of Xwero\IgdbGameshop\PIM\Commands\IGDBGamesCommand.

Add the IGDBCoversCommand to the bin/console file.

The Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTO has two properties:

- offset: the filename part
- games: the array of game ids

For the Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTOCollection that only contains Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTO objects.
A Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTO object has two properties:

- offset: the Xwero\IgdbGameshop\PIM\DTO\GameCoversRequestDTO offset property
- covers: an array with the following keys: gameId, url, width, height

Xwero\IgdbGameshop\Shared\Data\TempFiles class multiStoreCovers method transforms the Xwero\IgdbGameshop\PIM\DTO\GameCoversResponseDTO objects to csv files where:

- the offset property is the filename part between covers_ and .csv.
- the covers are the csv content.

Create for all the classes tests in Test subdirectories of the respective namespaces. Make sure to test all possible flows.


