<?php

namespace App\Service\Serializer;

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\SerializerInterface;

class DTOSerializer implements DTOSerializerInterface
{
    private SerializerInterface $serializer;

    public function __construct() {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $extractors = [new PhpDocExtractor(), new ReflectionExtractor()];
        $propertyTypeExtractor = new PropertyInfoExtractor([], $extractors);

        $this->serializer = new Serializer(
            [
                new GetSetMethodNormalizer(
                    classMetadataFactory: $classMetadataFactory,
                    propertyTypeExtractor: $propertyTypeExtractor
                ),
                new ArrayDenormalizer(),
                new ObjectNormalizer(
                    classMetadataFactory: $classMetadataFactory,
                    propertyTypeExtractor: $propertyTypeExtractor
                ),
            ],
            [new JsonEncoder()]
        );
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        return $this->serializer->deserialize($data, $type, $format, $context);

    }
}