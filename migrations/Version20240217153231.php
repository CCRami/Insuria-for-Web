<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217153231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE indemnissation DROP FOREIGN KEY FK_6DBECB5C2D6BA2D9');
        $this->addSql('DROP INDEX UNIQ_6DBECB5C2D6BA2D9 ON indemnissation');
        $this->addSql('ALTER TABLE indemnissation DROP reclamation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE indemnissation ADD reclamation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE indemnissation ADD CONSTRAINT FK_6DBECB5C2D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DBECB5C2D6BA2D9 ON indemnissation (reclamation_id)');
    }
}
