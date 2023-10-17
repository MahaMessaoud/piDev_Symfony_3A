<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308163032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) DEFAULT NULL, start DATE DEFAULT NULL, end DATE DEFAULT NULL, description LONGTEXT DEFAULT NULL, all_day TINYINT(1) DEFAULT NULL, background_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) DEFAULT NULL, border_color VARCHAR(7) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_reclamation (id INT AUTO_INCREMENT NOT NULL, nom_category VARCHAR(255) NOT NULL, description_category VARCHAR(255) NOT NULL, priorite_category VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, nom_user_reclamation VARCHAR(255) NOT NULL, email_user_reclamation VARCHAR(255) NOT NULL, objet_reclamation VARCHAR(255) NOT NULL, texte_reclamation VARCHAR(255) NOT NULL, date_reclamation DATE NOT NULL, INDEX IDX_CE60640412469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, reclamation_id INT DEFAULT NULL, objet_reponse VARCHAR(255) NOT NULL, date_reponse DATE NOT NULL, piece_jointe VARCHAR(255) NOT NULL, contenu_reponse VARCHAR(255) NOT NULL, INDEX IDX_5FB6DEC72D6BA2D9 (reclamation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640412469DE2 FOREIGN KEY (category_id) REFERENCES category_reclamation (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640412469DE2');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC72D6BA2D9');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE category_reclamation');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
    }
}
