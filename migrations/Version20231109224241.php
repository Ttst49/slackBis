<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109224241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_message ADD group_conversation_id INT NOT NULL');
        $this->addSql('ALTER TABLE group_message ADD CONSTRAINT FK_30BD6473B73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_30BD6473B73F9E4F ON group_message (group_conversation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE group_message DROP CONSTRAINT FK_30BD6473B73F9E4F');
        $this->addSql('DROP INDEX IDX_30BD6473B73F9E4F');
        $this->addSql('ALTER TABLE group_message DROP group_conversation_id');
    }
}
