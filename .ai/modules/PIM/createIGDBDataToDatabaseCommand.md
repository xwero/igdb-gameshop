# Create IGDBDataToDatabaseCommand

The flow of the command:

1. Use the Xwero\IgdbGameshop\Shared\Data\TempFiles class getGamesAndCovers method to group the related games and game cover files.
2. Use the getGamesAndCovers method output as an argument for the Xwero\IgdbGameshop\PIM\Data\Migration\IGDBDataToDatabase function.
3. Show the IGDBDataToDatabase function return to stop the function. 

The command is the Xwero\IgdbGameshop\PIM\Commands\IGDBDataToDatabaseCommand class.

The Xwero\IgdbGameshop\Shared\Data\TempFiles class getGamesAndCovers method instantiates a Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTOCollection.
The Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTOCollection contains Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTO objects.
A Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTO is created by matching a covers_*.csv files with a games_*.json, where * is identical.
For Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTOCollection and Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTO look at examples in the src/PIM/DTO directory.

The Xwero\IgdbGameshop\PIM\Data\Migration\IGDBDataToDatabase function flow is:

1. Loop over the Xwero\IgdbGameshop\PIM\DTO\GamesAndCoversFileDTOCollection.
2. The GamesAndCoversFileDTO games property is per game matched with the database fields in src/PIM/Data/Migration/pim_products.sql and added to the Xwero\IgdbGameshop\PIM\Data\Products class multiInsert method.
The multiInsert method returns an array with the database id and igdb_fields.
3. The GamesAndCoversFileDTO covers property is per line matched with the multiInsert method array products id and the other fields that exist in src/PIM/Data/Migration/pim_product_covers.sql. The resulting array is added to the Xwero\IgdbGameshop\PIM\Data\ProductCovers class multiInsert method

The covers_*.csv file has the columns game id, url, width, height.

The Xwero\IgdbGameshop\PIM\Data\Products and Xwero\IgdbGameshop\PIM\Data\ProductCovers have the parent class Xwero\IgdbGameshop\PIM\Data\Table.
The Xwero\IgdbGameshop\PIM\Data\Table gets the database connection from the DATABASE_DSN environment variable.

Create for all the classes tests in Test subdirectories of the respective namespaces. Make sure to test all possible flows.


