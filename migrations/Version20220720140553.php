<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220720140553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experience CHANGE year year YEAR, CHANGE duration duration timestamp, CHANGE picture picture VARCHAR(64) DEFAULT \'0.jpg\' NOT NULL, CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE message CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE reception_structure CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE thematic CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE volunteering_type CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experience CHANGE year year DATE DEFAULT NULL, CHANGE duration duration DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE picture picture VARCHAR(64) DEFAULT \'0.png\' NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE message CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE reception_structure CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE thematic CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE volunteering_type CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}