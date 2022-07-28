<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728073632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX search_idx ON experience');
        $this->addSql('ALTER TABLE experience DROP city');
        $this->addSql('CREATE INDEX search_idx ON experience (country)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX search_idx ON experience');
        $this->addSql('ALTER TABLE experience ADD city VARCHAR(255) NOT NULL');
        $this->addSql('CREATE INDEX search_idx ON experience (country, city)');
    }
}
