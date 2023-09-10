<?php

/**
 * Add `timestamp` type to DBAL
 * Unused code for reference / backward compatibility
 */

namespace App\Utils\DatabaseUtils;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;

// Add these lines to any of your service providers
// if (!Type::hasType('timestamp')) {
//     Type::addType('timestamp', TimestampType::class);
// }

class TimestampType extends Type
{
    public function getName()
    {
        return 'timestamp';
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @throws \Doctrine\DBAL\DBALException
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $name = $platform->getName();

        if (in_array($name, ['mysql', 'sqlite'])) {
            $method = 'get'.ucfirst($name).'PlatformSQLDeclaration';

            return $this->$method($fieldDeclaration);
        }

        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Gets the SQL declaration snippet for a field of this type for the MySQL Platform.
     *
     * @param array $fieldDeclaration The field declaration.
     *
     * @return string
     */
    protected function getMysqlPlatformSQLDeclaration(array $fieldDeclaration)
    {
        $columnType = $fieldDeclaration['precision'] ? "TIMESTAMP({$fieldDeclaration['precision']})" : 'TIMESTAMP';

        if (isset($fieldDeclaration['notnull']) && $fieldDeclaration['notnull'] == true) {
            return $columnType;
        }

        return "$columnType NULL";
    }

    /**
     * Gets the SQL declaration snippet for a field of this type for the Sqlite Platform.
     *
     * @param array $fieldDeclaration The field declaration.
     *
     * @return string
     */
    protected function getSqlitePlatformSQLDeclaration(array $fieldDeclaration)
    {
        return $this->getMysqlPlatformSQLDeclaration($fieldDeclaration);
    }
}
