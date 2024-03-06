<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306121530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE indemnissation (id INT AUTO_INCREMENT NOT NULL, date DATE DEFAULT NULL, beneficitaire VARCHAR(255) DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE indemnisation');
        $this->addSql('ALTER TABLE reclamation ADD indemnissation_id INT DEFAULT NULL, ADD command_id INT NOT NULL, ADD file_name VARCHAR(255) DEFAULT NULL, ADD latitude VARCHAR(50) NOT NULL, ADD longitude VARCHAR(50) NOT NULL, CHANGE reponse reponse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064047308287 FOREIGN KEY (indemnissation_id) REFERENCES indemnissation (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640433E1689A FOREIGN KEY (command_id) REFERENCES commande (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CE6064047308287 ON reclamation (indemnissation_id)');
        $this->addSql('CREATE INDEX IDX_CE60640433E1689A ON reclamation (command_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064047308287');
        $this->addSql('CREATE TABLE indemnisation (id INT AUTO_INCREMENT NOT NULL, date_ind DATETIME NOT NULL, benif VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, montant_ind DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE indemnissation');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640433E1689A');
        $this->addSql('DROP INDEX UNIQ_CE6064047308287 ON reclamation');
        $this->addSql('DROP INDEX IDX_CE60640433E1689A ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP indemnissation_id, DROP command_id, DROP file_name, DROP latitude, DROP longitude, CHANGE reponse reponse TINYINT(1) NOT NULL');
    }
}
