# DbUrlParser
A lightweight simple library that parses DbUrl and transforming it DSN:

### Installation
`composer require snoussi/dburl_parser`

### Usage

```php
use Snoussi\DbUrlParser;

$dbUrl = "mysql://dbUser:dbPass@127.0.0.1:3306/dbName?serverVersion=5.7";
$dsn = DbUrlParser::getDsnFromUrl($dbUrl);

echo($dsn); // mysql:host=127.0.0.1;dbname=dbName:3306;

$parts = DbUrlParser::getDatabaseUrlParts($dbUrl); 
// $parts now contains all the properties: $parts["user" | "pass" | "engine" | "version" | "dbName" | "host" | "port" ]
```




