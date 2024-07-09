<?php

namespace App\Serializer\Normalizer;

use App\Entity\CPVCode;
use App\Entity\Funding;
use App\Entity\FundingRegion;
use App\Repository\FundingRepository;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class FundingNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private readonly ObjectNormalizer $normalizer,
        private readonly FundingRepository $fundingRepository
    ) {}

    /**
     * @param Funding $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        //        $data = $this->normalizer->normalize($object, $format, $context);
        //
        //        // TODO: add, edit, or delete some data
        //
        //        return $data;

        //        if (!$object->indexable()) {
        //            return [];
        //        }

        return [
            'id' => $object->getId(),
            'ocid' => $object->getOcid(),
            'hasParent' => $this->fundingRepository->hasParent($object->getOcid(), $object->getPublishedDate()),
            'title' => $object->getTitle(),
            'description' => $object->getDescription(),
            'fundingRegions' => $object
                ->getFundingRegions()
                ->map(fn (FundingRegion $fundingRegion) => $fundingRegion->getRegion())
                ->toArray(),
            'fundingSector' => $object
                ->getCpv()
                ->map(fn (CPVCode $cpv) => $cpv->getCode())
                ->toArray(),
            'mainSectors' => array_unique($object
                ->getCpv()
                ->map(fn (CPVCode $cpv) => $cpv->getCode()[0].$cpv->getCode()[1])
                ->toArray()),
            'tags' => $object->getTags(),
            'status' => $object->getStatus(),
            'value_low' => $object->getValueLow(),
            'value_high' => $object->getValueHigh(),
            'published_date' => $object->getPublishedDate()?->getTimestamp(),
            'closing_date' => $object->getClosingDate()?->getTimestamp(),
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Funding;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
