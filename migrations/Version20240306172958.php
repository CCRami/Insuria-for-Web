<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306172958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence ADD create_at DATE NOT NULL, CHANGE nom_age nomage VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE avis ADD avis_id INT NOT NULL, ADD agenceav_id INT NOT NULL, ADD etat TINYINT(1) NOT NULL, CHANGE date_avis date_avis DATE NOT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0197E709F FOREIGN KEY (avis_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF08B12E471 FOREIGN KEY (agenceav_id) REFERENCES agence (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_8F91ABF0197E709F ON avis (avis_id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF08B12E471 ON avis (agenceav_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence DROP create_at, CHANGE nomage nom_age VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0197E709F');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF08B12E471');
        $this->addSql('DROP INDEX IDX_8F91ABF0197E709F ON avis');
        $this->addSql('DROP INDEX IDX_8F91ABF08B12E471 ON avis');
        $this->addSql('ALTER TABLE avis DROP avis_id, DROP agenceav_id, DROP etat, CHANGE date_avis date_avis DATETIME NOT NULL');
    }
}
