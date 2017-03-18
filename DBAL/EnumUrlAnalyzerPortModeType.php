<?php
namespace OpenActu\UrlBundle\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use OpenActu\UrlBundle\Model\UrlManager;
class EnumUrlAnalyzerPortModeType extends Type
{
    const ENUM_VISIBILITY = 'enumUrlAnalyzerPortMode';
    const ERR_MSG	  = 'invalid port mode';
    
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('".UrlManager::PORT_MODE_NORMAL."', '".UrlManager::PORT_MODE_NONE."','".UrlManager::PORT_MODE_FORCED."')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, array(UrlManager::PORT_MODE_NORMAL, UrlManager::PORT_MODE_NONE, UrlManager::PORT_MODE_FORCED))) {
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
