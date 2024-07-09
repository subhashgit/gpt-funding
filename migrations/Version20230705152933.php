<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705152933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cpvcode_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE funding_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE funding_region_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE grant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE grant_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE grant_location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE questionnaire_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE questionnaire_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reset_password_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tariff_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answer (id INT NOT NULL, questionnaire_answer_id INT DEFAULT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) DEFAULT NULL, gpt BOOLEAN DEFAULT false NOT NULL, instruction VARCHAR(1000) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DADD4A2539A48C93 ON answer (questionnaire_answer_id)');
        $this->addSql('CREATE TABLE cpvcode (id INT NOT NULL, code VARCHAR(15) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE funding (id INT NOT NULL, notice_identifier VARCHAR(255) NOT NULL, notice_type VARCHAR(255) NOT NULL, organisation_name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, published_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(500) NOT NULL, description TEXT NOT NULL, postcode VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) NOT NULL, contact_address1 VARCHAR(255) NOT NULL, contact_address2 VARCHAR(255) DEFAULT NULL, contact_town VARCHAR(255) NOT NULL, contact_postcode VARCHAR(255) NOT NULL, contact_country VARCHAR(255) NOT NULL, contact_telephone VARCHAR(255) DEFAULT NULL, contact_website VARCHAR(255) DEFAULT NULL, links JSON DEFAULT NULL, additional_text TEXT DEFAULT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, closing_date DATE NOT NULL, suitable_for_sme BOOLEAN DEFAULT NULL, suitable_for_vco BOOLEAN DEFAULT NULL, supply_chain BOOLEAN NOT NULL, ojeu_contract_type VARCHAR(255) DEFAULT NULL, value_low INT NOT NULL, value_high INT DEFAULT NULL, ojeu_procedure_type VARCHAR(255) DEFAULT NULL, closing_time TIME(0) WITHOUT TIME ZONE NOT NULL, external_link VARCHAR(500) DEFAULT NULL, external_id VARCHAR(255) DEFAULT NULL, downloads JSON DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D30DD1D69E041448 ON funding (notice_identifier)');
        $this->addSql('CREATE INDEX IDX_D30DD1D67B00651C ON funding (status)');
        $this->addSql('COMMENT ON COLUMN funding.published_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN funding.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE funding_cpvcode (funding_id INT NOT NULL, cpvcode_id INT NOT NULL, PRIMARY KEY(funding_id, cpvcode_id))');
        $this->addSql('CREATE INDEX IDX_C796FE6C9D70482 ON funding_cpvcode (funding_id)');
        $this->addSql('CREATE INDEX IDX_C796FE6CF970ADAE ON funding_cpvcode (cpvcode_id)');
        $this->addSql('CREATE TABLE funding_region (id INT NOT NULL, region VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE funding_region_funding (funding_region_id INT NOT NULL, funding_id INT NOT NULL, PRIMARY KEY(funding_region_id, funding_id))');
        $this->addSql('CREATE INDEX IDX_A3FB7FCE85E70635 ON funding_region_funding (funding_region_id)');
        $this->addSql('CREATE INDEX IDX_A3FB7FCE9D70482 ON funding_region_funding (funding_id)');
        $this->addSql('CREATE TABLE "grant" (id INT NOT NULL, title VARCHAR(500) NOT NULL, funder VARCHAR(500) DEFAULT NULL, description TEXT DEFAULT NULL, max_amount INT DEFAULT NULL, how_to_apply VARCHAR(500) DEFAULT NULL, deadline VARCHAR(255) DEFAULT NULL, funding_details TEXT DEFAULT NULL, who_can_apply TEXT DEFAULT NULL, open_to TEXT DEFAULT NULL, find_out_more TEXT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE grant_category (id INT NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE grant_category_grant (grant_category_id INT NOT NULL, grant_id INT NOT NULL, PRIMARY KEY(grant_category_id, grant_id))');
        $this->addSql('CREATE INDEX IDX_D67C331E33BEF741 ON grant_category_grant (grant_category_id)');
        $this->addSql('CREATE INDEX IDX_D67C331E5C0C89F3 ON grant_category_grant (grant_id)');
        $this->addSql('CREATE TABLE grant_location (id INT NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE grant_location_grant (grant_location_id INT NOT NULL, grant_id INT NOT NULL, PRIMARY KEY(grant_location_id, grant_id))');
        $this->addSql('CREATE INDEX IDX_4813397227B54B2D ON grant_location_grant (grant_location_id)');
        $this->addSql('CREATE INDEX IDX_481339725C0C89F3 ON grant_location_grant (grant_id)');
        $this->addSql('CREATE TABLE payment (id INT NOT NULL, tariff_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, client_email VARCHAR(255) DEFAULT NULL, client_id VARCHAR(255) DEFAULT NULL, total_amount INT DEFAULT NULL, currency_code VARCHAR(255) DEFAULT NULL, details JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6D28840D92348FD2 ON payment (tariff_id)');
        $this->addSql('CREATE TABLE payment_token (hash VARCHAR(255) NOT NULL, details TEXT DEFAULT NULL, after_url TEXT DEFAULT NULL, target_url TEXT NOT NULL, gateway_name VARCHAR(255) NOT NULL, PRIMARY KEY(hash))');
        $this->addSql('COMMENT ON COLUMN payment_token.details IS \'(DC2Type:object)\'');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, questionnaire_id INT NOT NULL, question VARCHAR(1000) NOT NULL, answer VARCHAR(1000) DEFAULT NULL, section VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, instruction VARCHAR(1000) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6F7494ECE07E8FF ON question (questionnaire_id)');
        $this->addSql('CREATE TABLE questionnaire (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE questionnaire_answer (id INT NOT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_437B451CA76ED395 ON questionnaire_answer (user_id)');
        $this->addSql('CREATE TABLE reset_password_request (id INT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('COMMENT ON COLUMN reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tariff (id INT NOT NULL, title VARCHAR(255) NOT NULL, price INT NOT NULL, popular BOOLEAN NOT NULL, points INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, points INT DEFAULT 0 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A2539A48C93 FOREIGN KEY (questionnaire_answer_id) REFERENCES questionnaire_answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE funding_cpvcode ADD CONSTRAINT FK_C796FE6C9D70482 FOREIGN KEY (funding_id) REFERENCES funding (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE funding_cpvcode ADD CONSTRAINT FK_C796FE6CF970ADAE FOREIGN KEY (cpvcode_id) REFERENCES cpvcode (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE funding_region_funding ADD CONSTRAINT FK_A3FB7FCE85E70635 FOREIGN KEY (funding_region_id) REFERENCES funding_region (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE funding_region_funding ADD CONSTRAINT FK_A3FB7FCE9D70482 FOREIGN KEY (funding_id) REFERENCES funding (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_category_grant ADD CONSTRAINT FK_D67C331E33BEF741 FOREIGN KEY (grant_category_id) REFERENCES grant_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_category_grant ADD CONSTRAINT FK_D67C331E5C0C89F3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_location_grant ADD CONSTRAINT FK_4813397227B54B2D FOREIGN KEY (grant_location_id) REFERENCES grant_location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_location_grant ADD CONSTRAINT FK_481339725C0C89F3 FOREIGN KEY (grant_id) REFERENCES "grant" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D92348FD2 FOREIGN KEY (tariff_id) REFERENCES tariff (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494ECE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE questionnaire_answer ADD CONSTRAINT FK_437B451CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cpvcode_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE funding_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE funding_region_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE grant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE grant_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE grant_location_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE questionnaire_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE questionnaire_answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reset_password_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tariff_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('ALTER TABLE answer DROP CONSTRAINT FK_DADD4A2539A48C93');
        $this->addSql('ALTER TABLE funding_cpvcode DROP CONSTRAINT FK_C796FE6C9D70482');
        $this->addSql('ALTER TABLE funding_cpvcode DROP CONSTRAINT FK_C796FE6CF970ADAE');
        $this->addSql('ALTER TABLE funding_region_funding DROP CONSTRAINT FK_A3FB7FCE85E70635');
        $this->addSql('ALTER TABLE funding_region_funding DROP CONSTRAINT FK_A3FB7FCE9D70482');
        $this->addSql('ALTER TABLE grant_category_grant DROP CONSTRAINT FK_D67C331E33BEF741');
        $this->addSql('ALTER TABLE grant_category_grant DROP CONSTRAINT FK_D67C331E5C0C89F3');
        $this->addSql('ALTER TABLE grant_location_grant DROP CONSTRAINT FK_4813397227B54B2D');
        $this->addSql('ALTER TABLE grant_location_grant DROP CONSTRAINT FK_481339725C0C89F3');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D92348FD2');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494ECE07E8FF');
        $this->addSql('ALTER TABLE questionnaire_answer DROP CONSTRAINT FK_437B451CA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE cpvcode');
        $this->addSql('DROP TABLE funding');
        $this->addSql('DROP TABLE funding_cpvcode');
        $this->addSql('DROP TABLE funding_region');
        $this->addSql('DROP TABLE funding_region_funding');
        $this->addSql('DROP TABLE "grant"');
        $this->addSql('DROP TABLE grant_category');
        $this->addSql('DROP TABLE grant_category_grant');
        $this->addSql('DROP TABLE grant_location');
        $this->addSql('DROP TABLE grant_location_grant');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_token');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE questionnaire');
        $this->addSql('DROP TABLE questionnaire_answer');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE tariff');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
