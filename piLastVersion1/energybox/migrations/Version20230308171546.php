<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308171546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, pack_id INT NOT NULL, user_id INT DEFAULT NULL, date_achat DATE NOT NULL, date_fin DATE NOT NULL, etat_abonnement VARCHAR(255) NOT NULL, code_promo VARCHAR(255) DEFAULT NULL, montant_abonnement DOUBLE PRECISION NOT NULL, INDEX IDX_351268BB1919B217 (pack_id), INDEX IDX_351268BBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pack (id INT AUTO_INCREMENT NOT NULL, type_pack VARCHAR(255) NOT NULL, montant_pack DOUBLE PRECISION NOT NULL, duree_pack INT NOT NULL, description_pack VARCHAR(255) NOT NULL, places_pack INT NOT NULL, disponibilite_pack INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, code_promotion VARCHAR(255) NOT NULL, reduction_promotion DOUBLE PRECISION NOT NULL, date_expiration DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB1919B217 FOREIGN KEY (pack_id) REFERENCES pack (id)');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cours ADD coach_name_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CCAC7749E FOREIGN KEY (coach_name_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CCAC7749E ON cours (coach_name_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB1919B217');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBA76ED395');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE pack');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CCAC7749E');
        $this->addSql('DROP INDEX IDX_FDCA8C9CCAC7749E ON cours');
        $this->addSql('ALTER TABLE cours DROP coach_name_id');
    }
}
