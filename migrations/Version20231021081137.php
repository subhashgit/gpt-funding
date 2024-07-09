<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231021081137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE all_io_d2019_scores_ranks_deciles_population_denominators_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE all_io_d2019_scores_ranks_deciles_population_denominators (id INT NOT NULL, lsoa_code VARCHAR(255) DEFAULT NULL, lsoa_name VARCHAR(255) DEFAULT NULL, local_authority_district_code VARCHAR(255) DEFAULT NULL, local_authority_district_name VARCHAR(255) DEFAULT NULL, imd_score VARCHAR(255) DEFAULT NULL, imd_rank VARCHAR(255) DEFAULT NULL, imd_decile VARCHAR(255) DEFAULT NULL, income_score VARCHAR(255) DEFAULT NULL, income_rank VARCHAR(255) DEFAULT NULL, income_decile VARCHAR(255) DEFAULT NULL, employment_score VARCHAR(255) DEFAULT NULL, employment_decile VARCHAR(255) DEFAULT NULL, education_skills_and_training_score VARCHAR(255) DEFAULT NULL, education_skills_and_training_rank VARCHAR(255) DEFAULT NULL, education_skills_and_training_decile VARCHAR(255) DEFAULT NULL, health_deprivation_and_disability_score VARCHAR(255) DEFAULT NULL, health_deprivation_and_disability_rank VARCHAR(255) DEFAULT NULL, health_deprivation_and_disability_decile VARCHAR(255) DEFAULT NULL, crime_score VARCHAR(255) DEFAULT NULL, crime_rank VARCHAR(255) DEFAULT NULL, crime_decile VARCHAR(255) DEFAULT NULL, barriers_to_housing_and_services_score VARCHAR(255) DEFAULT NULL, barriers_to_housing_and_services_rank VARCHAR(255) DEFAULT NULL, barriers_to_housing_and_services_decile VARCHAR(255) DEFAULT NULL, living_environment_score VARCHAR(255) DEFAULT NULL, living_environment_rank VARCHAR(255) DEFAULT NULL, living_environment_decile VARCHAR(255) DEFAULT NULL, income_deprivation_affecting_children_index_idaci_score VARCHAR(255) DEFAULT NULL, income_deprivation_affecting_children_index_idaci_rank VARCHAR(255) DEFAULT NULL, income_deprivation_affecting_children_index_idaci_decile VARCHAR(255) DEFAULT NULL, income_deprivation_affecting_older_people_idaopi_score VARCHAR(255) DEFAULT NULL, income_deprivation_affecting_older_people_idaopi_rank VARCHAR(255) DEFAULT NULL, income_deprivation_affecting_older_people_idaopi_decile VARCHAR(255) DEFAULT NULL, children_and_young_people_sub_domain_score VARCHAR(255) DEFAULT NULL, children_and_young_people_sub_domain_rank VARCHAR(255) DEFAULT NULL, children_and_young_people_sub_domain_decile VARCHAR(255) DEFAULT NULL, adult_skills_sub_domain_score VARCHAR(255) DEFAULT NULL, adult_skills_sub_domain_rank VARCHAR(255) DEFAULT NULL, adult_skills_sub_domain_decile VARCHAR(255) DEFAULT NULL, geographical_barriers_sub_domain_score VARCHAR(255) DEFAULT NULL, geographical_barriers_sub_domain_rank VARCHAR(255) DEFAULT NULL, geographical_barriers_sub_domain_decile VARCHAR(255) DEFAULT NULL, wider_barriers_sub_domain_score VARCHAR(255) DEFAULT NULL, wider_barriers_sub_domain_rank VARCHAR(255) DEFAULT NULL, wider_barriers_sub_domain_decile VARCHAR(255) DEFAULT NULL, indoors_sub_domain_score VARCHAR(255) DEFAULT NULL, indoors_sub_domain_rank VARCHAR(255) DEFAULT NULL, indoors_sub_domain_decile VARCHAR(255) DEFAULT NULL, outdoors_sub_domain_score VARCHAR(255) DEFAULT NULL, outdoors_sub_domain_rank VARCHAR(255) DEFAULT NULL, outdoors_sub_domain_decile VARCHAR(255) DEFAULT NULL, total_population_mid2015_excluding_prisoners VARCHAR(255) DEFAULT NULL, dependent_children_aged015_mid2015_excluding_prisoners VARCHAR(255) DEFAULT NULL, population_aged1659_mid2015_excluding_prisoners VARCHAR(255) DEFAULT NULL, older_population_aged60_and_over_mid2015_excluding_prisoners VARCHAR(255) DEFAULT NULL, employment_deprivation_domain_excluding_prisoners VARCHAR(255) DEFAULT NULL, employment_rank VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE all_io_d2019_scores_ranks_deciles_population_denominators_id_seq CASCADE');
        $this->addSql('DROP TABLE all_io_d2019_scores_ranks_deciles_population_denominators');
    }
}
