<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511111128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE project_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE project_message (id INT NOT NULL, request_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_20A33C1A427EB8A5 ON project_message (request_id)');
        $this->addSql('CREATE TABLE project_request (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE project_message ADD CONSTRAINT FK_20A33C1A427EB8A5 FOREIGN KEY (request_id) REFERENCES project_request (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ALTER request_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE project_message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_request_id_seq CASCADE');
        $this->addSql('ALTER TABLE project_message DROP CONSTRAINT FK_20A33C1A427EB8A5');
        $this->addSql('DROP TABLE project_message');
        $this->addSql('DROP TABLE project_request');
        $this->addSql('ALTER TABLE message ALTER request_id DROP NOT NULL');
    }
}
