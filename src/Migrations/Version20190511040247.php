<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190511040247 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE job_category (job_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_610BBCBABE04EA9 (job_id), INDEX IDX_610BBCBA12469DE2 (category_id), PRIMARY KEY(job_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_category ADD CONSTRAINT FK_610BBCBABE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_category ADD CONSTRAINT FK_610BBCBA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matches CHANGE caller_id caller_id INT DEFAULT NULL, CHANGE caller_service_id caller_service_id INT DEFAULT NULL, CHANGE responder_id responder_id INT DEFAULT NULL, CHANGE responder_service_id responder_service_id INT DEFAULT NULL, CHANGE accepted_at accepted_at DATETIME DEFAULT NULL, CHANGE rejected_at rejected_at DATETIME DEFAULT NULL, CHANGE cancelled_at cancelled_at DATETIME DEFAULT NULL, CHANGE payed_at payed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE service CHANGE user_id user_id INT DEFAULT NULL, CHANGE service_type service_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile CHANGE user_id_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE job DROP category1, DROP category2, CHANGE user_id user_id INT DEFAULT NULL, CHANGE budget budget NUMERIC(10, 0) DEFAULT NULL, CHANGE lat lat NUMERIC(10, 0) DEFAULT NULL, CHANGE lon lon NUMERIC(10, 0) DEFAULT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL, CHANGE active_till active_till DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE job_category');
        $this->addSql('ALTER TABLE category CHANGE name name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE job ADD category1 LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD category2 LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE user_id user_id INT DEFAULT NULL, CHANGE budget budget NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE lat lat NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE lon lon NUMERIC(10, 0) DEFAULT \'NULL\', CHANGE date_end date_end DATETIME DEFAULT \'NULL\', CHANGE active_till active_till DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE matches CHANGE caller_id caller_id INT DEFAULT NULL, CHANGE caller_service_id caller_service_id INT DEFAULT NULL, CHANGE responder_id responder_id INT DEFAULT NULL, CHANGE responder_service_id responder_service_id INT DEFAULT NULL, CHANGE accepted_at accepted_at DATETIME DEFAULT \'NULL\', CHANGE rejected_at rejected_at DATETIME DEFAULT \'NULL\', CHANGE cancelled_at cancelled_at DATETIME DEFAULT \'NULL\', CHANGE payed_at payed_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE service CHANGE user_id user_id INT DEFAULT NULL, CHANGE service_type service_type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user_profile CHANGE user_id_id user_id_id INT DEFAULT NULL');
    }
}
