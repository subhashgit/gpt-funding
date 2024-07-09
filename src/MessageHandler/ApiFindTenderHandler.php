<?php

namespace App\MessageHandler;

use App\Entity\Funding;
use App\Message\ApiFindTender;
use App\Repository\FundingRepository;
use App\Service\FundingService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ApiFindTenderHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly FundingRepository $fundingRepository,
        private readonly FundingService $fundingService
    ) {}

    public function __invoke(ApiFindTender $message): void
    {
        $tender = $message->tender;

        if (!$funding = $this->fundingRepository->findOneBy(['external_id' => $tender->ocid])) {
            $funding = new Funding();
        }

        $funding->setNoticeIdentifier($tender->ocid);
        $funding->setNoticeType('Contract');
        $funding->setOrganisationName($tender->buyer->name);
        $funding->setStatus($tender->tender->status);
        $funding->setTitle($tender->tender->title);
        $funding->setDescription($tender->tender->description);
        //                $funding->setPostcode();

        //                $this->populateFundingRegionData($fundingModel, $funding);
        //                $this->populateCPVData($fundingModel, $funding);

        $funding->setContactName($tender->buyer->name);
        //                $funding->setContactEmail($tender->);
        //        $funding->setContactTelephone($fundingModel->getContactTelephone());
        //        $funding->setContactAddress1($fundingModel->getContactAddress1());
        //        $funding->setContactAddress2($fundingModel->getContactAddress2());
        //        $funding->setContactTown($fundingModel->getContactTown());
        //        $funding->setContactPostcode($fundingModel->getContactPostcode());
        //        $funding->setContactCountry($fundingModel->getContactCountry());
        //        $funding->setContactWebsite($fundingModel->getContactWebsite());
        //        $funding->setLinks($fundingModel->getLinks());
        //        $funding->setAdditionalText($fundingModel->getAdditionalText());
        //        $funding->setStartDate($fundingModel->getStartDate());
        //        $funding->setEndDate($fundingModel->getEndDate());
        //        $funding->setClosingDate($fundingModel->getClosingDate());
        //        $funding->setSuitableForSme($fundingModel->getSuitableForSme());
        //        $funding->setSuitableForVco($fundingModel->getSuitableForVco());
        //        $funding->setOjeuProcedureType($fundingModel->getOjeuProcedureType());
        //        $funding->setOjeuContractType($fundingModel->getOjeuContractType());
        //        $funding->setClosingTime($fundingModel->getClosingTime());
        //        $funding->setPublishedDate($fundingModel->getPublishedDate());
        //        $funding->setSupplyChain($fundingModel->getSupplyChain());
        //        $funding->setValueLow($fundingModel->getValueLow());
        //        $funding->setValueHigh($fundingModel->getValueHigh());
        //        $funding->setDownloads($fundingModel->getDownloads());
        //
        //        $funding->setExternalId($fundingModel->getExternalId());
        //        $funding->setExternalLink($fundingModel->getExternalLink());
    }
}
