<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309112435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, categories VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, descriptionmenu VARCHAR(255) NOT NULL, nbplats INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plat (id INT AUTO_INCREMENT NOT NULL, categories_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, calories VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, nbp INT DEFAULT NULL, INDEX IDX_2038A207A21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, idplat_id INT DEFAULT NULL, date DATE NOT NULL, userid VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_42C84955839E70E (idplat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A207A21214B7 FOREIGN KEY (categories_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955839E70E FOREIGN KEY (idplat_id) REFERENCES plat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A207A21214B7');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955839E70E');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE plat');
        $this->addSql('DROP TABLE reservation');
    }
}
