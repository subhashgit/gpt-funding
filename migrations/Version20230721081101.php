<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230721081101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_likes ADD grant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_likes ALTER funding_id DROP NOT NULL');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B5255C0C89F3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB08B5255C0C89F3 ON user_likes (grant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_likes DROP CONSTRAINT FK_AB08B5255C0C89F3');
        $this->addSql('DROP INDEX IDX_AB08B5255C0C89F3');
        $this->addSql('ALTER TABLE user_likes DROP grant_id');
        $this->addSql('ALTER TABLE user_likes ALTER funding_id SET NOT NULL');
    }
}
