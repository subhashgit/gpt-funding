<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230714153525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questionnaire_answer ADD grant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE questionnaire_answer DROP first_name');
        $this->addSql('ALTER TABLE questionnaire_answer DROP last_name');
        $this->addSql('ALTER TABLE questionnaire_answer DROP email');
        $this->addSql('ALTER TABLE questionnaire_answer ADD CONSTRAINT FK_437B451C5C0C89F3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_437B451C5C0C89F3 ON questionnaire_answer (grant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questionnaire_answer DROP CONSTRAINT FK_437B451C5C0C89F3');
        $this->addSql('DROP INDEX IDX_437B451C5C0C89F3');
        $this->addSql('ALTER TABLE questionnaire_answer ADD first_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE questionnaire_answer ADD last_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE questionnaire_answer ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE questionnaire_answer DROP grant_id');
    }
}
