<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508022630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE reset');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB1919B217');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBA76ED395');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB1919B217 FOREIGN KEY (pack_id) REFERENCES pack (id)');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE menu CHANGE user_id user_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A207A21214B7');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A207A21214B7 FOREIGN KEY (categories_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post DROP moy_rate, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955839E70E');
        $this->addSql('ALTER TABLE reservation DROP userid');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955839E70E FOREIGN KEY (idplat_id) REFERENCES plat (id)');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA37B39D312');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA37B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id)');
        $this->addSql('ALTER TABLE user DROP qr_code, DROP pathQr, CHANGE is_blocked is_blocked TINYINT(1) NOT NULL, CHANGE is_approved is_approved TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoris (idFavoris INT AUTO_INCREMENT NOT NULL, idPlatF INT NOT NULL, Nomplat VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nbr INT NOT NULL, PRIMARY KEY(idFavoris)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reset (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, code INT NOT NULL, timeMils VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB1919B217');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBA76ED395');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB1919B217 FOREIGN KEY (pack_id) REFERENCES pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE user_id user_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A207A21214B7');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A207A21214B7 FOREIGN KEY (categories_id) REFERENCES menu (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post ADD moy_rate DOUBLE PRECISION NOT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955839E70E');
        $this->addSql('ALTER TABLE reservation ADD userid INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955839E70E FOREIGN KEY (idplat_id) REFERENCES plat (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA37B39D312');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA37B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD qr_code VARCHAR(255) NOT NULL, ADD pathQr VARCHAR(255) NOT NULL, CHANGE is_blocked is_blocked TINYINT(1) DEFAULT NULL, CHANGE is_approved is_approved TINYINT(1) DEFAULT NULL');
    }
}
