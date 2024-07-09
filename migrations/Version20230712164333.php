<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712164333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_filter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_filter (id INT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, type_id INT NOT NULL, notification_type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1A964420A76ED395 ON user_filter (user_id)');
        $this->addSql('CREATE TABLE user_notification (id INT NOT NULL, user_id INT NOT NULL, grant_id INT DEFAULT NULL, funding_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, notified BOOLEAN DEFAULT false NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3F980AC85C0C89F3 ON user_notification (grant_id)');
        $this->addSql('CREATE INDEX IDX_3F980AC8A76ED395 ON user_notification (user_id)');
        $this->addSql('CREATE INDEX IDX_3F980AC89D70482 ON user_notification (funding_id)');
        $this->addSql('CREATE INDEX user_grant_notifies_idx ON user_notification (notified, grant_id)');
        $this->addSql('ALTER TABLE user_filter ADD CONSTRAINT FK_1A964420A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_notification ADD CONSTRAINT FK_3F980AC85C0C89F3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_notification ADD CONSTRAINT FK_3F980AC8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_notification ADD CONSTRAINT FK_3F980AC89D70482 FOREIGN KEY (funding_id) REFERENCES funding (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE funding ALTER value_low TYPE BIGINT');
        $this->addSql('ALTER TABLE funding ALTER value_high TYPE BIGINT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE user_filter_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_notification_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_filter DROP CONSTRAINT FK_1A964420A76ED395');
        $this->addSql('ALTER TABLE user_notification DROP CONSTRAINT FK_3F980AC85C0C89F3');
        $this->addSql('ALTER TABLE user_notification DROP CONSTRAINT FK_3F980AC8A76ED395');
        $this->addSql('ALTER TABLE user_notification DROP CONSTRAINT FK_3F980AC89D70482');
        $this->addSql('DROP TABLE user_filter');
        $this->addSql('DROP TABLE user_notification');
        $this->addSql('ALTER TABLE funding ALTER value_low TYPE INT');
        $this->addSql('ALTER TABLE funding ALTER value_high TYPE INT');
    }
}
