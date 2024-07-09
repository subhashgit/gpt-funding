<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230810115512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "grant" ALTER closing_date TYPE DATE');
        $this->addSql('COMMENT ON COLUMN "grant".closing_date IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "grant" ALTER closing_date TYPE DATE');
        $this->addSql('COMMENT ON COLUMN "grant".closing_date IS \'(DC2Type:date_immutable)\'');
    }
}
