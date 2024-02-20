<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214234615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis ADD agenceav_id INT NOT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF08B12E471 FOREIGN KEY (agenceav_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF08B12E471 ON avis (agenceav_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF08B12E471');
        $this->addSql('DROP INDEX IDX_8F91ABF08B12E471 ON avis');
        $this->addSql('ALTER TABLE avis DROP agenceav_id');
    }
}
