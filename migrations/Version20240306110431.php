<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306110431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance ADD cat_a_id INT DEFAULT NULL, ADD ins_image VARCHAR(255) NOT NULL, CHANGE doa doa JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE assurance ADD CONSTRAINT FK_386829AE26EDA4E9 FOREIGN KEY (cat_a_id) REFERENCES categorie_assurance (id)');
        $this->addSql('CREATE INDEX IDX_386829AE26EDA4E9 ON assurance (cat_a_id)');
        $this->addSql('ALTER TABLE commande ADD doa_com_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD ins_value DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DB4CE15E6 FOREIGN KEY (doa_com_id) REFERENCES assurance (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DB4CE15E6 ON commande (doa_com_id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assurance DROP FOREIGN KEY FK_386829AE26EDA4E9');
        $this->addSql('DROP INDEX IDX_386829AE26EDA4E9 ON assurance');
        $this->addSql('ALTER TABLE assurance DROP cat_a_id, DROP ins_image, CHANGE doa doa VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DB4CE15E6');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('DROP INDEX IDX_6EEAA67DB4CE15E6 ON commande');
        $this->addSql('DROP INDEX IDX_6EEAA67DA76ED395 ON commande');
        $this->addSql('ALTER TABLE commande DROP doa_com_id, DROP user_id, DROP ins_value');
    }
}
