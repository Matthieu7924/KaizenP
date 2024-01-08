<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108125300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, montant INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, code VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_produit (promotion_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_71D81A1D139DF194 (promotion_id), INDEX IDX_71D81A1DF347EFB (produit_id), PRIMARY KEY(promotion_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promotion_produit ADD CONSTRAINT FK_71D81A1D139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_produit ADD CONSTRAINT FK_71D81A1DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion_produit DROP FOREIGN KEY FK_71D81A1D139DF194');
        $this->addSql('ALTER TABLE promotion_produit DROP FOREIGN KEY FK_71D81A1DF347EFB');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_produit');
    }
}
