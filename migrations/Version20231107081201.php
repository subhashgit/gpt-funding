<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107081201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE all_io_d2019_scores_ranks_deciles_population_denominators_id_se CASCADE');
        $this->addSql('CREATE SEQUENCE all_io_d2019_scores_ranks_deciles_population_denominators_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE user_notification ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE user_notification ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE all_io_d2019_scores_ranks_deciles_population_denominators_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE all_io_d2019_scores_ranks_deciles_population_denominators_id_se INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE user_notification DROP created_at');
        $this->addSql('ALTER TABLE user_notification DROP updated_at');
    }
}
