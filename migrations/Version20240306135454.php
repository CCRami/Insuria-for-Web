<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306135454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance ADD pol_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE assurance ADD CONSTRAINT FK_386829AE49721867 FOREIGN KEY (pol_id) REFERENCES police (id)');
        $this->addSql('CREATE INDEX IDX_386829AE49721867 ON assurance (pol_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE49721867');
        $this->addSql('DROP INDEX IDX_386829AE49721867 ON assurance');
        $this->addSql('ALTER TABLE assurance DROP pol_id');
    }
}
