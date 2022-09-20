<?php


use PHPUnit\Framework\TestCase;
use Snoussi\DbUrlParser\DbUrlParser;

class DbUrlParserTest extends TestCase
{

	public function testGetDsnFromParts(): void
	{
		$dbUrl = "mysql://dbUser:123456_abc@127.0.0.1:3306/dbName?serverVersion=5.7";
		$parts = DbUrlParser::getDatabaseUrlParts($dbUrl);

		$actual = DbUrlParser::getDsnFromParts($parts);
		self::assertSame("mysql:host=127.0.0.1;dbname=dbName:3306", $actual);
	}

	public function testGetDatabaseUrlParts(): void
	{
		$dbUrl = "mysql://dbUser:123456_abc@127.0.0.1:3306/dbName?serverVersion=5.7";

		$parts = DbUrlParser::getDatabaseUrlParts( $dbUrl );

		self::assertSame( $parts[ "engine" ], "mysql" );
		self::assertSame( $parts[ "user" ], "dbUser" );
		self::assertSame( $parts[ "pass" ], "123456_abc" );
		self::assertSame( $parts[ "host" ], "127.0.0.1" );
		self::assertSame( $parts[ "port" ], "3306" );
		self::assertSame( $parts[ "dbName" ], "dbName" );
		self::assertSame( $parts[ "version" ], "serverVersion=5.7" );
	}

	public function testGetDatabaseUrlPartsEmptyPass(): void
	{
		$dbUrl = "mysql://dbUser:@127.0.0.1:3306/dbName?serverVersion=5.7";

		$parts = DbUrlParser::getDatabaseUrlParts( $dbUrl );

		self::assertSame( $parts[ "engine" ], "mysql" );
		self::assertSame( $parts[ "user" ], "dbUser" );
		self::assertSame( $parts[ "pass" ], "" );
		self::assertSame( $parts[ "host" ], "127.0.0.1" );
		self::assertSame( $parts[ "port" ], "3306" );
		self::assertSame( $parts[ "dbName" ], "dbName" );
		self::assertSame( $parts[ "version" ], "serverVersion=5.7" );
	}

	public function testGetDatabaseUrlPartsEmptyVersion(): void
	{
		$dbUrl = "mysql://dbUser:@127.0.0.1:3306/dbName?";

		$parts = DbUrlParser::getDatabaseUrlParts( $dbUrl );

		self::assertSame( $parts[ "engine" ], "mysql" );
		self::assertSame( $parts[ "user" ], "dbUser" );
		self::assertSame( $parts[ "pass" ], "" );
		self::assertSame( $parts[ "host" ], "127.0.0.1" );
		self::assertSame( $parts[ "port" ], "3306" );
		self::assertSame( $parts[ "dbName" ], "dbName" );
		self::assertSame( $parts[ "version" ], "" );
	}

	public function testGetDatabaseUrlPartsEmptyPort(): void
	{
		$dbUrl = "mysql://dbUser:@127.0.0.1/dbName?";

		$parts = DbUrlParser::getDatabaseUrlParts( $dbUrl );

		self::assertSame( $parts[ "engine" ], "mysql" );
		self::assertSame( $parts[ "user" ], "dbUser" );
		self::assertSame( $parts[ "pass" ], "" );
		self::assertSame( $parts[ "host" ], "127.0.0.1" );
		self::assertSame( $parts[ "port" ], "" );
		self::assertSame( $parts[ "dbName" ], "dbName" );
		self::assertSame( $parts[ "version" ], "" );
	}

	public function testGetDsnFromUrl(): void
	{
		$dbUrl  = "mysql://dbUser:@127.0.0.1:3306/dbName?serverVersion=5.7";
		$actual = DbUrlParser::getDsnFromUrl( $dbUrl );
		self::assertSame( "mysql:host=127.0.0.1:3306;dbname=dbName;", $actual );
	}
}
