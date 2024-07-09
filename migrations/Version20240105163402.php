<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240105163402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE payment_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_tariff_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, user_id INT NOT NULL, session_id VARCHAR(255) NOT NULL, amount_total INT NOT NULL, amount_subtotal INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D1A76ED395 ON transaction (user_id)');
        $this->addSql('CREATE TABLE user_tariff (id INT NOT NULL, tariff_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price_id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F1373B4092348FD2 ON user_tariff (tariff_id)');
        $this->addSql('CREATE INDEX IDX_F1373B40A76ED395 ON user_tariff (user_id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_tariff ADD CONSTRAINT FK_F1373B4092348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_tariff ADD CONSTRAINT FK_F1373B40A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT fk_6d28840d92348fd2');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_token');
        $this->addSql('ALTER TABLE tariff ADD product_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tariff ADD period VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tariff ADD month_price VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tariff ADD year_price VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD tariff_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD customer_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64992348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D64992348FD2 ON "user" (tariff_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE transaction_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_tariff_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payment (id INT NOT NULL, tariff_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, client_email VARCHAR(255) DEFAULT NULL, client_id VARCHAR(255) DEFAULT NULL, total_amount INT DEFAULT NULL, currency_code VARCHAR(255) DEFAULT NULL, details JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6d28840d92348fd2 ON payment (tariff_id)');
        $this->addSql('CREATE TABLE payment_token (hash VARCHAR(255) NOT NULL, details TEXT DEFAULT NULL, after_url TEXT DEFAULT NULL, target_url TEXT NOT NULL, gateway_name VARCHAR(255) NOT NULL, PRIMARY KEY(hash))');
        $this->addSql('COMMENT ON COLUMN payment_token.details IS \'(DC2Type:object)\'');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT fk_6d28840d92348fd2 FOREIGN KEY (tariff_id) REFERENCES tariff (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1A76ED395');
        $this->addSql('ALTER TABLE user_tariff DROP CONSTRAINT FK_F1373B4092348FD2');
        $this->addSql('ALTER TABLE user_tariff DROP CONSTRAINT FK_F1373B40A76ED395');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user_tariff');
        $this->addSql('ALTER TABLE tariff DROP product_id');
        $this->addSql('ALTER TABLE tariff DROP period');
        $this->addSql('ALTER TABLE tariff DROP month_price');
        $this->addSql('ALTER TABLE tariff DROP year_price');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64992348FD2');
        $this->addSql('DROP INDEX IDX_8D93D64992348FD2');
        $this->addSql('ALTER TABLE "user" DROP tariff_id');
        $this->addSql('ALTER TABLE "user" DROP customer_id');
    }
}
