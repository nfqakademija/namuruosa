<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190530111023 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX index_reviews ON reviews (id, user_id_id, estimator_id_id)');
        $this->addSql('CREATE INDEX index_user_profile ON user_profile (id, user_id_id)');
        $this->addSql('CREATE INDEX index_fos_user ON fos_user (id)');
        $this->addSql('CREATE INDEX index_reports ON reports (id, reporter_user_id, reported_user_id)');
        $this->addSql('CREATE INDEX index_job ON job (id, user_id)');
        $this->addSql('CREATE INDEX index_service ON service (id, user_id)');
        $this->addSql('CREATE INDEX index_category ON category (id)');
        $this->addSql('CREATE INDEX index_matches ON matches (id, caller_id, caller_service_id, responder_id, responder_service_id, caller_job_id, responder_job_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX index_reviews ON reviews');
        $this->addSql('DROP INDEX index_user_profile ON user_profile');
        $this->addSql('DROP INDEX index_fos_user ON fos_user');
        $this->addSql('DROP INDEX index_reports ON reports');
        $this->addSql('DROP INDEX index_job ON job');
        $this->addSql('DROP INDEX index_service ON service');
        $this->addSql('DROP INDEX index_category ON category');
        $this->addSql('DROP INDEX index_matches ON matches');
    }
}
