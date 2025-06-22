<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622094708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, person_id INT DEFAULT NULL, team_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, minute INT NOT NULL, description LONGTEXT DEFAULT NULL, period VARCHAR(255) DEFAULT NULL, INDEX IDX_3BAE0AA7E48FD905 (game_id), INDEX IDX_3BAE0AA7217BBB47 (person_id), INDEX IDX_3BAE0AA7296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, local_team_id INT DEFAULT NULL, visitor_team_id INT DEFAULT NULL, date_time DATETIME NOT NULL, location VARCHAR(255) NOT NULL, result_local INT DEFAULT NULL, result_visitor INT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_232B318CB4B9DD23 (local_team_id), INDEX IDX_232B318CEB7F4866 (visitor_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, position VARCHAR(255) DEFAULT NULL, number INT DEFAULT NULL, birth_date DATE DEFAULT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_34DCD176296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name LONGTEXT NOT NULL, logo LONGTEXT DEFAULT NULL, category LONGTEXT NOT NULL, INDEX IDX_C4E0A61FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_team_role (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, team_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_8668F3F5A76ED395 (user_id), INDEX IDX_8668F3F5296CD8AE (team_id), INDEX IDX_8668F3F5D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game ADD CONSTRAINT FK_232B318CB4B9DD23 FOREIGN KEY (local_team_id) REFERENCES team (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game ADD CONSTRAINT FK_232B318CEB7F4866 FOREIGN KEY (visitor_team_id) REFERENCES team (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE person ADD CONSTRAINT FK_34DCD176296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_team_role ADD CONSTRAINT FK_8668F3F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_team_role ADD CONSTRAINT FK_8668F3F5296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_team_role ADD CONSTRAINT FK_8668F3F5D60322AC FOREIGN KEY (role_id) REFERENCES role (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7E48FD905
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7217BBB47
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7296CD8AE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game DROP FOREIGN KEY FK_232B318CB4B9DD23
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game DROP FOREIGN KEY FK_232B318CEB7F4866
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE person DROP FOREIGN KEY FK_34DCD176296CD8AE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_team_role DROP FOREIGN KEY FK_8668F3F5A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_team_role DROP FOREIGN KEY FK_8668F3F5296CD8AE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_team_role DROP FOREIGN KEY FK_8668F3F5D60322AC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE game
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE person
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE team
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_team_role
        SQL);
    }
}
