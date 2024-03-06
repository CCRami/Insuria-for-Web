<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306154832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD off_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DD95F2A3C FOREIGN KEY (off_id) REFERENCES offre (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DD95F2A3C ON commande (off_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DD95F2A3C');
        $this->addSql('DROP INDEX IDX_6EEAA67DD95F2A3C ON commande');
        $this->addSql('ALTER TABLE commande DROP off_id');
    }
}
