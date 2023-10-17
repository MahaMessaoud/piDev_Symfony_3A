<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309144450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE charge (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT NOT NULL, materiel_id INT NOT NULL, quantite_charge INT NOT NULL, date_arrivage_charge DATE NOT NULL, INDEX IDX_556BA434670C757F (fournisseur_id), INDEX IDX_556BA43416880AAF (materiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, description LONGTEXT NOT NULL, date_com DATE NOT NULL, INDEX IDX_9474526C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom_fournisseur VARCHAR(255) NOT NULL, contact_fournisseur INT NOT NULL, email_fournisseur VARCHAR(255) NOT NULL, adresse_fournisseur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, nom_materiel VARCHAR(255) NOT NULL, reference_materiel VARCHAR(255) NOT NULL, date_maintenance_materiel DATE NOT NULL, quantite_materiel INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, details VARCHAR(500) NOT NULL, image VARCHAR(255) NOT NULL, date_post DATE NOT NULL, rate INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE charge ADD CONSTRAINT FK_556BA434670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE charge ADD CONSTRAINT FK_556BA43416880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge DROP FOREIGN KEY FK_556BA434670C757F');
        $this->addSql('ALTER TABLE charge DROP FOREIGN KEY FK_556BA43416880AAF');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('DROP TABLE charge');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE post');
    }
}
