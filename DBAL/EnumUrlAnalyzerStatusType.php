<?php
namespace OpenActu\UrlBundle\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumUrlAnalyzerStatusType extends Type
{
    const ENUM_VISIBILITY = 'enumUrlAnalyzerStatus';
    const STATUS_NONE	  = 'none';
    const STATUS_SENT	  = 'sent';
    const STATUS_SANITIZED= 'sanitized';
    const ERR_MSG	  = 'invalid status';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('".self::STATUS_NONE."', '".self::STATUS_SENT."','".self::STATUS_SANITIZED."')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, array(self::STATUS_NONE, self::STATUS_SENT, self::STATUS_SANITIZED))) {
            throw new \InvalidArgumentException(self::ERR_MSG);
        }
        return $value;
    }

    public function getName()
    {
        return self::ENUM_VISIBILITY;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
