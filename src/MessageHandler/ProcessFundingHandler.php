<?php

namespace App\MessageHandler;

use App\Entity\Funding;
use App\Message\ProcessFunding;
use App\Repository\CPVCodeRepository;
use App\Repository\FundingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProcessFundingHandler
{
    public function __construct(
        private CPVCodeRepository $cpvCodeRepository,
        private FundingRepository $fundingRepository,
        private EntityManagerInterface $em
    ) {}

    public function __invoke(ProcessFunding $message): void
    {
        $funding = $this->fundingRepository->findOneBy(['notice_identifier' => $message->funding->getNoticeIdentifier()]);
        if ($funding) {
            return;
        }

        try {
            $funding = $this->createFundingFromMessage($message);
            $this->em->persist($funding);
            $this->em->flush();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function createFundingFromMessage(ProcessFunding $message): Funding
    {
        $funding = new Funding();
        $sourceFunding = $message->funding;

        $funding->setNoticeIdentifier($sourceFunding->getNoticeIdentifier())
            ->setNoticeType($sourceFunding->getNoticeType())
            ->setOrganisationName($sourceFunding->getOrganisationName())
            ->setStatus($sourceFunding->getStatus())
            ->setTitle($sourceFunding->getTitle())
            ->setDescription($sourceFunding->getDescription())
            ->setPostcode($sourceFunding->getPostcode())
            ->setRegion($sourceFunding->getRegion())
        ;

        foreach ($sourceFunding->getCpvCodes() as $cpvCode) {
            $cpv = $this->cpvCodeRepository->createQueryBuilder('c')
                ->where('c.code like :code')
                ->setParameter('code', "{$cpvCode}%")
                ->getQuery()
                ->getSingleResult()
            ;

            $funding->addCpv($cpv);
        }

        $funding->setContactName($sourceFunding->getContactName())
            ->setContactEmail($sourceFunding->getContactEmail())
            ->setContactTelephone($sourceFunding->getContactTelephone())
            ->setContactAddress1($sourceFunding->getContactAddress1())
            ->setContactAddress2($sourceFunding->getContactAddress2())
            ->setContactTown($sourceFunding->getContactTown())
            ->setContactPostcode($sourceFunding->getContactPostcode())
            ->setContactCountry($sourceFunding->getContactCountry())
            ->setContactWebsite($sourceFunding->getContactWebsite())
        ;

        $funding->setLinks($sourceFunding->getLinks())
            ->setAdditionalText($sourceFunding->getAdditionalText())
            ->setStartDate($sourceFunding->getStartDate())
            ->setEndDate($sourceFunding->getEndDate())
            ->setClosingDate($sourceFunding->getClosingDate())
            ->setSuitableForSme($sourceFunding->getSuitableForSme())
            ->setSuitableForVco($sourceFunding->getSuitableForVco())
            ->setOjeuProcedureType($sourceFunding->getOjeuProcedureType())
            ->setOjeuContractType($sourceFunding->getOjeuContractType())
            ->setClosingTime($sourceFunding->getClosingTime())
            ->setPublishedDate($sourceFunding->getPublishedDate())
            ->setSupplyChain($sourceFunding->getSupplyChain())
            ->setValueLow($sourceFunding->getValueLow())
            ->setValueHigh($sourceFunding->getValueHigh())
        ;

        return $funding;
    }
}
