<?php
namespace Test;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Magomogo\Persisted\Container\Db\SchemaCreator;
use Test\JobRecord\Model;
use Test\ObjectMother;

class DbFixture
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    public $db;

    public function __construct()
    {
        $this->db = self::memoryDb();
//        $this->db = self::postgresDb();
    }

    public function install()
    {
        if ($this->db->getDatabasePlatform()->getName() !== 'sqlite') {
            $this->db->exec(
                <<<SQL
DROP TABLE IF EXISTS creditcard, person, company, employee, jobrecord, keymarker, person2keymarker CASCADE
SQL
            );
        }

        self::installSchema($this->db);
    }

//----------------------------------------------------------------------------------------------------------------------

    private static function installSchema(Connection $db)
    {
        $creator = new SchemaCreator($db->getSchemaManager(), new DbNames());
        $creator->schemaFor(ObjectMother\Company::xiag());
        $creator->schemaFor(new JobRecord\Model(ObjectMother\Company::xiag(), ObjectMother\Company::nstu()));
        $creator->schemaFor(ObjectMother\Keymarker::IT());
        $creator->schemaFor(ObjectMother\CreditCard::datatransTesting());

        $taggedEmployee = ObjectMother\Employee::maxim();
        $taggedEmployee->tag(ObjectMother\Keymarker::IT());
        $creator->schemaFor($taggedEmployee);
    }

    /**
     * @return Connection
     */
    private static function memoryDb()
    {
        return DriverManager::getConnection(
            array(
                'memory' => true,
                'user' => '',
                'password' => '',
                'driver' => 'pdo_sqlite',
            ),
            new Configuration
        );
    }

    private static function postgresDb()
    {
        $conn = DriverManager::getConnection(
            array(
                'user' => 'postgres',
                'password' => '',
                'driver' => 'pdo_pgsql',
                'host' => '',
                'port' => '',
                'dbname' => ''
            ),
            new Configuration
        );
        $conn->exec("SET TIME ZONE '+7'");
        return $conn;
    }

}
