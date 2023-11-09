<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109223849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE group_conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE group_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE group_conversation (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE group_message (id INT NOT NULL, author_id INT NOT NULL, associated_to_group_conversation_id INT NOT NULL, content TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_30BD6473F675F31B ON group_message (author_id)');
        $this->addSql('CREATE INDEX IDX_30BD6473E3ADDBD0 ON group_message (associated_to_group_conversation_id)');
        $this->addSql('ALTER TABLE group_message ADD CONSTRAINT FK_30BD6473F675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_message ADD CONSTRAINT FK_30BD6473E3ADDBD0 FOREIGN KEY (associated_to_group_conversation_id) REFERENCES group_conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE group_conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE group_message_id_seq CASCADE');
        $this->addSql('ALTER TABLE group_message DROP CONSTRAINT FK_30BD6473F675F31B');
        $this->addSql('ALTER TABLE group_message DROP CONSTRAINT FK_30BD6473E3ADDBD0');
        $this->addSql('DROP TABLE group_conversation');
        $this->addSql('DROP TABLE group_message');
    }
}
