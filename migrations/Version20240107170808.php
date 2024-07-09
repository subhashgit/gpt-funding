<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240107170808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tariff DROP price');
        $this->addSql('ALTER TABLE tariff DROP popular');
        $this->addSql('ALTER TABLE tariff DROP points');
        $this->addSql('ALTER TABLE user_tariff ADD period VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tariff ADD price INT NOT NULL');
        $this->addSql('ALTER TABLE tariff ADD popular BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE tariff ADD points INT NOT NULL');
        $this->addSql('ALTER TABLE user_tariff DROP period');
    }
}
