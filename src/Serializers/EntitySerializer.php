<?php
namespace App\Serializers;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EntitySerializer
{
  private static function getSerializer()
  {
    return new Serializer(
      [new ObjectNormalizer()],
      [
        new XmlEncoder(),
        new JsonEncoder()
      ]);
  }

  public static function serialize($entity, $type='json')
  {
      return self::getSerializer()->serialize($entity, $type);
  }
}
