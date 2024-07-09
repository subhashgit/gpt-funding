<?php

namespace App\Entity;

use App\Repository\ScoresRanksRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoresRanksRepository::class)]
class ScoresRanks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lsoaCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lsoaName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localAuthorityDistrictCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localAuthorityDistrictName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imdScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imdRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imdDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $incomeScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $incomeRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $incomeDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $employmentScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $employmentDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $educationSkillsAndTrainingScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $educationSkillsAndTrainingRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $educationSkillsAndTrainingDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $healthDeprivationAndDisabilityScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $healthDeprivationAndDisabilityRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $healthDeprivationAndDisabilityDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $crimeScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $crimeRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $crimeDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $barriersToHousingAndServicesScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $barriersToHousingAndServicesRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $barriersToHousingAndServicesDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $livingEnvironmentScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $livingEnvironmentRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $livingEnvironmentDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $incomeDeprivationAffectingChildrenIndexIdaciScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $incomeDeprivationAffectingChildrenIndexIdaciRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $incomeDeprivationAffectingChildrenIndexIdaciDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $incomeDeprivationAffectingOlderPeopleIdaopiScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $incomeDeprivationAffectingOlderPeopleIdaopiRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $incomeDeprivationAffectingOlderPeopleIdaopiDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $childrenAndYoungPeopleSubDomainScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $childrenAndYoungPeopleSubDomainRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $childrenAndYoungPeopleSubDomainDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adultSkillsSubDomainScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adultSkillsSubDomainRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adultSkillsSubDomainDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $geographicalBarriersSubDomainScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $geographicalBarriersSubDomainRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $geographicalBarriersSubDomainDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $widerBarriersSubDomainScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $widerBarriersSubDomainRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $widerBarriersSubDomainDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $indoorsSubDomainScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $indoorsSubDomainRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $indoorsSubDomainDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $outdoorsSubDomainScore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $outdoorsSubDomainRank = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $outdoorsSubDomainDecile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $totalPopulationMid2015ExcludingPrisoners = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dependentChildrenAged015Mid2015ExcludingPrisoners = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $populationAged1659Mid2015ExcludingPrisoners = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $olderPopulationAged60AndOverMid2015ExcludingPrisoners = null;

    #[ORM\Column(name: 'employment_deprivation_domain_excluding_prisoners', length: 255, nullable: true)]
    private ?string $workingAgePopulation185964ForUseWithEmploymentDeprivationDomainExcludingPrisoners = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $employmentRank = null;

    public function __get(string $name)
    {
        $ucfirst = ucfirst($name);

        return $this->{"get{$ucfirst}"}();
    }

    public function __set(string $name, $value): void
    {
        $ucfirst = ucfirst($name);
        $this->{"set{$ucfirst}"}($value);
    }

    public function __isset(string $name): bool
    {
        $ucfirst = ucfirst($name);

        return method_exists($this, "get{$ucfirst}");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLsoaCode(): ?string
    {
        return $this->lsoaCode;
    }

    public function setLsoaCode(string $lsoaCode): static
    {
        $this->lsoaCode = $lsoaCode;

        return $this;
    }

    public function getLsoaName(): ?string
    {
        return $this->lsoaName;
    }

    public function setLsoaName(string $lsoaName): static
    {
        $this->lsoaName = $lsoaName;

        return $this;
    }

    public function getLocalAuthorityDistrictCode(): ?string
    {
        return $this->localAuthorityDistrictCode;
    }

    public function setLocalAuthorityDistrictCode(string $localAuthorityDistrictCode): static
    {
        $this->localAuthorityDistrictCode = $localAuthorityDistrictCode;

        return $this;
    }

    public function getLocalAuthorityDistrictName(): ?string
    {
        return $this->localAuthorityDistrictName;
    }

    public function setLocalAuthorityDistrictName(string $localAuthorityDistrictName): static
    {
        $this->localAuthorityDistrictName = $localAuthorityDistrictName;

        return $this;
    }

    public function getImdScore(): ?string
    {
        return $this->imdScore;
    }

    public function setImdScore(string $imdScore): static
    {
        $this->imdScore = $imdScore;

        return $this;
    }

    public function getImdRank(): ?string
    {
        return $this->imdRank;
    }

    public function setImdRank(string $imdRank): static
    {
        $this->imdRank = $imdRank;

        return $this;
    }

    public function getImdDecile(): ?string
    {
        return $this->imdDecile;
    }

    public function setImdDecile(string $imdDecile): static
    {
        $this->imdDecile = $imdDecile;

        return $this;
    }

    public function getIncomeScore(): ?string
    {
        return $this->incomeScore;
    }

    public function setIncomeScore(string $incomeScore): static
    {
        $this->incomeScore = $incomeScore;

        return $this;
    }

    public function getIncomeRank(): ?string
    {
        return $this->incomeRank;
    }

    public function setIncomeRank(string $incomeRank): static
    {
        $this->incomeRank = $incomeRank;

        return $this;
    }

    public function getIncomeDecile(): ?string
    {
        return $this->incomeDecile;
    }

    public function setIncomeDecile(string $incomeDecile): static
    {
        $this->incomeDecile = $incomeDecile;

        return $this;
    }

    public function getEmploymentScore(): ?string
    {
        return $this->employmentScore;
    }

    public function setEmploymentScore(string $employmentScore): static
    {
        $this->employmentScore = $employmentScore;

        return $this;
    }

    public function getEmploymentDecile(): ?string
    {
        return $this->employmentDecile;
    }

    public function setEmploymentDecile(string $employmentDecile): static
    {
        $this->employmentDecile = $employmentDecile;

        return $this;
    }

    public function getEducationSkillsAndTrainingScore(): ?string
    {
        return $this->educationSkillsAndTrainingScore;
    }

    public function setEducationSkillsAndTrainingScore(string $educationSkillsAndTrainingScore): static
    {
        $this->educationSkillsAndTrainingScore = $educationSkillsAndTrainingScore;

        return $this;
    }

    public function getEducationSkillsAndTrainingRank(): ?string
    {
        return $this->educationSkillsAndTrainingRank;
    }

    public function setEducationSkillsAndTrainingRank(string $educationSkillsAndTrainingRank): static
    {
        $this->educationSkillsAndTrainingRank = $educationSkillsAndTrainingRank;

        return $this;
    }

    public function getEducationSkillsAndTrainingDecile(): ?string
    {
        return $this->educationSkillsAndTrainingDecile;
    }

    public function setEducationSkillsAndTrainingDecile(string $educationSkillsAndTrainingDecile): static
    {
        $this->educationSkillsAndTrainingDecile = $educationSkillsAndTrainingDecile;

        return $this;
    }

    public function getHealthDeprivationAndDisabilityScore(): ?string
    {
        return $this->healthDeprivationAndDisabilityScore;
    }

    public function setHealthDeprivationAndDisabilityScore(string $healthDeprivationAndDisabilityScore): static
    {
        $this->healthDeprivationAndDisabilityScore = $healthDeprivationAndDisabilityScore;

        return $this;
    }

    public function getHealthDeprivationAndDisabilityRank(): ?string
    {
        return $this->healthDeprivationAndDisabilityRank;
    }

    public function setHealthDeprivationAndDisabilityRank(string $healthDeprivationAndDisabilityRank): static
    {
        $this->healthDeprivationAndDisabilityRank = $healthDeprivationAndDisabilityRank;

        return $this;
    }

    public function getHealthDeprivationAndDisabilityDecile(): ?string
    {
        return $this->healthDeprivationAndDisabilityDecile;
    }

    public function setHealthDeprivationAndDisabilityDecile(string $healthDeprivationAndDisabilityDecile): static
    {
        $this->healthDeprivationAndDisabilityDecile = $healthDeprivationAndDisabilityDecile;

        return $this;
    }

    public function getCrimeScore(): ?string
    {
        return $this->crimeScore;
    }

    public function setCrimeScore(string $crimeScore): static
    {
        $this->crimeScore = $crimeScore;

        return $this;
    }

    public function getCrimeRank(): ?string
    {
        return $this->crimeRank;
    }

    public function setCrimeRank(string $crimeRank): static
    {
        $this->crimeRank = $crimeRank;

        return $this;
    }

    public function getCrimeDecile(): ?string
    {
        return $this->crimeDecile;
    }

    public function setCrimeDecile(string $crimeDecile): static
    {
        $this->crimeDecile = $crimeDecile;

        return $this;
    }

    public function getBarriersToHousingAndServicesScore(): ?string
    {
        return $this->barriersToHousingAndServicesScore;
    }

    public function setBarriersToHousingAndServicesScore(string $barriersToHousingAndServicesScore): static
    {
        $this->barriersToHousingAndServicesScore = $barriersToHousingAndServicesScore;

        return $this;
    }

    public function getBarriersToHousingAndServicesRank(): ?string
    {
        return $this->barriersToHousingAndServicesRank;
    }

    public function setBarriersToHousingAndServicesRank(string $barriersToHousingAndServicesRank): static
    {
        $this->barriersToHousingAndServicesRank = $barriersToHousingAndServicesRank;

        return $this;
    }

    public function getBarriersToHousingAndServicesDecile(): ?string
    {
        return $this->barriersToHousingAndServicesDecile;
    }

    public function setBarriersToHousingAndServicesDecile(string $barriersToHousingAndServicesDecile): static
    {
        $this->barriersToHousingAndServicesDecile = $barriersToHousingAndServicesDecile;

        return $this;
    }

    public function getLivingEnvironmentScore(): ?string
    {
        return $this->livingEnvironmentScore;
    }

    public function setLivingEnvironmentScore(string $livingEnvironmentScore): static
    {
        $this->livingEnvironmentScore = $livingEnvironmentScore;

        return $this;
    }

    public function getLivingEnvironmentRank(): ?string
    {
        return $this->livingEnvironmentRank;
    }

    public function setLivingEnvironmentRank(string $livingEnvironmentRank): static
    {
        $this->livingEnvironmentRank = $livingEnvironmentRank;

        return $this;
    }

    public function getLivingEnvironmentDecile(): ?string
    {
        return $this->livingEnvironmentDecile;
    }

    public function setLivingEnvironmentDecile(string $livingEnvironmentDecile): static
    {
        $this->livingEnvironmentDecile = $livingEnvironmentDecile;

        return $this;
    }

    public function getIncomeDeprivationAffectingChildrenIndexIdaciScore(): ?string
    {
        return $this->incomeDeprivationAffectingChildrenIndexIdaciScore;
    }

    public function setIncomeDeprivationAffectingChildrenIndexIdaciScore(string $incomeDeprivationAffectingChildrenIndexIdaciScore): static
    {
        $this->incomeDeprivationAffectingChildrenIndexIdaciScore = $incomeDeprivationAffectingChildrenIndexIdaciScore;

        return $this;
    }

    public function getIncomeDeprivationAffectingChildrenIndexIdaciRank(): ?string
    {
        return $this->incomeDeprivationAffectingChildrenIndexIdaciRank;
    }

    public function setIncomeDeprivationAffectingChildrenIndexIdaciRank(string $incomeDeprivationAffectingChildrenIndexIdaciRank): static
    {
        $this->incomeDeprivationAffectingChildrenIndexIdaciRank = $incomeDeprivationAffectingChildrenIndexIdaciRank;

        return $this;
    }

    public function getIncomeDeprivationAffectingChildrenIndexIdaciDecile(): ?string
    {
        return $this->incomeDeprivationAffectingChildrenIndexIdaciDecile;
    }

    public function setIncomeDeprivationAffectingChildrenIndexIdaciDecile(string $incomeDeprivationAffectingChildrenIndexIdaciDecile): static
    {
        $this->incomeDeprivationAffectingChildrenIndexIdaciDecile = $incomeDeprivationAffectingChildrenIndexIdaciDecile;

        return $this;
    }

    public function getIncomeDeprivationAffectingOlderPeopleIdaopiScore(): ?string
    {
        return $this->incomeDeprivationAffectingOlderPeopleIdaopiScore;
    }

    public function setIncomeDeprivationAffectingOlderPeopleIdaopiScore(string $incomeDeprivationAffectingOlderPeopleIdaopiScore): static
    {
        $this->incomeDeprivationAffectingOlderPeopleIdaopiScore = $incomeDeprivationAffectingOlderPeopleIdaopiScore;

        return $this;
    }

    public function getIncomeDeprivationAffectingOlderPeopleIdaopiRank(): ?string
    {
        return $this->incomeDeprivationAffectingOlderPeopleIdaopiRank;
    }

    public function setIncomeDeprivationAffectingOlderPeopleIdaopiRank(string $incomeDeprivationAffectingOlderPeopleIdaopiRank): static
    {
        $this->incomeDeprivationAffectingOlderPeopleIdaopiRank = $incomeDeprivationAffectingOlderPeopleIdaopiRank;

        return $this;
    }

    public function getIncomeDeprivationAffectingOlderPeopleIdaopiDecile(): ?string
    {
        return $this->incomeDeprivationAffectingOlderPeopleIdaopiDecile;
    }

    public function setIncomeDeprivationAffectingOlderPeopleIdaopiDecile(string $incomeDeprivationAffectingOlderPeopleIdaopiDecile): static
    {
        $this->incomeDeprivationAffectingOlderPeopleIdaopiDecile = $incomeDeprivationAffectingOlderPeopleIdaopiDecile;

        return $this;
    }

    public function getChildrenAndYoungPeopleSubDomainScore(): ?string
    {
        return $this->childrenAndYoungPeopleSubDomainScore;
    }

    public function setChildrenAndYoungPeopleSubDomainScore(string $childrenAndYoungPeopleSubDomainScore): static
    {
        $this->childrenAndYoungPeopleSubDomainScore = $childrenAndYoungPeopleSubDomainScore;

        return $this;
    }

    public function getChildrenAndYoungPeopleSubDomainRank(): ?string
    {
        return $this->childrenAndYoungPeopleSubDomainRank;
    }

    public function setChildrenAndYoungPeopleSubDomainRank(string $childrenAndYoungPeopleSubDomainRank): static
    {
        $this->childrenAndYoungPeopleSubDomainRank = $childrenAndYoungPeopleSubDomainRank;

        return $this;
    }

    public function getChildrenAndYoungPeopleSubDomainDecile(): ?string
    {
        return $this->childrenAndYoungPeopleSubDomainDecile;
    }

    public function setChildrenAndYoungPeopleSubDomainDecile(string $childrenAndYoungPeopleSubDomainDecile): static
    {
        $this->childrenAndYoungPeopleSubDomainDecile = $childrenAndYoungPeopleSubDomainDecile;

        return $this;
    }

    public function getAdultSkillsSubDomainScore(): ?string
    {
        return $this->adultSkillsSubDomainScore;
    }

    public function setAdultSkillsSubDomainScore(string $adultSkillsSubDomainScore): static
    {
        $this->adultSkillsSubDomainScore = $adultSkillsSubDomainScore;

        return $this;
    }

    public function getAdultSkillsSubDomainRank(): ?string
    {
        return $this->adultSkillsSubDomainRank;
    }

    public function setAdultSkillsSubDomainRank(string $adultSkillsSubDomainRank): static
    {
        $this->adultSkillsSubDomainRank = $adultSkillsSubDomainRank;

        return $this;
    }

    public function getAdultSkillsSubDomainDecile(): ?string
    {
        return $this->adultSkillsSubDomainDecile;
    }

    public function setAdultSkillsSubDomainDecile(string $adultSkillsSubDomainDecile): static
    {
        $this->adultSkillsSubDomainDecile = $adultSkillsSubDomainDecile;

        return $this;
    }

    public function getGeographicalBarriersSubDomainScore(): ?string
    {
        return $this->geographicalBarriersSubDomainScore;
    }

    public function setGeographicalBarriersSubDomainScore(string $geographicalBarriersSubDomainScore): static
    {
        $this->geographicalBarriersSubDomainScore = $geographicalBarriersSubDomainScore;

        return $this;
    }

    public function getGeographicalBarriersSubDomainRank(): ?string
    {
        return $this->geographicalBarriersSubDomainRank;
    }

    public function setGeographicalBarriersSubDomainRank(string $geographicalBarriersSubDomainRank): static
    {
        $this->geographicalBarriersSubDomainRank = $geographicalBarriersSubDomainRank;

        return $this;
    }

    public function getGeographicalBarriersSubDomainDecile(): ?string
    {
        return $this->geographicalBarriersSubDomainDecile;
    }

    public function setGeographicalBarriersSubDomainDecile(string $geographicalBarriersSubDomainDecile): static
    {
        $this->geographicalBarriersSubDomainDecile = $geographicalBarriersSubDomainDecile;

        return $this;
    }

    public function getWiderBarriersSubDomainScore(): ?string
    {
        return $this->widerBarriersSubDomainScore;
    }

    public function setWiderBarriersSubDomainScore(string $widerBarriersSubDomainScore): static
    {
        $this->widerBarriersSubDomainScore = $widerBarriersSubDomainScore;

        return $this;
    }

    public function getWiderBarriersSubDomainRank(): ?string
    {
        return $this->widerBarriersSubDomainRank;
    }

    public function setWiderBarriersSubDomainRank(string $widerBarriersSubDomainRank): static
    {
        $this->widerBarriersSubDomainRank = $widerBarriersSubDomainRank;

        return $this;
    }

    public function getWiderBarriersSubDomainDecile(): ?string
    {
        return $this->widerBarriersSubDomainDecile;
    }

    public function setWiderBarriersSubDomainDecile(string $widerBarriersSubDomainDecile): static
    {
        $this->widerBarriersSubDomainDecile = $widerBarriersSubDomainDecile;

        return $this;
    }

    public function getIndoorsSubDomainScore(): ?string
    {
        return $this->indoorsSubDomainScore;
    }

    public function setIndoorsSubDomainScore(string $indoorsSubDomainScore): static
    {
        $this->indoorsSubDomainScore = $indoorsSubDomainScore;

        return $this;
    }

    public function getIndoorsSubDomainRank(): ?string
    {
        return $this->indoorsSubDomainRank;
    }

    public function setIndoorsSubDomainRank(string $indoorsSubDomainRank): static
    {
        $this->indoorsSubDomainRank = $indoorsSubDomainRank;

        return $this;
    }

    public function getIndoorsSubDomainDecile(): ?string
    {
        return $this->indoorsSubDomainDecile;
    }

    public function setIndoorsSubDomainDecile(string $indoorsSubDomainDecile): static
    {
        $this->indoorsSubDomainDecile = $indoorsSubDomainDecile;

        return $this;
    }

    public function getOutdoorsSubDomainScore(): ?string
    {
        return $this->outdoorsSubDomainScore;
    }

    public function setOutdoorsSubDomainScore(string $outdoorsSubDomainScore): static
    {
        $this->outdoorsSubDomainScore = $outdoorsSubDomainScore;

        return $this;
    }

    public function getOutdoorsSubDomainRank(): ?string
    {
        return $this->outdoorsSubDomainRank;
    }

    public function setOutdoorsSubDomainRank(string $outdoorsSubDomainRank): static
    {
        $this->outdoorsSubDomainRank = $outdoorsSubDomainRank;

        return $this;
    }

    public function getOutdoorsSubDomainDecile(): ?string
    {
        return $this->outdoorsSubDomainDecile;
    }

    public function setOutdoorsSubDomainDecile(string $outdoorsSubDomainDecile): static
    {
        $this->outdoorsSubDomainDecile = $outdoorsSubDomainDecile;

        return $this;
    }

    public function getTotalPopulationMid2015ExcludingPrisoners(): ?string
    {
        return $this->totalPopulationMid2015ExcludingPrisoners;
    }

    public function setTotalPopulationMid2015ExcludingPrisoners(string $totalPopulationMid2015ExcludingPrisoners): static
    {
        $this->totalPopulationMid2015ExcludingPrisoners = $totalPopulationMid2015ExcludingPrisoners;

        return $this;
    }

    public function getDependentChildrenAged015Mid2015ExcludingPrisoners(): ?string
    {
        return $this->dependentChildrenAged015Mid2015ExcludingPrisoners;
    }

    public function setDependentChildrenAged015Mid2015ExcludingPrisoners(string $dependentChildrenAged015Mid2015ExcludingPrisoners): static
    {
        $this->dependentChildrenAged015Mid2015ExcludingPrisoners = $dependentChildrenAged015Mid2015ExcludingPrisoners;

        return $this;
    }

    public function getPopulationAged1659Mid2015ExcludingPrisoners(): ?string
    {
        return $this->populationAged1659Mid2015ExcludingPrisoners;
    }

    public function setPopulationAged1659Mid2015ExcludingPrisoners(string $populationAged1659Mid2015ExcludingPrisoners): static
    {
        $this->populationAged1659Mid2015ExcludingPrisoners = $populationAged1659Mid2015ExcludingPrisoners;

        return $this;
    }

    public function getOlderPopulationAged60AndOverMid2015ExcludingPrisoners(): ?string
    {
        return $this->olderPopulationAged60AndOverMid2015ExcludingPrisoners;
    }

    public function setOlderPopulationAged60AndOverMid2015ExcludingPrisoners(string $olderPopulationAged60AndOverMid2015ExcludingPrisoners): static
    {
        $this->olderPopulationAged60AndOverMid2015ExcludingPrisoners = $olderPopulationAged60AndOverMid2015ExcludingPrisoners;

        return $this;
    }

    public function getWorkingAgePopulation185964ForUseWithEmploymentDeprivationDomainExcludingPrisoners(): ?string
    {
        return $this->workingAgePopulation185964ForUseWithEmploymentDeprivationDomainExcludingPrisoners;
    }

    public function setWorkingAgePopulation185964ForUseWithEmploymentDeprivationDomainExcludingPrisoners(string $workingAgePopulation185964ForUseWithEmploymentDeprivationDomainExcludingPrisoners): static
    {
        $this->workingAgePopulation185964ForUseWithEmploymentDeprivationDomainExcludingPrisoners = $workingAgePopulation185964ForUseWithEmploymentDeprivationDomainExcludingPrisoners;

        return $this;
    }

    public function getEmploymentRank(): ?string
    {
        return $this->employmentRank;
    }

    public function setEmploymentRank(string $employmentRank): static
    {
        $this->employmentRank = $employmentRank;

        return $this;
    }
}
