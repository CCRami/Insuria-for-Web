<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214174735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance ADD cat_a_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE assurance ADD CONSTRAINT FK_386829AE26EDA4E9 FOREIGN KEY (cat_a_id) REFERENCES categorie_assurance (id)');
        $this->addSql('CREATE INDEX IDX_386829AE26EDA4E9 ON assurance (cat_a_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE26EDA4E9');
        $this->addSql('DROP INDEX IDX_386829AE26EDA4E9 ON assurance');
        $this->addSql('ALTER TABLE assurance DROP cat_a_id');
    }
}
