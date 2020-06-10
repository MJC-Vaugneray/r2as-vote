<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200610202711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE response_type1 (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event_id_id INTEGER NOT NULL, user_id_id INTEGER NOT NULL, proposal_id_id INTEGER NOT NULL, positive INTEGER DEFAULT NULL, negative INTEGER DEFAULT NULL, abstention INTEGER DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_D19472DC3E5F2F7B ON response_type1 (event_id_id)');
        $this->addSql('CREATE INDEX IDX_D19472DC9D86650F ON response_type1 (user_id_id)');
        $this->addSql('CREATE INDEX IDX_D19472DCB8B357F6 ON response_type1 (proposal_id_id)');
        $this->addSql('CREATE TABLE proposal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event_id_id INTEGER NOT NULL, type INTEGER NOT NULL, name VARCHAR(512) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_BFE594723E5F2F7B ON proposal (event_id_id)');
        $this->addSql('CREATE TABLE events (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(1024) DEFAULT NULL, uuid VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, state BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event_id_id INTEGER NOT NULL, mail VARCHAR(255) NOT NULL, factor INTEGER NOT NULL, uuid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_1483A5E93E5F2F7B ON users (event_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE response_type1');
        $this->addSql('DROP TABLE proposal');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE users');
    }
}
