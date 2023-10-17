<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308154238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, nom_activite VARCHAR(255) NOT NULL, duree_activite VARCHAR(255) NOT NULL, tenue_activite VARCHAR(255) NOT NULL, difficulte_activite VARCHAR(255) NOT NULL, image_activite VARCHAR(255) NOT NULL, description_activite VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, nom_cours VARCHAR(255) NOT NULL, nom_coach VARCHAR(255) NOT NULL, age_min_cours INT NOT NULL, prix_cours DOUBLE PRECISION NOT NULL, description_cours VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours_activite (cours_id INT NOT NULL, activite_id INT NOT NULL, INDEX IDX_7FE1FBC87ECF78B0 (cours_id), INDEX IDX_7FE1FBC89B0F88B1 (activite_id), PRIMARY KEY(cours_id, activite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, date_planning DATE NOT NULL, jour_planning VARCHAR(255) NOT NULL, heure_planning INT NOT NULL, INDEX IDX_D499BFF67ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours_activite ADD CONSTRAINT FK_7FE1FBC87ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours_activite ADD CONSTRAINT FK_7FE1FBC89B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF67ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE user DROP is_verified');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours_activite DROP FOREIGN KEY FK_7FE1FBC87ECF78B0');
        $this->addSql('ALTER TABLE cours_activite DROP FOREIGN KEY FK_7FE1FBC89B0F88B1');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF67ECF78B0');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE cours_activite');
        $this->addSql('DROP TABLE planning');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL');
    }
}
