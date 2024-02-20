<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218124721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance CHANGE doa doa JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE commande ADD doa_com_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DB4CE15E6 FOREIGN KEY (doa_com_id) REFERENCES assurance (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6EEAA67DB4CE15E6 ON commande (doa_com_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance CHANGE doa doa VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DB4CE15E6');
        $this->addSql('DROP INDEX UNIQ_6EEAA67DB4CE15E6 ON commande');
        $this->addSql('ALTER TABLE commande DROP doa_com_id');
    }
}
