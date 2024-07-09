<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815171430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE grant_grant_location (grant_id INT NOT NULL, grant_location_id INT NOT NULL, PRIMARY KEY(grant_id, grant_location_id))');
        $this->addSql('CREATE INDEX IDX_CDE9EF445C0C89F3 ON grant_grant_location (grant_id)');
        $this->addSql('CREATE INDEX IDX_CDE9EF4427B54B2D ON grant_grant_location (grant_location_id)');
        $this->addSql('CREATE TABLE grant_grant_category (grant_id INT NOT NULL, grant_category_id INT NOT NULL, PRIMARY KEY(grant_id, grant_category_id))');
        $this->addSql('CREATE INDEX IDX_953B7F4E5C0C89F3 ON grant_grant_category (grant_id)');
        $this->addSql('CREATE INDEX IDX_953B7F4E33BEF741 ON grant_grant_category (grant_category_id)');
        $this->addSql('ALTER TABLE grant_grant_location ADD CONSTRAINT FK_CDE9EF445C0C89F3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_grant_location ADD CONSTRAINT FK_CDE9EF4427B54B2D FOREIGN KEY (grant_location_id) REFERENCES grant_location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_grant_category ADD CONSTRAINT FK_953B7F4E5C0C89F3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_grant_category ADD CONSTRAINT FK_953B7F4E33BEF741 FOREIGN KEY (grant_category_id) REFERENCES grant_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_location_grant DROP CONSTRAINT fk_4813397227b54b2d');
        $this->addSql('ALTER TABLE grant_location_grant DROP CONSTRAINT fk_481339725c0c89f3');
        $this->addSql('ALTER TABLE grant_category_grant DROP CONSTRAINT fk_d67c331e33bef741');
        $this->addSql('ALTER TABLE grant_category_grant DROP CONSTRAINT fk_d67c331e5c0c89f3');

        $this->addSql('INSERT INTO grant_grant_location (grant_id, grant_location_id) SELECT grant_id, grant_location_id FROM grant_location_grant');
        $this->addSql('INSERT INTO grant_grant_category (grant_id, grant_category_id) SELECT grant_id, grant_category_id FROM grant_category_grant');

        $this->addSql('DROP TABLE grant_location_grant');
        $this->addSql('DROP TABLE grant_category_grant');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE grant_location_grant (grant_location_id INT NOT NULL, grant_id INT NOT NULL, PRIMARY KEY(grant_location_id, grant_id))');
        $this->addSql('CREATE INDEX idx_481339725c0c89f3 ON grant_location_grant (grant_id)');
        $this->addSql('CREATE INDEX idx_4813397227b54b2d ON grant_location_grant (grant_location_id)');
        $this->addSql('CREATE TABLE grant_category_grant (grant_category_id INT NOT NULL, grant_id INT NOT NULL, PRIMARY KEY(grant_category_id, grant_id))');
        $this->addSql('CREATE INDEX idx_d67c331e5c0c89f3 ON grant_category_grant (grant_id)');
        $this->addSql('CREATE INDEX idx_d67c331e33bef741 ON grant_category_grant (grant_category_id)');
        $this->addSql('ALTER TABLE grant_location_grant ADD CONSTRAINT fk_4813397227b54b2d FOREIGN KEY (grant_location_id) REFERENCES grant_location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_location_grant ADD CONSTRAINT fk_481339725c0c89f3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_category_grant ADD CONSTRAINT fk_d67c331e33bef741 FOREIGN KEY (grant_category_id) REFERENCES grant_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_category_grant ADD CONSTRAINT fk_d67c331e5c0c89f3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_grant_location DROP CONSTRAINT FK_CDE9EF445C0C89F3');
        $this->addSql('ALTER TABLE grant_grant_location DROP CONSTRAINT FK_CDE9EF4427B54B2D');
        $this->addSql('ALTER TABLE grant_grant_category DROP CONSTRAINT FK_953B7F4E5C0C89F3');
        $this->addSql('ALTER TABLE grant_grant_category DROP CONSTRAINT FK_953B7F4E33BEF741');

        $this->addSql('INSERT INTO grant_location_grant (grant_location_id, grant_id) SELECT grant_location_id, grant_id FROM grant_grant_location');
        $this->addSql('INSERT INTO grant_category_grant (grant_category_id, grant_id) SELECT grant_category_id, grant_id FROM grant_grant_category');

        $this->addSql('DROP TABLE grant_grant_location');
        $this->addSql('DROP TABLE grant_grant_category');
    }
}
