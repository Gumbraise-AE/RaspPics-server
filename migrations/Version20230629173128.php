<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230629173128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rasp_project_user (rasp_project_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_F22D8AF5550EC933 (rasp_project_id), INDEX IDX_F22D8AF5A76ED395 (user_id), PRIMARY KEY(rasp_project_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rasp_project_user ADD CONSTRAINT FK_F22D8AF5550EC933 FOREIGN KEY (rasp_project_id) REFERENCES rasp_project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rasp_project_user ADD CONSTRAINT FK_F22D8AF5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rasp_project DROP FOREIGN KEY FK_F1FA83481A43EEE6');
        $this->addSql('DROP INDEX IDX_F1FA83481A43EEE6 ON rasp_project');
        $this->addSql('ALTER TABLE rasp_project DROP authorized_users_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rasp_project_user DROP FOREIGN KEY FK_F22D8AF5550EC933');
        $this->addSql('ALTER TABLE rasp_project_user DROP FOREIGN KEY FK_F22D8AF5A76ED395');
        $this->addSql('DROP TABLE rasp_project_user');
        $this->addSql('ALTER TABLE rasp_project ADD authorized_users_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE rasp_project ADD CONSTRAINT FK_F1FA83481A43EEE6 FOREIGN KEY (authorized_users_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F1FA83481A43EEE6 ON rasp_project (authorized_users_id)');
    }
}
