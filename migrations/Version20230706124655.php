<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706124655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE grant_open_to_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE grant_grant_open_to (grant_id INT NOT NULL, grant_open_to_id INT NOT NULL, PRIMARY KEY(grant_id, grant_open_to_id))');
        $this->addSql('CREATE INDEX IDX_EEBC2D855C0C89F3 ON grant_grant_open_to (grant_id)');
        $this->addSql('CREATE INDEX IDX_EEBC2D8537167C57 ON grant_grant_open_to (grant_open_to_id)');
        $this->addSql('CREATE TABLE grant_open_to (id INT NOT NULL, open_to VARCHAR(500) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE grant_grant_open_to ADD CONSTRAINT FK_EEBC2D855C0C89F3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_grant_open_to ADD CONSTRAINT FK_EEBC2D8537167C57 FOREIGN KEY (grant_open_to_id) REFERENCES grant_open_to (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "grant" DROP open_to');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE grant_open_to_id_seq CASCADE');
        $this->addSql('ALTER TABLE grant_grant_open_to DROP CONSTRAINT FK_EEBC2D855C0C89F3');
        $this->addSql('ALTER TABLE grant_grant_open_to DROP CONSTRAINT FK_EEBC2D8537167C57');
        $this->addSql('DROP TABLE grant_grant_open_to');
        $this->addSql('DROP TABLE grant_open_to');
        $this->addSql('ALTER TABLE "grant" ADD open_to TEXT DEFAULT NULL');
    }
}
