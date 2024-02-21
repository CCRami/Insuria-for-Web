<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217143805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE indemnissation (id INT AUTO_INCREMENT NOT NULL, reclamation_id INT DEFAULT NULL, date DATE DEFAULT NULL, beneficitaire VARCHAR(255) DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_6DBECB5C2D6BA2D9 (reclamation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE indemnissation ADD CONSTRAINT FK_6DBECB5C2D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('DROP TABLE indemnisation');
        $this->addSql('ALTER TABLE reclamation ADD indemnissation_id INT DEFAULT NULL, CHANGE libelle libelle VARCHAR(255) NOT NULL, CHANGE contenu_rec contenu_rec VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064047308287 FOREIGN KEY (indemnissation_id) REFERENCES indemnissation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CE6064047308287 ON reclamation (indemnissation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064047308287');
        $this->addSql('CREATE TABLE indemnisation (id INT AUTO_INCREMENT NOT NULL, date_ind DATETIME NOT NULL, benif VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, montant_ind DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE indemnissation DROP FOREIGN KEY FK_6DBECB5C2D6BA2D9');
        $this->addSql('DROP TABLE indemnissation');
        $this->addSql('DROP INDEX UNIQ_CE6064047308287 ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP indemnissation_id, CHANGE libelle libelle VARCHAR(255) DEFAULT NULL, CHANGE contenu_rec contenu_rec VARCHAR(255) DEFAULT NULL');
    }
}
