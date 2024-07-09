<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130153013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ALTER roles TYPE TEXT');
        $this->addSql("update \"user\" set roles = replace(roles,'\"','') where true;");
        $this->addSql("update \"user\" set roles = replace(roles,'[','') where true;");
        $this->addSql("update \"user\" set roles = replace(roles,']','') where true;");
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ALTER roles TYPE JSON USING roles::json');
        $this->addSql('COMMENT ON COLUMN "user".roles IS NULL');
    }
}
