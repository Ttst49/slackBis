<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109224921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_conversation_user (group_conversation_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(group_conversation_id, user_id))');
        $this->addSql('CREATE INDEX IDX_84A89A69B73F9E4F ON group_conversation_user (group_conversation_id)');
        $this->addSql('CREATE INDEX IDX_84A89A69A76ED395 ON group_conversation_user (user_id)');
        $this->addSql('ALTER TABLE group_conversation_user ADD CONSTRAINT FK_84A89A69B73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_conversation_user ADD CONSTRAINT FK_84A89A69A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE group_conversation_user DROP CONSTRAINT FK_84A89A69B73F9E4F');
        $this->addSql('ALTER TABLE group_conversation_user DROP CONSTRAINT FK_84A89A69A76ED395');
        $this->addSql('DROP TABLE group_conversation_user');
    }
}
