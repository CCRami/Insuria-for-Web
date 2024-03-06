<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216134120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE police ADD sinistre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE police ADD CONSTRAINT FK_E47C5959216966DF FOREIGN KEY (sinistre_id) REFERENCES sinistre (id)');
        $this->addSql('CREATE INDEX IDX_E47C5959216966DF ON police (sinistre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE police DROP FOREIGN KEY FK_E47C5959216966DF');
        $this->addSql('DROP INDEX IDX_E47C5959216966DF ON police');
        $this->addSql('ALTER TABLE police DROP sinistre_id');
    }
}
