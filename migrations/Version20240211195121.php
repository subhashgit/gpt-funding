<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211195121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE revisions (id SERIAL NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE answer_audit (id INT NOT NULL, rev INT NOT NULL, questionnaire_answer_id INT DEFAULT NULL, question TEXT DEFAULT NULL, answer TEXT DEFAULT NULL, gpt BOOLEAN DEFAULT false, instruction TEXT DEFAULT NULL, denominators JSON DEFAULT NULL, prompt TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_c37401e9ba2a8f5bca26846c01a9d14e_idx ON answer_audit (rev)');
        $this->addSql('CREATE TABLE cpvcode_audit (id INT NOT NULL, rev INT NOT NULL, code VARCHAR(15) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_e3badc29c67fb4dd940baa51c756f3af_idx ON cpvcode_audit (rev)');
        $this->addSql('CREATE TABLE funding_audit (id INT NOT NULL, rev INT NOT NULL, notice_identifier VARCHAR(255) DEFAULT NULL, notice_type VARCHAR(255) DEFAULT NULL, organisation_name VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, published_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, title VARCHAR(1000) DEFAULT NULL, description TEXT DEFAULT NULL, postcode VARCHAR(255) DEFAULT NULL, links JSON DEFAULT NULL, additional_text TEXT DEFAULT NULL, start_date DATE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, closing_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, suitable_for_sme BOOLEAN DEFAULT NULL, suitable_for_vco BOOLEAN DEFAULT NULL, supply_chain BOOLEAN DEFAULT NULL, ojeu_contract_type VARCHAR(255) DEFAULT NULL, value_low BIGINT DEFAULT NULL, value_high BIGINT DEFAULT NULL, ojeu_procedure_type VARCHAR(255) DEFAULT NULL, external_link VARCHAR(1000) DEFAULT NULL, external_id VARCHAR(255) DEFAULT NULL, downloads JSON DEFAULT NULL, slug VARCHAR(1000) DEFAULT NULL, submission_method JSON DEFAULT NULL, submission_method_details VARCHAR(2000) DEFAULT NULL, tags jsonb DEFAULT NULL, procurement_method VARCHAR(255) DEFAULT NULL, procurement_method_details VARCHAR(500) DEFAULT NULL, main_procurement_category VARCHAR(255) DEFAULT NULL, ocid VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_6b877fd22647b29cf2b13e2b75366aef_idx ON funding_audit (rev)');
        $this->addSql('COMMENT ON COLUMN funding_audit.published_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN funding_audit.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN funding_audit.end_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN funding_audit.closing_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE funding_cpvcode_audit (funding_id INT NOT NULL, cpvcode_id INT NOT NULL, rev INT NOT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(funding_id, cpvcode_id, rev))');
        $this->addSql('CREATE INDEX rev_53f6556692277402ff959b5b241747b8_idx ON funding_cpvcode_audit (rev)');
        $this->addSql('CREATE TABLE funding_party_audit (funding_id INT NOT NULL, party_id INT NOT NULL, rev INT NOT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(funding_id, party_id, rev))');
        $this->addSql('CREATE INDEX rev_9266f4e4d92d60de249ad97f6914276c_idx ON funding_party_audit (rev)');
        $this->addSql('CREATE TABLE funding_region_audit (id INT NOT NULL, rev INT NOT NULL, region VARCHAR(255) DEFAULT NULL, show_in_filter BOOLEAN DEFAULT false, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_43cc1bbf7a3948e4016d75addc49d91e_idx ON funding_region_audit (rev)');
        $this->addSql('CREATE TABLE funding_region_funding_audit (funding_region_id INT NOT NULL, funding_id INT NOT NULL, rev INT NOT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(funding_region_id, funding_id, rev))');
        $this->addSql('CREATE INDEX rev_33ba85989ffe2776c2fc8f66a57714e2_idx ON funding_region_funding_audit (rev)');
        $this->addSql('CREATE TABLE grant_audit (id INT NOT NULL, rev INT NOT NULL, title VARCHAR(500) DEFAULT NULL, funder VARCHAR(500) DEFAULT NULL, description TEXT DEFAULT NULL, max_amount INT DEFAULT NULL, how_to_apply TEXT DEFAULT NULL, closing_date DATE DEFAULT NULL, funding_details TEXT DEFAULT NULL, who_can_apply TEXT DEFAULT NULL, find_out_more TEXT DEFAULT NULL, published_date DATE DEFAULT NULL, status VARCHAR(255) DEFAULT \'Open\', slug VARCHAR(500) DEFAULT NULL, guidance_documents TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_5687122ecbd2fd091c44ad3ab1d3278e_idx ON grant_audit (rev)');
        $this->addSql('CREATE TABLE grant_grant_location_audit (grant_id INT NOT NULL, grant_location_id INT NOT NULL, rev INT NOT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(grant_id, grant_location_id, rev))');
        $this->addSql('CREATE INDEX rev_0855f0453e77cfba2e185f3b919bc16e_idx ON grant_grant_location_audit (rev)');
        $this->addSql('CREATE TABLE grant_grant_category_audit (grant_id INT NOT NULL, grant_category_id INT NOT NULL, rev INT NOT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(grant_id, grant_category_id, rev))');
        $this->addSql('CREATE INDEX rev_3e2dc578e10f83d64d4131dae7c7f259_idx ON grant_grant_category_audit (rev)');
        $this->addSql('CREATE TABLE grant_grant_open_to_audit (grant_id INT NOT NULL, grant_open_to_id INT NOT NULL, rev INT NOT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(grant_id, grant_open_to_id, rev))');
        $this->addSql('CREATE INDEX rev_dda59dcb9168df9100da7027d9f6216b_idx ON grant_grant_open_to_audit (rev)');
        $this->addSql('CREATE TABLE grant_category_audit (id INT NOT NULL, rev INT NOT NULL, category VARCHAR(255) DEFAULT NULL, show_in_filter BOOLEAN DEFAULT false, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_24f34cd2abae79144d32710d7de4e1dd_idx ON grant_category_audit (rev)');
        $this->addSql('CREATE TABLE grant_location_audit (id INT NOT NULL, rev INT NOT NULL, location VARCHAR(255) DEFAULT NULL, show_in_filter BOOLEAN DEFAULT false, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_630da2195ed5af523c8518178730f686_idx ON grant_location_audit (rev)');
        $this->addSql('CREATE TABLE grant_open_to_audit (id INT NOT NULL, rev INT NOT NULL, open_to VARCHAR(500) DEFAULT NULL, show_in_filter BOOLEAN DEFAULT false, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_6968df21a70c4ae2ae59889edc5d8558_idx ON grant_open_to_audit (rev)');
        $this->addSql('CREATE TABLE questionnaire_answer_audit (id INT NOT NULL, rev INT NOT NULL, user_id INT DEFAULT NULL, grant_id INT DEFAULT NULL, prompt_tokens INT DEFAULT 0, completion_tokens INT DEFAULT 0, total_tokens INT DEFAULT 0, points_consumed INT DEFAULT 0, estimated_price DOUBLE PRECISION DEFAULT \'0\', postcodes JSON DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_a302540ee2af46cd003a3cd4c9286c9d_idx ON questionnaire_answer_audit (rev)');
        $this->addSql('CREATE TABLE suggestion_audit (id INT NOT NULL, rev INT NOT NULL, grant_id INT DEFAULT NULL, user_id INT DEFAULT NULL, description TEXT DEFAULT NULL, prompt_tokens INT DEFAULT 0, completion_tokens INT DEFAULT 0, total_tokens INT DEFAULT 0, points_consumed INT DEFAULT 0, estimated_price DOUBLE PRECISION DEFAULT \'0\', slug VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_0768198981d971c8e2fb4b786c2258e0_idx ON suggestion_audit (rev)');
        $this->addSql('CREATE TABLE tariff_audit (id INT NOT NULL, rev INT NOT NULL, title VARCHAR(255) DEFAULT NULL, product_id VARCHAR(255) DEFAULT NULL, period VARCHAR(255) DEFAULT NULL, month_price VARCHAR(255) DEFAULT NULL, year_price VARCHAR(255) DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_a58fb9d5d4ed14d0ac3218c443cc8697_idx ON tariff_audit (rev)');
        $this->addSql('CREATE TABLE user_audit (id INT NOT NULL, rev INT NOT NULL, tariff_id INT DEFAULT NULL, username VARCHAR(180) DEFAULT NULL, username_canonical VARCHAR(180) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, email_canonical VARCHAR(180) DEFAULT NULL, enabled BOOLEAN DEFAULT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_verified BOOLEAN DEFAULT NULL, points INT DEFAULT 0, subscribed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, notification_email VARCHAR(255) DEFAULT NULL, account_type VARCHAR(255) DEFAULT \'free\', last_logged_in TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, customer_id VARCHAR(255) DEFAULT NULL, brief BOOLEAN DEFAULT true, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_e06395edc291d0719bee26fd39a32e8a_idx ON user_audit (rev)');
        $this->addSql('COMMENT ON COLUMN user_audit.roles IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN user_audit.subscribed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_company_audit (id INT NOT NULL, rev INT NOT NULL, user_id INT DEFAULT NULL, description TEXT DEFAULT NULL, locations JSON DEFAULT NULL, open_to JSON DEFAULT NULL, project_examples TEXT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev))');
        $this->addSql('CREATE INDEX rev_a3efcf1b62a2bf48997d85bc97ed7c8e_idx ON user_company_audit (rev)');
        $this->addSql('ALTER TABLE answer_audit ADD CONSTRAINT rev_c37401e9ba2a8f5bca26846c01a9d14e_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cpvcode_audit ADD CONSTRAINT rev_e3badc29c67fb4dd940baa51c756f3af_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE funding_audit ADD CONSTRAINT rev_6b877fd22647b29cf2b13e2b75366aef_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE funding_region_audit ADD CONSTRAINT rev_43cc1bbf7a3948e4016d75addc49d91e_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_audit ADD CONSTRAINT rev_5687122ecbd2fd091c44ad3ab1d3278e_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_category_audit ADD CONSTRAINT rev_24f34cd2abae79144d32710d7de4e1dd_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_location_audit ADD CONSTRAINT rev_630da2195ed5af523c8518178730f686_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grant_open_to_audit ADD CONSTRAINT rev_6968df21a70c4ae2ae59889edc5d8558_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE questionnaire_answer_audit ADD CONSTRAINT rev_a302540ee2af46cd003a3cd4c9286c9d_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE suggestion_audit ADD CONSTRAINT rev_0768198981d971c8e2fb4b786c2258e0_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tariff_audit ADD CONSTRAINT rev_a58fb9d5d4ed14d0ac3218c443cc8697_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_audit ADD CONSTRAINT rev_e06395edc291d0719bee26fd39a32e8a_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_company_audit ADD CONSTRAINT rev_a3efcf1b62a2bf48997d85bc97ed7c8e_fk FOREIGN KEY (rev) REFERENCES revisions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer_audit DROP CONSTRAINT rev_c37401e9ba2a8f5bca26846c01a9d14e_fk');
        $this->addSql('ALTER TABLE cpvcode_audit DROP CONSTRAINT rev_e3badc29c67fb4dd940baa51c756f3af_fk');
        $this->addSql('ALTER TABLE funding_audit DROP CONSTRAINT rev_6b877fd22647b29cf2b13e2b75366aef_fk');
        $this->addSql('ALTER TABLE funding_region_audit DROP CONSTRAINT rev_43cc1bbf7a3948e4016d75addc49d91e_fk');
        $this->addSql('ALTER TABLE grant_audit DROP CONSTRAINT rev_5687122ecbd2fd091c44ad3ab1d3278e_fk');
        $this->addSql('ALTER TABLE grant_category_audit DROP CONSTRAINT rev_24f34cd2abae79144d32710d7de4e1dd_fk');
        $this->addSql('ALTER TABLE grant_location_audit DROP CONSTRAINT rev_630da2195ed5af523c8518178730f686_fk');
        $this->addSql('ALTER TABLE grant_open_to_audit DROP CONSTRAINT rev_6968df21a70c4ae2ae59889edc5d8558_fk');
        $this->addSql('ALTER TABLE questionnaire_answer_audit DROP CONSTRAINT rev_a302540ee2af46cd003a3cd4c9286c9d_fk');
        $this->addSql('ALTER TABLE suggestion_audit DROP CONSTRAINT rev_0768198981d971c8e2fb4b786c2258e0_fk');
        $this->addSql('ALTER TABLE tariff_audit DROP CONSTRAINT rev_a58fb9d5d4ed14d0ac3218c443cc8697_fk');
        $this->addSql('ALTER TABLE user_audit DROP CONSTRAINT rev_e06395edc291d0719bee26fd39a32e8a_fk');
        $this->addSql('ALTER TABLE user_company_audit DROP CONSTRAINT rev_a3efcf1b62a2bf48997d85bc97ed7c8e_fk');
        $this->addSql('DROP TABLE revisions');
        $this->addSql('DROP TABLE answer_audit');
        $this->addSql('DROP TABLE cpvcode_audit');
        $this->addSql('DROP TABLE funding_audit');
        $this->addSql('DROP TABLE funding_cpvcode_audit');
        $this->addSql('DROP TABLE funding_party_audit');
        $this->addSql('DROP TABLE funding_region_audit');
        $this->addSql('DROP TABLE funding_region_funding_audit');
        $this->addSql('DROP TABLE grant_audit');
        $this->addSql('DROP TABLE grant_grant_location_audit');
        $this->addSql('DROP TABLE grant_grant_category_audit');
        $this->addSql('DROP TABLE grant_grant_open_to_audit');
        $this->addSql('DROP TABLE grant_category_audit');
        $this->addSql('DROP TABLE grant_location_audit');
        $this->addSql('DROP TABLE grant_open_to_audit');
        $this->addSql('DROP TABLE questionnaire_answer_audit');
        $this->addSql('DROP TABLE suggestion_audit');
        $this->addSql('DROP TABLE tariff_audit');
        $this->addSql('DROP TABLE user_audit');
        $this->addSql('DROP TABLE user_company_audit');
    }
}
