<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181115115156 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment ADD changelog_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C3C8F0C57 FOREIGN KEY (changelog_id) REFERENCES changelog (id)');
        $this->addSql('CREATE INDEX IDX_9474526C3C8F0C57 ON comment (changelog_id)');
        $this->addSql('ALTER TABLE changelog ADD title VARCHAR(255) NOT NULL, ADD number VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE changelog DROP title, DROP number');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C3C8F0C57');
        $this->addSql('DROP INDEX IDX_9474526C3C8F0C57 ON comment');
        $this->addSql('ALTER TABLE comment DROP changelog_id');
    }
}
