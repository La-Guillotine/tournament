<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209164400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, stadium_id INT NOT NULL, league_id INT NOT NULL, secretary_id INT NOT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(4) NOT NULL, logo VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_B8EE38727E860E36 (stadium_id), INDEX IDX_B8EE387258AFC4DE (league_id), UNIQUE INDEX UNIQ_B8EE3872A2A63DB2 (secretary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, club_id INT NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_5E90F6D633D1A3E7 (tournament_id), INDEX IDX_5E90F6D661190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE league (id INT AUTO_INCREMENT NOT NULL, responsible_id INT NOT NULL, name VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3EB4C318602AD315 (responsible_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stadium (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, league_id INT NOT NULL, stadium_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, nb_max_team INT NOT NULL, description LONGTEXT DEFAULT NULL, age_category VARCHAR(20) DEFAULT NULL, INDEX IDX_BD5FB8D958AFC4DE (league_id), INDEX IDX_BD5FB8D97E860E36 (stadium_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, tel VARCHAR(10) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE38727E860E36 FOREIGN KEY (stadium_id) REFERENCES stadium (id)');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE387258AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE3872A2A63DB2 FOREIGN KEY (secretary_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D633D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D661190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C318602AD315 FOREIGN KEY (responsible_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D958AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D97E860E36 FOREIGN KEY (stadium_id) REFERENCES stadium (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D661190A32');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE387258AFC4DE');
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D958AFC4DE');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE38727E860E36');
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D97E860E36');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D633D1A3E7');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE3872A2A63DB2');
        $this->addSql('ALTER TABLE league DROP FOREIGN KEY FK_3EB4C318602AD315');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE stadium');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE user');
    }
}
