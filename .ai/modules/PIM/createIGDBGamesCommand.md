# Create IGDBGamesCammand

The flow of the command is:

1. Get an access token from the IGDB API OAuth url.
2. Stop the command when the access token is not received. 
3. Call the games endpoint with maximal 3 parallel requests with the limit of 400 games per request 
4. When all responses return an error http response, get a new access token and try again. After three new access token requests stop the command.
5. The response data should be stored in the var directory with the name of the offset
6. Repeat steps 3 to 5 until the all 355.799 games are fetched form the IGDB API

The command class is Xwero\IgdbGameshop\PIM\Commands\IGDBCommand.
The command has arguments the Twitch id and OAuth secret.

The Oauth class is Xwero\IgdbGameshop\Shared\Data\IGDBOAuth.

The Games endpoint class is Xwero\IgdbGameshop\PIM\Data\IGDBGames.

Create for all the classes tests in Test subdirectories of the respective namespaces. Make sure to test all possible flows. 

