<?php 

namespace Ken\Laravelia\App\Services\Database;

use Doctrine\DBAL\DriverManager;

class Connection
{
	public static function setConnection(...$config)
	{
		return DriverManager::getConnection([
            'dbname' => $config[0]['database'] ?? null,
            'user' => $config[0]['username'],
            'password' => $config[0]['password'],
            'host' => $config[0]['host'],
            'driver' => 'pdo_mysql',
        ], null)->getSchemaManager();
	}
}