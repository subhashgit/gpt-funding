<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230803091318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_company DROP CONSTRAINT fk_17b2174567b3b43d');
        $this->addSql('DROP INDEX idx_17b2174567b3b43d');
        $this->addSql('ALTER TABLE user_company RENAME COLUMN users_id TO user_id');
        $this->addSql('ALTER TABLE user_company ADD CONSTRAINT FK_17B21745A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_17B21745A76ED395 ON user_company (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_company DROP CONSTRAINT FK_17B21745A76ED395');
        $this->addSql('DROP INDEX IDX_17B21745A76ED395');
        $this->addSql('ALTER TABLE user_company RENAME COLUMN user_id TO users_id');
        $this->addSql('ALTER TABLE user_company ADD CONSTRAINT fk_17b2174567b3b43d FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_17b2174567b3b43d ON user_company (users_id)');
    }
}
