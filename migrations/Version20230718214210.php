<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230718214210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE lot_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE lot (id INT NOT NULL, description TEXT DEFAULT NULL, value DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE funding ALTER start_date DROP NOT NULL');
        $this->addSql('ALTER TABLE funding ALTER end_date DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE lot_id_seq CASCADE');
        $this->addSql('DROP TABLE lot');
        $this->addSql('ALTER TABLE funding ALTER start_date SET NOT NULL');
        $this->addSql('ALTER TABLE funding ALTER end_date SET NOT NULL');
    }
}
