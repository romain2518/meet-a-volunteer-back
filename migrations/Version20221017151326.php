<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221017151326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, volunteering_type_id INT DEFAULT NULL, reception_structure_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, slug_title VARCHAR(100) NOT NULL, country VARCHAR(190) NOT NULL, year INT NOT NULL, duration VARCHAR(64) NOT NULL, feedback LONGTEXT NOT NULL, views INT UNSIGNED DEFAULT 0 NOT NULL, picture VARCHAR(64) NOT NULL, participation_fee INT UNSIGNED NOT NULL, is_hosted VARCHAR(64) NOT NULL, is_fed VARCHAR(64) NOT NULL, language LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_590C103A76ED395 (user_id), INDEX IDX_590C10349AC5322 (volunteering_type_id), INDEX IDX_590C1034DFB40A2 (reception_structure_id), INDEX search_idx (country), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experience_thematic (experience_id INT NOT NULL, thematic_id INT NOT NULL, INDEX IDX_625806F146E90E27 (experience_id), INDEX IDX_625806F12395FCED (thematic_id), PRIMARY KEY(experience_id, thematic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_sender_id INT DEFAULT NULL, user_receiver_id INT DEFAULT NULL, message LONGTEXT NOT NULL, is_read TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B6BD307FF6C43E79 (user_sender_id), INDEX IDX_B6BD307F64482423 (user_receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reception_structure (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, slug_name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_53AE1D865E237E06 (name), UNIQUE INDEX UNIQ_53AE1D86C6E66724 (slug_name), INDEX reception_structure_name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thematic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, slug_name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATE DEFAULT NULL, UNIQUE INDEX UNIQ_7C1CDF725E237E06 (name), UNIQUE INDEX UNIQ_7C1CDF72C6E66724 (slug_name), INDEX thematic_name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, pseudo_slug VARCHAR(30) NOT NULL, firstname VARCHAR(64) NOT NULL, lastname VARCHAR(64) NOT NULL, age DATETIME NOT NULL, profile_picture VARCHAR(64) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(255) DEFAULT NULL, biography VARCHAR(250) DEFAULT NULL, native_country VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), UNIQUE INDEX UNIQ_8D93D649FF5E4F86 (pseudo_slug), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX native_country_idx (native_country), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteering_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, slug_name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D98742C95E237E06 (name), UNIQUE INDEX UNIQ_D98742C9C6E66724 (slug_name), INDEX volunteering_name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C10349AC5322 FOREIGN KEY (volunteering_type_id) REFERENCES volunteering_type (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C1034DFB40A2 FOREIGN KEY (reception_structure_id) REFERENCES reception_structure (id)');
        $this->addSql('ALTER TABLE experience_thematic ADD CONSTRAINT FK_625806F146E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_thematic ADD CONSTRAINT FK_625806F12395FCED FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF6C43E79 FOREIGN KEY (user_sender_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F64482423 FOREIGN KEY (user_receiver_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103A76ED395');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C10349AC5322');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C1034DFB40A2');
        $this->addSql('ALTER TABLE experience_thematic DROP FOREIGN KEY FK_625806F146E90E27');
        $this->addSql('ALTER TABLE experience_thematic DROP FOREIGN KEY FK_625806F12395FCED');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF6C43E79');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F64482423');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE experience_thematic');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE reception_structure');
        $this->addSql('DROP TABLE thematic');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE volunteering_type');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
