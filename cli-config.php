<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once 'vendor/autoload.php';

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ApcCache());

$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__."/src/App"));
$config->setMetadataDriverImpl($driverImpl);

// set up proxy configuration
$config->setProxyDir('models/Proxies');
$config->setProxyNamespace('Proxies');

$conn = array(
    'driver'    => 'pdo_mysql',
    'host'      => '127.0.0.1',
    'dbname'    => 'ac_todos',
    'user'      => 'root',
    'password'  => '11111111',
    'charset'   => 'utf8',
);

// obtaining the entity manager
$em = EntityManager::create($conn, $config);

$helpers = new \Symfony\Component\Console\Helper\HelperSet(array(
   'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
   'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
));


