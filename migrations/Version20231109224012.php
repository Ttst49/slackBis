<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109224012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_message DROP CONSTRAINT fk_30bd6473e3addbd0');
        $this->addSql('DROP INDEX idx_30bd6473e3addbd0');
        $this->addSql('ALTER TABLE group_message DROP associated_to_group_conversation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE group_message ADD associated_to_group_conversation_id INT NOT NULL');
        $this->addSql('ALTER TABLE group_message ADD CONSTRAINT fk_30bd6473e3addbd0 FOREIGN KEY (associated_to_group_conversation_id) REFERENCES group_conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_30bd6473e3addbd0 ON group_message (associated_to_group_conversation_id)');
    }
}
