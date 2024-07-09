<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230804103532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE suggestion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE suggestion (id INT NOT NULL, grant_id INT NOT NULL, user_id INT NOT NULL, description TEXT NOT NULL, prompt_tokens INT DEFAULT 0 NOT NULL, completion_tokens INT DEFAULT 0 NOT NULL, total_tokens INT DEFAULT 0 NOT NULL, points_consumed INT DEFAULT 0 NOT NULL, estimated_price DOUBLE PRECISION DEFAULT \'0\' NOT NULL, slug VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DD80F31B5C0C89F3 ON suggestion (grant_id)');
        $this->addSql('CREATE INDEX IDX_DD80F31BA76ED395 ON suggestion (user_id)');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31B5C0C89F3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE suggestion_id_seq CASCADE');
        $this->addSql('ALTER TABLE suggestion DROP CONSTRAINT FK_DD80F31B5C0C89F3');
        $this->addSql('ALTER TABLE suggestion DROP CONSTRAINT FK_DD80F31BA76ED395');
        $this->addSql('DROP TABLE suggestion');
    }
}
