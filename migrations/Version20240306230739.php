<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306230739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance ADD CONSTRAINT FK_386829AE49721867 FOREIGN KEY (pol_id) REFERENCES police (id)');
        $this->addSql('ALTER TABLE police DROP FOREIGN KEY FK_E47C5959216966DF');
        $this->addSql('ALTER TABLE police CHANGE sinistre_id sinistre_id INT NOT NULL');
        $this->addSql('ALTER TABLE police ADD CONSTRAINT FK_E47C5959216966DF FOREIGN KEY (sinistre_id) REFERENCES sinistre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640433E1689A33E1689A');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640433E1689A FOREIGN KEY (command_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE49721867');
        $this->addSql('ALTER TABLE police DROP FOREIGN KEY FK_E47C5959216966DF');
        $this->addSql('ALTER TABLE police CHANGE sinistre_id sinistre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE police ADD CONSTRAINT FK_E47C5959216966DF FOREIGN KEY (sinistre_id) REFERENCES sinistre (id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640433E1689A');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640433E1689A33E1689A FOREIGN KEY (command_id) REFERENCES commande (id) ON DELETE CASCADE');
    }
}
