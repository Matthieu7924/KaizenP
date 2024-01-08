<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108123619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, cp VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD magasin_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D20096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D20096AE3 ON commande (magasin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D20096AE3');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP INDEX IDX_6EEAA67D20096AE3 ON commande');
        $this->addSql('ALTER TABLE commande DROP magasin_id');
    }
}
