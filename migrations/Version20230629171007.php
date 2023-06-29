<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230629171007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rasp_authorization ADD user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rasp_authorization ADD CONSTRAINT FK_AB90C38AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AB90C38AA76ED395 ON rasp_authorization (user_id)');
        $this->addSql('ALTER TABLE rasp_project ADD authorized_users_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rasp_project ADD CONSTRAINT FK_F1FA83481A43EEE6 FOREIGN KEY (authorized_users_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F1FA83481A43EEE6 ON rasp_project (authorized_users_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rasp_authorization DROP FOREIGN KEY FK_AB90C38AA76ED395');
        $this->addSql('DROP INDEX IDX_AB90C38AA76ED395 ON rasp_authorization');
        $this->addSql('ALTER TABLE rasp_authorization DROP user_id');
        $this->addSql('ALTER TABLE rasp_project DROP FOREIGN KEY FK_F1FA83481A43EEE6');
        $this->addSql('DROP INDEX IDX_F1FA83481A43EEE6 ON rasp_project');
        $this->addSql('ALTER TABLE rasp_project DROP authorized_users_id');
    }
}
