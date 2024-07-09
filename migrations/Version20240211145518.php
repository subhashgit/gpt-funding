<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Psr\Log\LoggerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211145518 extends AbstractMigration
{
    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        parent::__construct($connection, $logger);
    }

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74');
        $this->addSql('ALTER TABLE "user" ADD username_canonical VARCHAR(180)');
        $this->addSql('update "user" set username_canonical = lower(email)');

        $this->addSql('ALTER TABLE "user" alter username_canonical set NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD email_canonical VARCHAR(180)');
        $this->addSql('update "user" set email_canonical = lower(email)');
        $this->addSql('ALTER TABLE "user" alter email_canonical set NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD enabled BOOLEAN');
        $this->addSql('update "user" set enabled = true');
        $this->addSql('ALTER TABLE "user" alter enabled set NOT NULL');

        $this->addSql('ALTER TABLE "user" ADD salt VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD confirmation_token VARCHAR(180) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');

        $q = $this->connection->executeQuery('select * from "user"')->fetchAllAssociative();

        $this->addSql('ALTER TABLE "user" ALTER roles TYPE TEXT');

        foreach ($q as $item) {
            $array = explode(',', $item['roles']);
            $this->addSql('update "user" set "roles" = \''.serialize($array)."' where id = ".$item['id']);
        }

        $this->addSql('update "user" set username=lower(email)');
        $this->addSql('ALTER TABLE "user" ALTER username SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER username TYPE VARCHAR(180)');

        $this->addSql('update "user" set created_at = now()');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET NOT NULL');
        $this->addSql('update "user" set updated_at = now()');
        $this->addSql('ALTER TABLE "user" ALTER updated_at SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64992FC23A8 ON "user" (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A0D96FBF ON "user" (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C05FB297 ON "user" (confirmation_token)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D64992FC23A8');
        $this->addSql('DROP INDEX UNIQ_8D93D649A0D96FBF');
        $this->addSql('DROP INDEX UNIQ_8D93D649C05FB297');
        $this->addSql('ALTER TABLE "user" DROP username_canonical');
        $this->addSql('ALTER TABLE "user" DROP email_canonical');
        $this->addSql('ALTER TABLE "user" DROP enabled');
        $this->addSql('ALTER TABLE "user" DROP salt');
        $this->addSql('ALTER TABLE "user" DROP last_login');
        $this->addSql('ALTER TABLE "user" DROP confirmation_token');
        $this->addSql('ALTER TABLE "user" DROP password_requested_at');
        $this->addSql('ALTER TABLE "user" ALTER username DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER username TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER roles TYPE TEXT');

        $q = $this->connection->executeQuery('select * from "user"')->fetchAllAssociative();

        foreach ($q as $user) {
            $array = array_map(fn ($i) => $i, unserialize($user['roles']));
            $this->addSql('update "user" set "roles" = \''.implode(',', $array)."' where id = ".$user['id']);
        }

        $this->addSql('ALTER TABLE "user" ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER updated_at DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:simple_array)\'');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
    }
}
