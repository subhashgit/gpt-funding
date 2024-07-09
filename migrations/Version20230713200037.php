<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713200037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "grant" ADD closing_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE "grant" DROP deadline');
        $this->addSql('COMMENT ON COLUMN "grant".closing_date IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "grant" ADD deadline TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE "grant" DROP closing_date');
        $this->addSql('COMMENT ON COLUMN "grant".deadline IS \'(DC2Type:datetime_immutable)\'');
    }
}
