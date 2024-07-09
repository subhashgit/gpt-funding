<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719201052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_likes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE funding_party (funding_id INT NOT NULL, party_id INT NOT NULL, PRIMARY KEY(funding_id, party_id))');
        $this->addSql('CREATE INDEX IDX_EFA58139D70482 ON funding_party (funding_id)');
        $this->addSql('CREATE INDEX IDX_EFA5813213C1059 ON funding_party (party_id)');
        $this->addSql('CREATE TABLE user_likes (id INT NOT NULL, user_id INT NOT NULL, funding_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AB08B525A76ED395 ON user_likes (user_id)');
        $this->addSql('CREATE INDEX IDX_AB08B5259D70482 ON user_likes (funding_id)');
        $this->addSql('ALTER TABLE funding_party ADD CONSTRAINT FK_EFA58139D70482 FOREIGN KEY (funding_id) REFERENCES funding (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE funding_party ADD CONSTRAINT FK_EFA5813213C1059 FOREIGN KEY (party_id) REFERENCES party (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B525A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B5259D70482 FOREIGN KEY (funding_id) REFERENCES funding (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE funding ADD tags jsonb DEFAULT NULL');
        $this->addSql('ALTER TABLE funding ADD procurement_method VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE funding ADD procurement_method_details VARCHAR(500) DEFAULT NULL');
        $this->addSql('ALTER TABLE funding ADD main_procurement_category VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE funding ALTER description DROP NOT NULL');
        $this->addSql('ALTER TABLE funding ALTER closing_date DROP NOT NULL');
        $this->addSql('ALTER TABLE lot ADD funding_id INT NOT NULL');
        $this->addSql('ALTER TABLE lot ADD has_options BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE lot ADD status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lot ADD contract_period jsonb DEFAULT NULL');
        $this->addSql('ALTER TABLE lot ADD submission_terms jsonb DEFAULT NULL');
        $this->addSql('ALTER TABLE lot ADD award_criteria JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE lot ADD has_renewal BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE lot ADD renewal JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291B9D70482 FOREIGN KEY (funding_id) REFERENCES funding (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B81291B9D70482 ON lot (funding_id)');
        $this->addSql('ALTER TABLE party DROP CONSTRAINT fk_89954ee09d70482');
        $this->addSql('DROP INDEX idx_89954ee09d70482');
        $this->addSql('ALTER TABLE party DROP funding_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE user_likes_id_seq CASCADE');
        $this->addSql('ALTER TABLE funding_party DROP CONSTRAINT FK_EFA58139D70482');
        $this->addSql('ALTER TABLE funding_party DROP CONSTRAINT FK_EFA5813213C1059');
        $this->addSql('ALTER TABLE user_likes DROP CONSTRAINT FK_AB08B525A76ED395');
        $this->addSql('ALTER TABLE user_likes DROP CONSTRAINT FK_AB08B5259D70482');
        $this->addSql('DROP TABLE funding_party');
        $this->addSql('DROP TABLE user_likes');
        $this->addSql('ALTER TABLE party ADD funding_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT fk_89954ee09d70482 FOREIGN KEY (funding_id) REFERENCES funding (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_89954ee09d70482 ON party (funding_id)');
        $this->addSql('ALTER TABLE lot DROP CONSTRAINT FK_B81291B9D70482');
        $this->addSql('DROP INDEX IDX_B81291B9D70482');
        $this->addSql('ALTER TABLE lot DROP funding_id');
        $this->addSql('ALTER TABLE lot DROP has_options');
        $this->addSql('ALTER TABLE lot DROP status');
        $this->addSql('ALTER TABLE lot DROP contract_period');
        $this->addSql('ALTER TABLE lot DROP submission_terms');
        $this->addSql('ALTER TABLE lot DROP award_criteria');
        $this->addSql('ALTER TABLE lot DROP has_renewal');
        $this->addSql('ALTER TABLE lot DROP renewal');
        $this->addSql('ALTER TABLE funding DROP tags');
        $this->addSql('ALTER TABLE funding DROP procurement_method');
        $this->addSql('ALTER TABLE funding DROP procurement_method_details');
        $this->addSql('ALTER TABLE funding DROP main_procurement_category');
        $this->addSql('ALTER TABLE funding ALTER description SET NOT NULL');
        $this->addSql('ALTER TABLE funding ALTER closing_date SET NOT NULL');
    }
}
