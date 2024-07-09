<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\SerializedName;

class DeprivationData implements \ArrayAccess
{
    public const FIELDS = [
        'lsoaCode' => 'LSOA code (2011)',
        'lsoaName' => 'LSOA name (2011)',
        'localAuthorityDistrictCode' => 'Local Authority District code (2019)',
        'localAuthorityDistrictName' => 'Local Authority District name (2019)',
        'imdScore' => 'Index of Multiple Deprivation (IMD) Score',
        'imdRank' => 'Index of Multiple Deprivation (IMD) Rank (where 1 is most deprived)',
        'imdDecile' => 'Index of Multiple Deprivation (IMD) Decile (where 1 is most deprived 10% of LSOAs)',
        'incomeScore' => 'Income Score (rate)',
        'incomeRank' => 'Income Rank (where 1 is most deprived)',
        'incomeDecile' => 'Income Decile (where 1 is most deprived 10% of LSOAs)',
        'employmentScore' => 'Employment Score (rate)',
        'employmentRank' => 'Employment Rank (where 1 is most deprived)',
        'employmentDecile' => 'Employment Decile (where 1 is most deprived 10% of LSOAs)',
        'educationSkillsAndTrainingScore' => 'Education, Skills and Training Score',
        'educationSkillsAndTrainingRank' => 'Education, Skills and Training Rank (where 1 is most deprived)',
        'educationSkillsAndTrainingDecile' => 'Education, Skills and Training Decile (where 1 is most deprived 10% of LSOAs)',
        'healthDeprivationAndDisabilityScore' => 'Health Deprivation and Disability Score',
        'healthDeprivationAndDisabilityRank' => 'Health Deprivation and Disability Rank (where 1 is most deprived)',
        'healthDeprivationAndDisabilityDecile' => 'Health Deprivation and Disability Decile (where 1 is most deprived 10% of LSOAs)',
        'crimeScore' => 'Crime Score',
        'crimeRank' => 'Crime Rank (where 1 is most deprived)',
        'crimeDecile' => 'Crime Decile (where 1 is most deprived 10% of LSOAs)',
        'barriersToHousingAndServicesScore' => 'Barriers to Housing and Services Score',
        'barriersToHousingAndServicesRank' => 'Barriers to Housing and Services Rank (where 1 is most deprived)',
        'barriersToHousingAndServicesDecile' => 'Barriers to Housing and Services Decile (where 1 is most deprived 10% of LSOAs)',
        'livingEnvironmentScore' => 'Living Environment Score',
        'livingEnvironmentRank' => 'Living Environment Rank (where 1 is most deprived)',
        'livingEnvironmentDecile' => 'Living Environment Decile (where 1 is most deprived 10% of LSOAs)',
        'incomeDeprivationAffectingChildrenIndexIdaciScore' => 'Income Deprivation Affecting Children Index (IDACI) Score (rate)',
        'incomeDeprivationAffectingChildrenIndexIdaciRank' => 'Income Deprivation Affecting Children Index (IDACI) Rank (where 1 is most deprived)',
        'incomeDeprivationAffectingChildrenIndexIdaciDecile' => 'Income Deprivation Affecting Children Index (IDACI) Decile (where 1 is most deprived 10% of LSOAs)',
        'incomeDeprivationAffectingOlderPeopleIdaopiScore' => 'Income Deprivation Affecting Older People (IDAOPI) Score (rate)',
        'incomeDeprivationAffectingOlderPeopleIdaopiRank' => 'Income Deprivation Affecting Older People (IDAOPI) Rank (where 1 is most deprived)',
        'incomeDeprivationAffectingOlderPeopleIdaopiDecile' => 'Income Deprivation Affecting Older People (IDAOPI) Decile (where 1 is most deprived 10% of LSOAs)',
        'childrenAndYoungPeopleSubDomainScore' => 'Children and Young People Sub-domain Score',
        'childrenAndYoungPeopleSubDomainRank' => 'Children and Young People Sub-domain Rank (where 1 is most deprived)',
        'childrenAndYoungPeopleSubDomainDecile' => 'Children and Young People Sub-domain Decile (where 1 is most deprived 10% of LSOAs)',
        'adultSkillsSubDomainScore' => 'Adult Skills Sub-domain Score',
        'adultSkillsSubDomainRank' => 'Adult Skills Sub-domain Rank (where 1 is most deprived)',
        'adultSkillsSubDomainDecile' => 'Adult Skills Sub-domain Decile (where 1 is most deprived 10% of LSOAs)',
        'geographicalBarriersSubDomainScore' => 'Geographical Barriers Sub-domain Score',
        'geographicalBarriersSubDomainRank' => 'Geographical Barriers Sub-domain Rank (where 1 is most deprived)',
        'geographicalBarriersSubDomainDecile' => 'Geographical Barriers Sub-domain Decile (where 1 is most deprived 10% of LSOAs)',
        'widerBarriersSubDomainScore' => 'Wider Barriers Sub-domain Score',
        'widerBarriersSubDomainRank' => 'Wider Barriers Sub-domain Rank (where 1 is most deprived)',
        'widerBarriersSubDomainDecile' => 'Wider Barriers Sub-domain Decile (where 1 is most deprived 10% of LSOAs)',
        'indoorsSubDomainScore' => 'Indoors Sub-domain Score',
        'indoorsSubDomainRank' => 'Indoors Sub-domain Rank (where 1 is most deprived)',
        'indoorsSubDomainDecile' => 'Indoors Sub-domain Decile (where 1 is most deprived 10% of LSOAs)',
        'outdoorsSubDomainScore' => 'Outdoors Sub-domain Score',
        'outdoorsSubDomainRank' => 'Outdoors Sub-domain Rank (where 1 is most deprived)',
        'outdoorsSubDomainDecile' => 'Outdoors Sub-domain Decile (where 1 is most deprived 10% of LSOAs)',
        'totalPopulationMid2015ExcludingPrisoners' => 'Total population: mid 2015 (excluding prisoners)',
        'dependentChildrenAged015Mid2015ExcludingPrisoners' => 'Dependent Children aged 0-15: mid 2015 (excluding prisoners)',
        'populationAged1659Mid2015ExcludingPrisoners' => 'Population aged 16-59: mid 2015 (excluding prisoners)',
        'olderPopulationAged60AndOverMid2015ExcludingPrisoners' => 'Older population aged 60 and over: mid 2015 (excluding prisoners)',
        'workingAgePopulation185964ForUseWithEmploymentDeprivationDomainExcludingPrisoners' => 'Working age population 18-59/64: for use with Employment Deprivation Domain (excluding prisoners)',
    ];

    #[SerializedName(DeprivationData::FIELDS['lsoaCode'])]
    public string $lsoaCode;

    #[SerializedName(DeprivationData::FIELDS['lsoaName'])]
    public string $lsoaName;

    #[SerializedName(DeprivationData::FIELDS['localAuthorityDistrictCode'])]
    public string $localAuthorityDistrictCode;

    #[SerializedName('Local Authority District name (2019)')]
    public string $localAuthorityDistrictName;

    #[SerializedName('Index of Multiple Deprivation (IMD) Score')]
    public string $imdScore;

    #[SerializedName('Index of Multiple Deprivation (IMD) Rank (where 1 is most deprived)')]
    public string $imdRank;

    #[SerializedName('Index of Multiple Deprivation (IMD) Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $imdDecile;

    #[SerializedName('Income Score (rate)')]
    public string $incomeScore;

    #[SerializedName('Income Rank (where 1 is most deprived)')]
    public string $incomeRank;

    #[SerializedName('Income Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $incomeDecile;

    #[SerializedName('Employment Score (rate)')]
    public string $employmentScore;

    #[SerializedName('Employment Rank (where 1 is most deprived)')]
    public string $employmentRank;

    #[SerializedName('Employment Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $employmentDecile;

    #[SerializedName('Education, Skills and Training Score')]
    public string $educationSkillsAndTrainingScore;

    #[SerializedName('Education, Skills and Training Rank (where 1 is most deprived)')]
    public string $educationSkillsAndTrainingRank;

    #[SerializedName('Education, Skills and Training Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $educationSkillsAndTrainingDecile;

    #[SerializedName('Health Deprivation and Disability Score')]
    public string $healthDeprivationAndDisabilityScore;

    #[SerializedName('Health Deprivation and Disability Rank (where 1 is most deprived)')]
    public string $healthDeprivationAndDisabilityRank;

    #[SerializedName('Health Deprivation and Disability Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $healthDeprivationAndDisabilityDecile;

    #[SerializedName('Crime Score')]
    public string $crimeScore;

    #[SerializedName('Crime Rank (where 1 is most deprived)')]
    public string $crimeRank;

    #[SerializedName('Crime Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $crimeDecile;

    #[SerializedName('Barriers to Housing and Services Score')]
    public string $barriersToHousingAndServicesScore;

    #[SerializedName('Barriers to Housing and Services Rank (where 1 is most deprived)')]
    public string $barriersToHousingAndServicesRank;

    #[SerializedName('Barriers to Housing and Services Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $barriersToHousingAndServicesDecile;

    #[SerializedName('Living Environment Score')]
    public string $livingEnvironmentScore;

    #[SerializedName('Living Environment Rank (where 1 is most deprived)')]
    public string $livingEnvironmentRank;

    #[SerializedName('Living Environment Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $livingEnvironmentDecile;

    #[SerializedName('Income Deprivation Affecting Children Index (IDACI) Score (rate)')]
    public string $incomeDeprivationAffectingChildrenIndexIdaciScore;

    #[SerializedName('Income Deprivation Affecting Children Index (IDACI) Rank (where 1 is most deprived)')]
    public string $incomeDeprivationAffectingChildrenIndexIdaciRank;

    #[SerializedName('Income Deprivation Affecting Children Index (IDACI) Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $incomeDeprivationAffectingChildrenIndexIdaciDecile;

    #[SerializedName('Income Deprivation Affecting Older People (IDAOPI) Score (rate)')]
    public string $incomeDeprivationAffectingOlderPeopleIdaopiScore;

    #[SerializedName('Income Deprivation Affecting Older People (IDAOPI) Rank (where 1 is most deprived)')]
    public string $incomeDeprivationAffectingOlderPeopleIdaopiRank;

    #[SerializedName('Income Deprivation Affecting Older People (IDAOPI) Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $incomeDeprivationAffectingOlderPeopleIdaopiDecile;

    #[SerializedName('Children and Young People Sub-domain Score')]
    public string $childrenAndYoungPeopleSubDomainScore;

    #[SerializedName('Children and Young People Sub-domain Rank (where 1 is most deprived)')]
    public string $childrenAndYoungPeopleSubDomainRank;

    #[SerializedName('Children and Young People Sub-domain Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $childrenAndYoungPeopleSubDomainDecile;

    #[SerializedName('Adult Skills Sub-domain Score')]
    public string $adultSkillsSubDomainScore;

    #[SerializedName('Adult Skills Sub-domain Rank (where 1 is most deprived)')]
    public string $adultSkillsSubDomainRank;

    #[SerializedName('Adult Skills Sub-domain Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $adultSkillsSubDomainDecile;

    #[SerializedName('Geographical Barriers Sub-domain Score')]
    public string $geographicalBarriersSubDomainScore;

    #[SerializedName('Geographical Barriers Sub-domain Rank (where 1 is most deprived)')]
    public string $geographicalBarriersSubDomainRank;

    #[SerializedName('Geographical Barriers Sub-domain Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $geographicalBarriersSubDomainDecile;

    #[SerializedName('Wider Barriers Sub-domain Score')]
    public string $widerBarriersSubDomainScore;

    #[SerializedName('Wider Barriers Sub-domain Rank (where 1 is most deprived)')]
    public string $widerBarriersSubDomainRank;

    #[SerializedName('Wider Barriers Sub-domain Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $widerBarriersSubDomainDecile;

    #[SerializedName('Indoors Sub-domain Score')]
    public string $indoorsSubDomainScore;

    #[SerializedName('Indoors Sub-domain Rank (where 1 is most deprived)')]
    public string $indoorsSubDomainRank;

    #[SerializedName('Indoors Sub-domain Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $indoorsSubDomainDecile;

    #[SerializedName('Outdoors Sub-domain Score')]
    public string $outdoorsSubDomainScore;

    #[SerializedName('Outdoors Sub-domain Rank (where 1 is most deprived)')]
    public string $outdoorsSubDomainRank;

    #[SerializedName('Outdoors Sub-domain Decile (where 1 is most deprived 10% of LSOAs)')]
    public string $outdoorsSubDomainDecile;

    #[SerializedName('Total population: mid 2015 (excluding prisoners)')]
    public string $totalPopulationMid2015ExcludingPrisoners;

    #[SerializedName('Dependent Children aged 0-15: mid 2015 (excluding prisoners)')]
    public string $dependentChildrenAged015Mid2015ExcludingPrisoners;

    #[SerializedName('Population aged 16-59: mid 2015 (excluding prisoners)')]
    public string $populationAged1659Mid2015ExcludingPrisoners;

    #[SerializedName('Older population aged 60 and over: mid 2015 (excluding prisoners)')]
    public string $olderPopulationAged60AndOverMid2015ExcludingPrisoners;

    #[SerializedName('Working age population 18-59/64: for use with Employment Deprivation Domain (excluding prisoners)')]
    public string $workingAgePopulation185964ForUseWithEmploymentDeprivationDomainExcludingPrisoners;

    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, $offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$offset};
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }
}
