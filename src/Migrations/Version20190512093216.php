<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190512093216 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE matches ADD caller_job_id INT DEFAULT NULL, ADD responder_job_id INT DEFAULT NULL, ADD hidden TINYINT(1) NOT NULL, CHANGE caller_id caller_id INT DEFAULT NULL, CHANGE caller_service_id caller_service_id INT DEFAULT NULL, CHANGE responder_id responder_id INT DEFAULT NULL, CHANGE responder_service_id responder_service_id INT DEFAULT NULL, CHANGE accepted_at accepted_at DATETIME DEFAULT NULL, CHANGE rejected_at rejected_at DATETIME DEFAULT NULL, CHANGE cancelled_at cancelled_at DATETIME DEFAULT NULL, CHANGE payed_at payed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BA9911426 FOREIGN KEY (caller_job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BAFE680242 FOREIGN KEY (responder_job_id) REFERENCES job (id)');
        $this->addSql('CREATE INDEX IDX_62615BA9911426 ON matches (caller_job_id)');
        $this->addSql('CREATE INDEX IDX_62615BAFE680242 ON matches (responder_job_id)');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE service CHANGE user_id user_id INT DEFAULT NULL, CHANGE service_type service_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile DROP job_title, DROP photo, DROP languages, DROP skill, DROP hour_price, DROP phone, CHANGE user_id_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE job CHANGE user_id user_id INT DEFAULT NULL, CHANGE budget budget NUMERIC(10, 0) DEFAULT NULL, CHANGE lat lat NUMERIC(10, 0) DEFAULT NULL, CHANGE lon lon NUMERIC(10, 0) DEFAULT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL, CHANGE active_till active_till DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE job CHANGE user_id user_id INT DEFAULT NULL, CHANGE budget budget NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE lat lat NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE lon lon NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE date_end date_end DATETIME DEFAULT \'NULL\', CHANGE active_till active_till DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BA9911426');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BAFE680242');
        $this->addSql('DROP INDEX IDX_62615BA9911426 ON matches');
        $this->addSql('DROP INDEX IDX_62615BAFE680242 ON matches');
        $this->addSql('ALTER TABLE matches DROP caller_job_id, DROP responder_job_id, DROP hidden, CHANGE caller_id caller_id INT DEFAULT NULL, CHANGE caller_service_id caller_service_id INT DEFAULT NULL, CHANGE responder_id responder_id INT DEFAULT NULL, CHANGE responder_service_id responder_service_id INT DEFAULT NULL, CHANGE accepted_at accepted_at DATETIME DEFAULT \'NULL\', CHANGE rejected_at rejected_at DATETIME DEFAULT \'NULL\', CHANGE cancelled_at cancelled_at DATETIME DEFAULT \'NULL\', CHANGE payed_at payed_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE service CHANGE user_id user_id INT DEFAULT NULL, CHANGE service_type service_type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user_profile ADD job_title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD photo VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD languages VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD skill VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD hour_price INT NOT NULL, ADD phone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE user_id_id user_id_id INT DEFAULT NULL');
    }
}
