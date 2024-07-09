<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731150356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE EXTENSION IF NOT EXISTS vector');

        $this->addSql('CREATE SEQUENCE embedding_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE embedding (id INT NOT NULL, vector vector(1536) DEFAULT NULL, entity_id INT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_485B55AE81257D5D ON embedding (entity_id)');
        $this->addSql('CREATE TABLE user_company (id INT NOT NULL, users_id INT NOT NULL, description TEXT DEFAULT NULL, tags JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_17B2174567B3B43D ON user_company (users_id)');
        $this->addSql('ALTER TABLE user_company ADD CONSTRAINT FK_17B2174567B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE embedding_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_company_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_company DROP CONSTRAINT FK_17B2174567B3B43D');
        $this->addSql('DROP TABLE embedding');
        $this->addSql('DROP TABLE user_company');
        $this->addSql('DROP EXTENSION IF EXISTS vector');
    }
}
