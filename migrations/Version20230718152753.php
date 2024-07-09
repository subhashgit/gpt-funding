<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230718152753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE party_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE party (id INT NOT NULL, funding_id INT DEFAULT NULL, name VARCHAR(500) NOT NULL, address jsonb DEFAULT NULL, contact_point jsonb DEFAULT NULL, roles jsonb DEFAULT NULL, external_id VARCHAR(255) NOT NULL, details jsonb DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_89954EE09D70482 ON party (funding_id)');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE09D70482 FOREIGN KEY (funding_id) REFERENCES funding (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE funding DROP closing_time');
        $this->addSql('ALTER TABLE funding DROP region');
        $this->addSql('ALTER TABLE funding DROP contact_name');
        $this->addSql('ALTER TABLE funding DROP contact_email');
        $this->addSql('ALTER TABLE funding DROP contact_address1');
        $this->addSql('ALTER TABLE funding DROP contact_address2');
        $this->addSql('ALTER TABLE funding DROP contact_town');
        $this->addSql('ALTER TABLE funding DROP contact_postcode');
        $this->addSql('ALTER TABLE funding DROP contact_country');
        $this->addSql('ALTER TABLE funding DROP contact_telephone');
        $this->addSql('ALTER TABLE funding DROP contact_website');
        $this->addSql('ALTER TABLE funding ALTER title TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE funding ALTER end_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE funding ALTER closing_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE funding ALTER value_low DROP NOT NULL');
        $this->addSql('ALTER TABLE funding ALTER external_link TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE funding ALTER slug TYPE VARCHAR(1000)');
        $this->addSql('COMMENT ON COLUMN funding.end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN funding.closing_date IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE party_id_seq CASCADE');
        $this->addSql('ALTER TABLE party DROP CONSTRAINT FK_89954EE09D70482');
        $this->addSql('DROP TABLE party');
        $this->addSql('ALTER TABLE funding ADD closing_time TIME(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE funding ADD region VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE funding ADD contact_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE funding ADD contact_email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE funding ADD contact_address1 VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE funding ADD contact_address2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE funding ADD contact_town VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE funding ADD contact_postcode VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE funding ADD contact_country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE funding ADD contact_telephone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE funding ADD contact_website VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE funding ALTER title TYPE VARCHAR(500)');
        $this->addSql('ALTER TABLE funding ALTER end_date TYPE DATE');
        $this->addSql('ALTER TABLE funding ALTER closing_date TYPE DATE');
        $this->addSql('ALTER TABLE funding ALTER value_low SET NOT NULL');
        $this->addSql('ALTER TABLE funding ALTER external_link TYPE VARCHAR(500)');
        $this->addSql('ALTER TABLE funding ALTER slug TYPE VARCHAR(500)');
        $this->addSql('COMMENT ON COLUMN funding.end_date IS NULL');
        $this->addSql('COMMENT ON COLUMN funding.closing_date IS NULL');
    }
}
