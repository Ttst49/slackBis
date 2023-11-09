<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109224446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_conversation_profile (group_conversation_id INT NOT NULL, profile_id INT NOT NULL, PRIMARY KEY(group_conversation_id, profile_id))');
        $this->addSql('CREATE INDEX IDX_54DD05CBB73F9E4F ON group_conversation_profile (group_conversation_id)');
        $this->addSql('CREATE INDEX IDX_54DD05CBCCFA12B8 ON group_conversation_profile (profile_id)');
        $this->addSql('ALTER TABLE group_conversation_profile ADD CONSTRAINT FK_54DD05CBB73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_conversation_profile ADD CONSTRAINT FK_54DD05CBCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE group_conversation_profile DROP CONSTRAINT FK_54DD05CBB73F9E4F');
        $this->addSql('ALTER TABLE group_conversation_profile DROP CONSTRAINT FK_54DD05CBCCFA12B8');
        $this->addSql('DROP TABLE group_conversation_profile');
    }
}
