<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180928031205 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE requirement (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', reported_by_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', updated_by_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', number VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, received_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, progress INT DEFAULT NULL, UNIQUE INDEX UNIQ_DB3F5550BF396750 (id), INDEX IDX_DB3F555071CE806 (reported_by_id), INDEX IDX_DB3F5550B03A8386 (created_by_id), INDEX IDX_DB3F5550896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE requirement ADD CONSTRAINT FK_DB3F555071CE806 FOREIGN KEY (reported_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE requirement ADD CONSTRAINT FK_DB3F5550B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE requirement ADD CONSTRAINT FK_DB3F5550896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD requirement_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7B576F77 FOREIGN KEY (requirement_id) REFERENCES requirement (id)');
        $this->addSql('CREATE INDEX IDX_9474526C7B576F77 ON comment (requirement_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7B576F77');
        $this->addSql('DROP TABLE requirement');
        $this->addSql('DROP INDEX IDX_9474526C7B576F77 ON comment');
        $this->addSql('ALTER TABLE comment DROP requirement_id');
    }
}
