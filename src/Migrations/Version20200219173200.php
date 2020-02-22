<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200219173200 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transactions ADD compteenvoi_id INT DEFAULT NULL, ADD compteretrait_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CF3B831CE FOREIGN KEY (compteenvoi_id) REFERENCES bank_account (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CF0EADA55 FOREIGN KEY (compteretrait_id) REFERENCES bank_account (id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4CF3B831CE ON transactions (compteenvoi_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4CF0EADA55 ON transactions (compteretrait_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CF3B831CE');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CF0EADA55');
        $this->addSql('DROP INDEX IDX_EAA81A4CF3B831CE ON transactions');
        $this->addSql('DROP INDEX IDX_EAA81A4CF0EADA55 ON transactions');
        $this->addSql('ALTER TABLE transactions DROP compteenvoi_id, DROP compteretrait_id');
    }
}
