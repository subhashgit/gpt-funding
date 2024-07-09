<?php

namespace App\Serializer\Normalizer;

use App\Entity\Grant;
use App\Entity\GrantCategory;
use App\Entity\GrantLocation;
use App\Entity\GrantOpenTo;
use App\Service\EmbeddingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class GrantNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(private ObjectNormalizer $normalizer,
        private readonly EmbeddingService $embeddingService,
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @param Grant $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        if (!$object->getEmbedding()) {
            $this->em->refresh($object);
        }

        return [
            'id' => $object->getId(),
            'title' => $object->getTitle(),
            'description' => $object->getDescription(),
            'status' => $object->getStatus(),
            'grantLocations' => $object
                ->getLocations()
                ->map(fn (GrantLocation $cpv) => $cpv->getLocation())
                ->toArray(),
            'grantCategories' => $object
                ->getCategories()
                ->map(fn (GrantCategory $cpv) => $cpv->getCategory())
                ->toArray(),
            'grantOpenTo' => $object
                ->getOpenTo()
                ?->map(fn (GrantOpenTo $cpv) => $cpv->getOpenTo())
                ->toArray(),
            'published_date' => $object->getPublishedDate()?->getTimestamp(),
            'closing_date' => $object->getClosingDate()?->getTimestamp(),
            '_vectors' => json_decode($object->getEmbedding()?->getVector()),
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Grant;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
