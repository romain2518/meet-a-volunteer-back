<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220719141202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, slug_title VARCHAR(100) NOT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, year YEAR, duration timestamp, feedback LONGTEXT NOT NULL, views INT UNSIGNED DEFAULT 0 NOT NULL, picture VARCHAR(64) DEFAULT \'0.png\' NOT NULL, participation_fee INT UNSIGNED NOT NULL, is_hosted VARCHAR(64) NOT NULL, is_fed VARCHAR(64) NOT NULL, language LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', created_at timestamp default current_timestamp, updated_at timestamp default current_timestamp on update current_timestamp, INDEX search_idx (country, city), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE experience');
        $this->addSql('ALTER TABLE message CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
