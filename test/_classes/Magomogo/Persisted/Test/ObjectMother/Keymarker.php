<?php
namespace Magomogo\Persisted\Test\ObjectMother;

use Magomogo\Persisted\Test\Keymarker\Model;
use Magomogo\Persisted\Test\Keymarker\Properties;

class Keymarker
{
    public static function friend()
    {
        return new Model(
            new Properties(array('name' => 'Friend', 'created' => new \DateTime('2012-12-08T10:16+07:00')))
        );
    }

    public static function IT()
    {
        return new Model(
            new Properties(array('name' => 'IT', 'created' => new \DateTime('2012-12-08T10:36+07:00')))
        );
    }

}
