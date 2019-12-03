<?php

declare(strict_types=1);

namespace AppBundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Field type mapping for the Doctrine Database Abstraction Layer (DBAL).
 *
 * UUID fields will be stored as a string in the database and converted back to
 * the Uuid value object when querying.
 */
class UuidType extends Type
{
    /**
     * @var string
     */
    public const NAME = 'uuid';

    /**
     * {@inheritdoc}
     *
     * @param array                                     $fieldDeclaration
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     *
     * @param string|null                               $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @param string|null                               $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        return (string) $value;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     *
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
