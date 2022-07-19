<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220719132411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD pseudo_slug VARCHAR(30) NOT NULL, ADD firstname VARCHAR(64) NOT NULL, ADD lastname VARCHAR(64) NOT NULL, ADD age DATETIME NOT NULL, ADD profile_picture VARCHAR(64) NOT NULL, ADD email VARCHAR(180) NOT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD biography VARCHAR(250) DEFAULT NULL, ADD native_country VARCHAR(255) NOT NULL, ADD created_at timestamp default current_timestamp, ADD updated_at timestamp default current_timestamp on update current_timestamp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP pseudo_slug, DROP firstname, DROP lastname, DROP age, DROP profile_picture, DROP email, DROP phone, DROP biography, DROP native_country, DROP created_at, DROP updated_at');
    }
}
