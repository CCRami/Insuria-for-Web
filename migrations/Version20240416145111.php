<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416145111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064047308287');
        $this->addSql('CREATE TABLE indemnisation (id INT AUTO_INCREMENT NOT NULL, date_ind DATETIME NOT NULL, benif VARCHAR(255) NOT NULL, montant_ind DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE adressemap');
        $this->addSql('DROP TABLE indemnissation');
        $this->addSql('DROP INDEX email ON agence');
        $this->addSql('ALTER TABLE agence ADD idadressemap INT DEFAULT NULL');
        $this->addSql('ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE49721867');
        $this->addSql('ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE26EDA4E9');
        $this->addSql('DROP INDEX IDX_386829AE49721867 ON assurance');
        $this->addSql('DROP INDEX IDX_386829AE26EDA4E9 ON assurance');
        $this->addSql('ALTER TABLE assurance DROP cat_a_id, DROP pol_id, DROP ins_image, CHANGE doa doa VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0197E709F');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF08B12E471');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0197E709F FOREIGN KEY (avis_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF08B12E471 FOREIGN KEY (agenceav_id) REFERENCES agence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_offre DROP catimg');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DB4CE15E6');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DD95F2A3C');
        $this->addSql('DROP INDEX IDX_6EEAA67DB4CE15E6 ON commande');
        $this->addSql('DROP INDEX IDX_6EEAA67DA76ED395 ON commande');
        $this->addSql('DROP INDEX IDX_6EEAA67DD95F2A3C ON commande');
        $this->addSql('ALTER TABLE commande DROP doa_com_id, DROP user_id, DROP off_id, DROP ins_value, DROP is_checked, CHANGE full_doa full_doa VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FBCF5E72D');
        $this->addSql('DROP INDEX IDX_AF86866FBCF5E72D ON offre');
        $this->addSql('ALTER TABLE offre DROP categorie_id, DROP offreimg');
        $this->addSql('ALTER TABLE police DROP FOREIGN KEY FK_E47C5959216966DF');
        $this->addSql('DROP INDEX IDX_E47C5959216966DF ON police');
        $this->addSql('ALTER TABLE police DROP sinistre_id');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640433E1689A');
        $this->addSql('DROP INDEX UNIQ_CE6064047308287 ON reclamation');
        $this->addSql('DROP INDEX IDX_CE60640433E1689A ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP indemnissation_id, DROP command_id, DROP file_name, DROP latitude, DROP longitude, CHANGE reponse reponse TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE sinistre DROP image_path');
        $this->addSql('ALTER TABLE user ADD role VARCHAR(255) NOT NULL, DROP roles, DROP is_verified, DROP secret, DROP google_id, DROP avatar, DROP hosted_domain, DROP is_blocked');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adressemap (latitude VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, longitude VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE indemnissation (id INT AUTO_INCREMENT NOT NULL, date DATE DEFAULT NULL, beneficitaire VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, montant DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE indemnisation');
        $this->addSql('ALTER TABLE agence DROP idadressemap');
        $this->addSql('CREATE UNIQUE INDEX email ON agence (email)');
        $this->addSql('ALTER TABLE assurance ADD cat_a_id INT DEFAULT NULL, ADD pol_id INT NOT NULL, ADD ins_image VARCHAR(255) NOT NULL, CHANGE doa doa JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE assurance ADD CONSTRAINT FK_386829AE49721867 FOREIGN KEY (pol_id) REFERENCES police (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assurance ADD CONSTRAINT FK_386829AE26EDA4E9 FOREIGN KEY (cat_a_id) REFERENCES categorie_assurance (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_386829AE49721867 ON assurance (pol_id)');
        $this->addSql('CREATE INDEX IDX_386829AE26EDA4E9 ON assurance (cat_a_id)');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0197E709F');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF08B12E471');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0197E709F FOREIGN KEY (avis_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF08B12E471 FOREIGN KEY (agenceav_id) REFERENCES agence (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_offre ADD catimg VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commande ADD doa_com_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD off_id INT DEFAULT NULL, ADD ins_value DOUBLE PRECISION NOT NULL, ADD is_checked TINYINT(1) NOT NULL, CHANGE full_doa full_doa JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DB4CE15E6 FOREIGN KEY (doa_com_id) REFERENCES assurance (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DD95F2A3C FOREIGN KEY (off_id) REFERENCES offre (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6EEAA67DB4CE15E6 ON commande (doa_com_id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DD95F2A3C ON commande (off_id)');
        $this->addSql('ALTER TABLE offre ADD categorie_id INT DEFAULT NULL, ADD offreimg VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_offre (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_AF86866FBCF5E72D ON offre (categorie_id)');
        $this->addSql('ALTER TABLE police ADD sinistre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE police ADD CONSTRAINT FK_E47C5959216966DF FOREIGN KEY (sinistre_id) REFERENCES sinistre (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_E47C5959216966DF ON police (sinistre_id)');
        $this->addSql('ALTER TABLE reclamation ADD indemnissation_id INT DEFAULT NULL, ADD command_id INT NOT NULL, ADD file_name VARCHAR(255) DEFAULT NULL, ADD latitude VARCHAR(50) NOT NULL, ADD longitude VARCHAR(50) NOT NULL, CHANGE reponse reponse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640433E1689A FOREIGN KEY (command_id) REFERENCES commande (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064047308287 FOREIGN KEY (indemnissation_id) REFERENCES indemnissation (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CE6064047308287 ON reclamation (indemnissation_id)');
        $this->addSql('CREATE INDEX IDX_CE60640433E1689A ON reclamation (command_id)');
        $this->addSql('ALTER TABLE sinistre ADD image_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD is_verified TINYINT(1) NOT NULL, ADD secret VARCHAR(255) DEFAULT NULL, ADD google_id VARCHAR(255) DEFAULT NULL, ADD avatar LONGTEXT DEFAULT NULL, ADD hosted_domain VARCHAR(255) DEFAULT NULL, ADD is_blocked TINYINT(1) NOT NULL, DROP role');
    }
}
