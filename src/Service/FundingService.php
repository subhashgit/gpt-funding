<?php

namespace App\Service;

use App\Entity\Funding;
use App\Entity\FundingRegion;
use App\Entity\Lot;
use App\Entity\Party;
use App\Enum\Status;
use App\Enum\Tag;
use App\Model;
use App\Model\API\FindTender\DeliveryAddresses;
use App\Model\API\FindTender\FindTenderItem;
use App\Repository\CPVCodeRepository;
use App\Repository\FundingRegionRepository;
use App\Repository\PartyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;

class FundingService
{
    private array $regions = [
        'East Midlands',
        'East of England',
        'North West',
        'North East',
        'London',
        'South East',
        'South West',
        'Yorkshire and Humber',
        'England',
        'Scotland',
        'Wales',
        'Northern Ireland',
    ];

    public function __construct(
        private EntityManagerInterface $em,
        private CPVCodeRepository $cpvCodeRepository,
        private FundingRegionRepository $fundingRegionRepository,
        private PartyRepository $partyRepository,
    ) {}

    public function createFunding(Model\Funding $fundingModel, ?Funding $funding = null): Funding
    {
        if (!$funding) {
            $funding = new Funding();
        }

        $funding->setNoticeIdentifier($fundingModel->getNoticeIdentifier());
        $funding->setNoticeType($fundingModel->getNoticeType());
        $funding->setOrganisationName($fundingModel->getOrganisationName());
        $funding->setStatus(Status::OPEN);
        $funding->setTitle($fundingModel->getTitle());
        $funding->setDescription($fundingModel->getDescription());
        $funding->setPostcode($fundingModel->getPostcode());

        $this->populateFundingRegionData($fundingModel, $funding);
        $this->populateCPVData($fundingModel, $funding);

        $funding->setContactName($fundingModel->getContactName());
        $funding->setContactEmail($fundingModel->getContactEmail());
        $funding->setContactTelephone($fundingModel->getContactTelephone());
        $funding->setContactAddress1($fundingModel->getContactAddress1());
        $funding->setContactAddress2($fundingModel->getContactAddress2());
        $funding->setContactTown($fundingModel->getContactTown());
        $funding->setContactPostcode($fundingModel->getContactPostcode());
        $funding->setContactCountry($fundingModel->getContactCountry());
        $funding->setContactWebsite($fundingModel->getContactWebsite());
        $funding->setLinks($fundingModel->getLinks());
        $funding->setAdditionalText($fundingModel->getAdditionalText());
        $funding->setStartDate($fundingModel->getStartDate());
        $funding->setEndDate($fundingModel->getEndDate());
        $funding->setClosingDate($fundingModel->getClosingDate());
        $funding->setSuitableForSme($fundingModel->getSuitableForSme());
        $funding->setSuitableForVco($fundingModel->getSuitableForVco());
        $funding->setOjeuProcedureType($fundingModel->getOjeuProcedureType());
        $funding->setOjeuContractType($fundingModel->getOjeuContractType());
        $funding->setClosingTime($fundingModel->getClosingTime());
        $funding->setPublishedDate($fundingModel->getPublishedDate());
        $funding->setSupplyChain($fundingModel->getSupplyChain());
        $funding->setValueLow($fundingModel->getValueLow());
        $funding->setValueHigh($fundingModel->getValueHigh());
        $funding->setDownloads($fundingModel->getDownloads());

        $funding->setExternalId($fundingModel->getExternalId());
        $funding->setExternalLink($fundingModel->getExternalLink());

        $this->em->persist($funding);
        $this->em->flush();

        return $funding;
    }

    /**
     * Creates a Funding object based on the given FindTenderItem and type.
     *
     * @param FindTenderItem $fundingItem the FindTenderItem to create the Funding from
     * @param Funding|null   $funding     the Funding object to update, if null a new Funding object will be created
     * @param string         $type        the type of the Funding
     *
     * @return Funding the created or updated Funding object
     */
    public function createFundingAPI(FindTenderItem $fundingItem, ?Funding $funding = null, string $type): ?Funding
    {
        if (!$funding) {
            $funding = new Funding();
        } else {
            $funding->setOcid($fundingItem->ocid);
            $this->em->flush();

            return null;
        }

        $funding->setOcid($fundingItem->ocid);
        $funding->setNoticeIdentifier($fundingItem->id);
        $funding->setNoticeType('Contract');
        $funding->setOrganisationName($fundingItem->buyer->name);
        $funding->setStatus(Status::OPEN);
        $funding->setTitle($fundingItem->tender->title);
        $funding->setDescription($fundingItem->tender->description);

        $regions = [];
        $isInternational = false;
        foreach ($fundingItem->tender->items ?? [] as $itemsItem) {
            $regions += array_map(
                callback: static function (DeliveryAddresses $region) use (&$isInternational) {
                    if (!$region->countryName and !$region->region) {
                        return null;
                    }

                    if ('United Kingdom' === $region->countryName) {
                        return $region->region ?? $region->countryName;
                    }

                    if (!$region->countryName and $region->region) {
                        return $region->region;
                    }

                    $isInternational = true;

                    return $region->countryName;
                },
                array: $itemsItem->deliveryAddresses ?? []
            );
        }

        foreach (array_filter($regions) ?? [] as $predefinedRegion) {
            $fr = $this->fundingRegionRepository->findOneBy(['region' => $predefinedRegion]);
            if (!$fr) {
                $fr = new FundingRegion();
                $fr->setRegion($predefinedRegion);
                $this->em->persist($fr);
            }
            $funding->addFundingRegion($fr);
        }

        if ($isInternational) {
            $fr = $this->fundingRegionRepository->findOneBy(['region' => 'International']);
            if (!$fr) {
                $fr = new FundingRegion();
                $fr->setRegion('International');
                $this->em->persist($fr);
            }
            $funding->addFundingRegion($fr);
        }

        $classification = array_merge([$fundingItem->tender->classification], $fundingItem->tender->additionalClassifications ?? []);

        foreach ($classification ?? [] as $cpvCode) {
            try {
                $cpv = $this->cpvCodeRepository->createQueryBuilder('c')
                    ->where('c.code like :code')
                    ->setParameter('code', "{$cpvCode->id}%")
                    ->getQuery()
                    ->getSingleResult()
                ;
                $funding->addCpv($cpv);
            } catch (\Exception $e) {
                // handle exception if necessary
            }
        }

        foreach ($fundingItem->parties ?? [] as $party) {
            $partyEntity = $this->partyRepository->findOneBy(['external_id' => $party->id]);
            if (!$partyEntity) {
                $partyEntity = new Party();
            }
            $partyEntity->setExternalId($party->id);
            $partyEntity->setName($party->name ?? $party->id);
            $partyEntity->setAddress($party->address);
            $partyEntity->setContactPoint($party->contactPoint);
            $partyEntity->setRoles($party->roles);
            $partyEntity->setDetails($party->details);
            // $partyEntity->setIdentifier($party->identifier);

            $this->em->persist($partyEntity);

            $funding->addParty($partyEntity);
        }

        $funding->setLinks(
            array_map(
                static fn (Model\API\FindTender\Documents $document) => $document->url,
                $fundingItem->tender->documents ?? []
            )
        );

        $funding->setStartDate($fundingItem->tender?->contractPeriod?->startDate);
        $funding->setEndDate($fundingItem->tender?->contractPeriod?->endDate);
        $funding->setClosingDate($fundingItem->tender?->tenderPeriod?->endDate);
        $funding->setSuitableForSme($fundingItem->tender?->suitability?->sme);
        $funding->setSuitableForVco($fundingItem->tender?->suitability?->vcse);
        $funding->setOjeuProcedureType($fundingItem->tender->procurementMethodDetails);
        $funding->setOjeuContractType($fundingItem->tender->mainProcurementCategory);
        $funding->setPublishedDate($fundingItem->date);
        $funding->setSupplyChain(false);
        $funding->setValueLow($fundingItem->tender?->minValue?->amount);
        $funding->setValueHigh($fundingItem->tender?->value?->amount);
        $funding->setDownloads(
            array_map(
                static fn (Model\API\FindTender\Documents $document) => $document->url,
                $fundingItem->tender->documents ?? []
            )
        );
        $funding->setSubmissionMethod($fundingItem->tender->submissionMethod);
        $funding->setSubmissionMethodDetails($fundingItem->tender->submissionMethodDetails);

        $funding->setExternalId($fundingItem->id);
        $funding->setExternalLink($fundingItem->getUrl($type));

        $funding->setTags(array_map(fn ($item) => Tag::from($item), $fundingItem->tag));

        $funding->setProcurementMethod($fundingItem->tender->procurementMethod);
        $funding->setProcurementMethodDetails($fundingItem->tender->procurementMethodDetails);
        $funding->setMainProcurementCategory($fundingItem->tender->mainProcurementCategory);

        foreach ($funding->getLots() ?? [] as $l) {
            $this->em->remove($l);
        }

        foreach ($fundingItem->tender->lots ?? [] as $lot) {
            $Lot = new Lot();
            $Lot->setDescription($lot->description);
            $Lot->setValue($lot?->value?->amount);
            $Lot->setContractPeriod($lot->contractPeriod);
            $Lot->setHasOptions($lot->hasOptions);
            $Lot->setAwardCriteria($lot->awardCriteria);
            $Lot->setHasOptions($lot->hasOptions);
            $Lot->setRenewal($lot->renewal);
            $Lot->setSubmissionTerms($lot->submissionTerms);
            $Lot->setStatus($lot->status);
            $funding->addLot($Lot);

            $this->em->persist($Lot);
        }

        $this->em->persist($funding);
        $this->em->flush();

        return $funding;
    }

    public function parseMoney(?string $value): ?string
    {
        if (!$value) {
            return null;
        }
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter('en_GB', \NumberFormatter::CURRENCY);

        try {
            return (new IntlMoneyParser($numberFormatter, $currencies))->parse(trim($value))->getAmount() / 100;
        } catch (\Exception $e) {
            return trim($value);
        }
    }

    private function populateFundingRegionData(Model\Funding $fundingModel, Funding $funding): void
    {
        if (!$fundingModel->getRegion()) {
            return;
        }

        $hasRegion = false;
        foreach ($this->regions as $predefinedRegion) {
            foreach ($fundingModel->getRegion() as $region) {
                if (str_contains($predefinedRegion, $region)) {
                    $fr = $this->fundingRegionRepository->findOneBy(['region' => $predefinedRegion]);
                    if (!$fr) {
                        $fr = new FundingRegion();
                        $fr->setRegion($predefinedRegion);
                        $this->em->persist($fr);
                    }
                    $funding->addFundingRegion($fr);

                    $hasRegion = true;
                }
            }
        }

        if ($fundingModel->getRegion() and !$hasRegion) {
            $fr = $this->fundingRegionRepository->findOneBy(['region' => 'International']);
            if (!$fr) {
                $fr = new FundingRegion();
                $fr->setRegion('International');
                $this->em->persist($fr);
            }
            $funding->addFundingRegion($fr);
        }
    }

    private function populateCPVData(Model\Funding $fundingModel, Funding $funding): void
    {
        foreach ($fundingModel->getCpvCodes() as $cpvCode) {
            try {
                $cpv = $this->cpvCodeRepository->createQueryBuilder('c')
                    ->where('c.code like :code')
                    ->setParameter('code', "{$cpvCode}%")
                    ->getQuery()
                    ->getSingleResult()
                ;
                $funding->addCpv($cpv);
            } catch (\Exception $e) {
                // handle exception if necessary
            }
        }
    }
}
