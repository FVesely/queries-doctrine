<?php
namespace LibretteTests\Doctrine\Queries;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOSqlite\Driver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Nette;


/**
 * @author David Matejka
 */
trait EntityManagerTest
{

	/**
	 * @return EntityManager
	 */
	protected function createMemoryManager($createSchema = TRUE)
	{
		$conf = [
			'driver' => 'pdo_sqlite',
			'memory' => TRUE,
		];
		$connection = new Connection($conf, new Driver());
		$config = new Configuration();
		$cache = new ArrayCache();
		$config->setMetadataCacheImpl($cache);
		$config->setQueryCacheImpl($cache);
		$config->setProxyDir(TEMP_DIR);
		$config->setProxyNamespace('TestProxy');
		$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver([__DIR__], FALSE));
		$em = EntityManager::create($connection, $config);
		if ($createSchema === FALSE) {
			return $em;
		}
		$schemaTool = new SchemaTool($em);
		$meta = $em->getMetadataFactory()->getAllMetadata();
		$schemaTool->createSchema($meta);

		return $em;

	}

}
