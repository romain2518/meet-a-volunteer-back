<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220719154507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE experience_thematic (experience_id INT NOT NULL, thematic_id INT NOT NULL, INDEX IDX_625806F146E90E27 (experience_id), INDEX IDX_625806F12395FCED (thematic_id), PRIMARY KEY(experience_id, thematic_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE experience_thematic ADD CONSTRAINT FK_625806F146E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience_thematic ADD CONSTRAINT FK_625806F12395FCED FOREIGN KEY (thematic_id) REFERENCES thematic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE experience ADD user_id INT NOT NULL, ADD volunteering_type_id INT DEFAULT NULL, ADD reception_structure_id INT DEFAULT NULL, CHANGE year year YEAR, CHANGE duration duration timestamp, CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C103A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C10349AC5322 FOREIGN KEY (volunteering_type_id) REFERENCES volunteering_type (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C1034DFB40A2 FOREIGN KEY (reception_structure_id) REFERENCES reception_structure (id)');
        $this->addSql('CREATE INDEX IDX_590C103A76ED395 ON experience (user_id)');
        $this->addSql('CREATE INDEX IDX_590C10349AC5322 ON experience (volunteering_type_id)');
        $this->addSql('CREATE INDEX IDX_590C1034DFB40A2 ON experience (reception_structure_id)');
        $this->addSql('ALTER TABLE message ADD user_sender_id INT DEFAULT NULL, ADD user_receiver_id INT DEFAULT NULL, CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF6C43E79 FOREIGN KEY (user_sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F64482423 FOREIGN KEY (user_receiver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF6C43E79 ON message (user_sender_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F64482423 ON message (user_receiver_id)');
        $this->addSql('ALTER TABLE reception_structure CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE thematic CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
        $this->addSql('ALTER TABLE volunteering_type CHANGE created_at created_at timestamp default current_timestamp, CHANGE updated_at updated_at timestamp default current_timestamp on update current_timestamp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE experience_thematic');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C103A76ED395');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C10349AC5322');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C1034DFB40A2');
        $this->addSql('DROP INDEX IDX_590C103A76ED395 ON experience');
        $this->addSql('DROP INDEX IDX_590C10349AC5322 ON experience');
        $this->addSql('DROP INDEX IDX_590C1034DFB40A2 ON experience');
        $this->addSql('ALTER TABLE experience DROP user_id, DROP volunteering_type_id, DROP reception_structure_id, CHANGE year year DATE DEFAULT NULL, CHANGE duration duration DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF6C43E79');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F64482423');
        $this->addSql('DROP INDEX IDX_B6BD307FF6C43E79 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307F64482423 ON message');
        $this->addSql('ALTER TABLE message DROP user_sender_id, DROP user_receiver_id, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE reception_structure CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE thematic CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE volunteering_type CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
