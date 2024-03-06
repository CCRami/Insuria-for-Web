<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305230828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF08B12E471');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0197E709F');
        $this->addSql('ALTER TABLE avis ADD etat TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF08B12E471 FOREIGN KEY (agenceav_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0197E709F FOREIGN KEY (avis_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0197E709F');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF08B12E471');
        $this->addSql('ALTER TABLE avis DROP etat');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0197E709F FOREIGN KEY (avis_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF08B12E471 FOREIGN KEY (agenceav_id) REFERENCES agence (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
