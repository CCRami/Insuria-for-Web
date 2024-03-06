<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306144528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_offre ADD catimg VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offre ADD categorie_id INT DEFAULT NULL, ADD offreimg VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_offre (id)');
        $this->addSql('CREATE INDEX IDX_AF86866FBCF5E72D ON offre (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_offre DROP catimg');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FBCF5E72D');
        $this->addSql('DROP INDEX IDX_AF86866FBCF5E72D ON offre');
        $this->addSql('ALTER TABLE offre DROP categorie_id, DROP offreimg');
    }
}
