<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230724181023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questionnaire_answer ADD prompt_tokens INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE questionnaire_answer ADD completion_tokens INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE questionnaire_answer ADD total_tokens INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE questionnaire_answer ADD points_consumed INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE questionnaire_answer ADD estimated_price DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questionnaire_answer DROP prompt_tokens');
        $this->addSql('ALTER TABLE questionnaire_answer DROP completion_tokens');
        $this->addSql('ALTER TABLE questionnaire_answer DROP total_tokens');
        $this->addSql('ALTER TABLE questionnaire_answer DROP points_consumed');
        $this->addSql('ALTER TABLE questionnaire_answer DROP estimated_price');
    }
}
