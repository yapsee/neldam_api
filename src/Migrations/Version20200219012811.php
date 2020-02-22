<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200219012811 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transactions ADD userdepot_id INT DEFAULT NULL, ADD userretrait_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CA9552BA9 FOREIGN KEY (userdepot_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C33846983 FOREIGN KEY (userretrait_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4CA9552BA9 ON transactions (userdepot_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C33846983 ON transactions (userretrait_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CA9552BA9');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C33846983');
        $this->addSql('DROP INDEX IDX_EAA81A4CA9552BA9 ON transactions');
        $this->addSql('DROP INDEX IDX_EAA81A4C33846983 ON transactions');
        $this->addSql('ALTER TABLE transactions DROP userdepot_id, DROP userretrait_id');
    }
}
