<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190514205420 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reviews (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, estimator_id_id INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_6970EB0F9D86650F (user_id_id), INDEX IDX_6970EB0FC17AA84F (estimator_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F9D86650F FOREIGN KEY (user_id_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FC17AA84F FOREIGN KEY (estimator_id_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_profile CHANGE user_id_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE job ADD address VARCHAR(120) NOT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE budget budget NUMERIC(10, 0) DEFAULT NULL, CHANGE lat lat NUMERIC(17, 14) NOT NULL, CHANGE lon lon NUMERIC(17, 14) NOT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL, CHANGE active_till active_till DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD address VARCHAR(120) NOT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE service_type service_type VARCHAR(255) DEFAULT NULL, CHANGE lat lat NUMERIC(17, 14) NOT NULL, CHANGE lon lon NUMERIC(17, 14) NOT NULL');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE matches CHANGE caller_id caller_id INT DEFAULT NULL, CHANGE caller_service_id caller_service_id INT DEFAULT NULL, CHANGE responder_id responder_id INT DEFAULT NULL, CHANGE responder_service_id responder_service_id INT DEFAULT NULL, CHANGE caller_job_id caller_job_id INT DEFAULT NULL, CHANGE responder_job_id responder_job_id INT DEFAULT NULL, CHANGE accepted_at accepted_at DATETIME DEFAULT NULL, CHANGE rejected_at rejected_at DATETIME DEFAULT NULL, CHANGE cancelled_at cancelled_at DATETIME DEFAULT NULL, CHANGE payed_at payed_at DATETIME DEFAULT NULL, CHANGE hidden hidden TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE reviews');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE job DROP address, CHANGE user_id user_id INT DEFAULT NULL, CHANGE budget budget NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE lat lat NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE lon lon NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE date_end date_end DATETIME DEFAULT \'NULL\', CHANGE active_till active_till DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE matches CHANGE caller_id caller_id INT DEFAULT NULL, CHANGE caller_service_id caller_service_id INT DEFAULT NULL, CHANGE caller_job_id caller_job_id INT DEFAULT NULL, CHANGE responder_id responder_id INT DEFAULT NULL, CHANGE responder_service_id responder_service_id INT DEFAULT NULL, CHANGE responder_job_id responder_job_id INT DEFAULT NULL, CHANGE accepted_at accepted_at DATETIME DEFAULT \'NULL\', CHANGE rejected_at rejected_at DATETIME DEFAULT \'NULL\', CHANGE cancelled_at cancelled_at DATETIME DEFAULT \'NULL\', CHANGE payed_at payed_at DATETIME DEFAULT \'NULL\', CHANGE hidden hidden TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE service DROP address, CHANGE user_id user_id INT DEFAULT NULL, CHANGE service_type service_type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE lat lat NUMERIC(10, 0) NOT NULL, CHANGE lon lon NUMERIC(10, 0) NOT NULL');
        $this->addSql('ALTER TABLE user_profile CHANGE user_id_id user_id_id INT DEFAULT NULL');
    }
}
