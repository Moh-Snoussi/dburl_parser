<?php

namespace Snoussi\DbUrlParser;

/**
 * Contains helper static method that can be used to convert DBUrl to DSN string
 */
class DbUrlParser
{

	/**
	 * Returns the last match of a given $source
	 */
	public static function getLastMatch( string $pattern, string $source ): string
	{
		$matches = [];
		$engine  = preg_match( $pattern, $source, $matches );

		return count( $matches ) > 0 ? $matches[ count( $matches ) - 1 ] : "";
	}

	/**
	 * Given a database url returns its properties: engine, version, host, port, dbName, user, pass
	 *
	 * @param string $databaseUrl mysql://user:123456_abc@127.0.0.1:3306/dbName?serverVersion=5.7
	 *
	 * @return array{engine: string, version: string, host: string, dbName: string, port: string, user: string, pass: string}
	 */
	public static function getDatabaseUrlParts( string $databaseUrl ): array
	{
		$engine  = self::getLastMatch( "/.[^:]+/", $databaseUrl );
		$version = self::getLastMatch( "/[^?]*$/", $databaseUrl );
		$dbName  = self::getLastMatch( "/\/([^\/]*)\?/", $databaseUrl );
		if ( $dbName === "" )
		{
			$dbName = self::getLastMatch( "/\/([^\/]*)$/", $databaseUrl );
		}
		$hostNUser = self::getLastMatch( "/\/\/([^\/]*)/", $databaseUrl );
		$portNHost = self::getLastMatch( "/([^@]*$)/", $hostNUser );
		[ $host, $port ] = strpos( $portNHost, ':' ) === false ? [ $portNHost, "" ] : explode( ":", $portNHost );
		$userNPass = str_replace( $portNHost, "", $hostNUser );
		[ $user, $pass ] = explode( ":", $userNPass );
		$pass = rtrim( $pass, "@" );

		return [ 'engine' => $engine, 'version' => $version, 'host' => $host, 'dbName' => $dbName, 'port' => $port, 'user' => $user,
				 'pass'   => $pass
		];
	}

	/**
	 * Given a database url gets the DSN string
	 *
	 * @param string $databaseUrl mysql://dbUser:123456_abc@127.0.0.1:3306/dbName?serverVersion=5.7
	 *
	 * @return string mysql:host=127.0.0.1:3306;dbname=dbName;
	 */
	public static function getDsnFromUrl( string $databaseUrl ): string
	{
		$parts = self::getDatabaseUrlParts( $databaseUrl );

		return $parts[ 'engine' ] . ':host=' . $parts[ 'host' ] . ';dbname=' . $parts[ 'dbName' ] . ':' . $parts[ 'port' ] . ';';
	}

	/**
	 * Given dbUrlParts that are previously generated from self::getDatabaseUrlParts
	 *
	 * @param array<string, string> $parts{engine: string, version: string, host: string, dbName: string, port: string, user: string, pass: string}
	 *
	 * @return string
	 */
	public static function getDsnFromParts( array $parts ): string
	{
		return $parts[ 'engine' ] . ':host=' . $parts[ 'host' ] . ';dbname=' . $parts[ 'dbName' ] . ':' . $parts[ 'port' ];
	}

}
