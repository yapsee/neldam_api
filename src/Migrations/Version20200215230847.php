<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200215230847 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE affectation ADD affectedby_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE affectation ADD CONSTRAINT FK_F4DD61D3FF0BC4E2 FOREIGN KEY (affectedby_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F4DD61D3FF0BC4E2 ON affectation (affectedby_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE affectation DROP FOREIGN KEY FK_F4DD61D3FF0BC4E2');
        $this->addSql('DROP INDEX IDX_F4DD61D3FF0BC4E2 ON affectation');
        $this->addSql('ALTER TABLE affectation DROP affectedby_id');
    }
}
