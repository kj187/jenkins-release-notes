<?php

namespace Kj187\JenkinsReleaseNotesBundle\Service;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ObjectTransformer
{
    /**
     * @param array $data
     * @param string $class
     * @return object
     */
    public function transformArrayToObject(array $data, $class)
    {
        $normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());
        $object = $normalizer->denormalize($data, $class);
        return $object;
    }
}
