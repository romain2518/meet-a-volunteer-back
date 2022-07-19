<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220719142917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reception_structure (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, slug_name VARCHAR(64) NOT NULL, created_at timestamp default current_timestamp, updated_at timestamp default current_timestamp on update current_timestamp, UNIQUE INDEX UNIQ_53AE1D865E237E06 (name), UNIQUE INDEX UNIQ_53AE1D86C6E66724 (slug_name), INDEX reception_structure_name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE experience CHANGE year year YEAR, CHANGE duration duration timestamp, CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE message CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE volunteering_type CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reception_structure');
        $this->addSql('ALTER TABLE experience CHANGE year year DATE DEFAULT NULL, CHANGE duration duration DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE message CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE volunteering_type CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
