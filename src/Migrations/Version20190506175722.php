<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506175722 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE matches CHANGE caller_id caller_id INT DEFAULT NULL, CHANGE caller_service_id caller_service_id INT DEFAULT NULL, CHANGE responder_id responder_id INT DEFAULT NULL, CHANGE responder_service_id responder_service_id INT DEFAULT NULL, CHANGE accepted_at accepted_at DATETIME DEFAULT NULL, CHANGE rejected_at rejected_at DATETIME DEFAULT NULL, CHANGE cancelled_at cancelled_at DATETIME DEFAULT NULL, CHANGE payed_at payed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD active_till DATETIME NOT NULL, ADD lat NUMERIC(10, 0) NOT NULL, ADD lon NUMERIC(10, 0) NOT NULL, ADD max_distance NUMERIC(10, 0) NOT NULL, ADD price_per_hour NUMERIC(10, 0) NOT NULL, DROP active_time_start, DROP active_time_end, DROP transport, DROP education, DROP cleaning, DROP coordinate_x, DROP coordinate_y, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile CHANGE user_id_id user_id_id INT DEFAULT NULL');
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
        $this->addSql('ALTER TABLE matches CHANGE caller_id caller_id INT DEFAULT NULL, CHANGE caller_service_id caller_service_id INT DEFAULT NULL, CHANGE responder_id responder_id INT DEFAULT NULL, CHANGE responder_service_id responder_service_id INT DEFAULT NULL, CHANGE accepted_at accepted_at DATETIME DEFAULT \'NULL\', CHANGE rejected_at rejected_at DATETIME DEFAULT \'NULL\', CHANGE cancelled_at cancelled_at DATETIME DEFAULT \'NULL\', CHANGE payed_at payed_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE service ADD active_time_start TIME NOT NULL, ADD active_time_end TIME NOT NULL, ADD transport TINYINT(1) NOT NULL, ADD education TINYINT(1) NOT NULL, ADD cleaning TINYINT(1) NOT NULL, ADD coordinate_x NUMERIC(10, 0) NOT NULL, ADD coordinate_y NUMERIC(10, 0) NOT NULL, DROP active_till, DROP lat, DROP lon, DROP max_distance, DROP price_per_hour, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile CHANGE user_id_id user_id_id INT DEFAULT NULL');
    }
}
