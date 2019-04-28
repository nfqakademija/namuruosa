<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190427092455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `match` (id INT AUTO_INCREMENT NOT NULL, caller_id INT DEFAULT NULL, caller_service_id INT DEFAULT NULL, responder_id INT DEFAULT NULL, responder_service_id INT DEFAULT NULL, created_at DATETIME NOT NULL, accepted_at DATETIME NOT NULL, rejected_at DATETIME NOT NULL, cancelled_at DATETIME NOT NULL, payed_at DATETIME NOT NULL, INDEX IDX_7A5BC505A5626C52 (caller_id), INDEX IDX_7A5BC505E1678D76 (caller_service_id), INDEX IDX_7A5BC50537395ADB (responder_id), INDEX IDX_7A5BC505AB2F0FAB (responder_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC505A5626C52 FOREIGN KEY (caller_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC505E1678D76 FOREIGN KEY (caller_service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC50537395ADB FOREIGN KEY (responder_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE `match` ADD CONSTRAINT FK_7A5BC505AB2F0FAB FOREIGN KEY (responder_service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE service CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE `match`');
        $this->addSql('ALTER TABLE fos_user CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE service CHANGE user_id user_id INT DEFAULT NULL');
    }
}
