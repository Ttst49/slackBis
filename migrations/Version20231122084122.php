<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122084122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE channel_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE channel_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE channel (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE channel_profile (channel_id INT NOT NULL, profile_id INT NOT NULL, PRIMARY KEY(channel_id, profile_id))');
        $this->addSql('CREATE INDEX IDX_3614950E72F5A1AA ON channel_profile (channel_id)');
        $this->addSql('CREATE INDEX IDX_3614950ECCFA12B8 ON channel_profile (profile_id)');
        $this->addSql('CREATE TABLE channel_user (channel_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(channel_id, user_id))');
        $this->addSql('CREATE INDEX IDX_11C7753772F5A1AA ON channel_user (channel_id)');
        $this->addSql('CREATE INDEX IDX_11C77537A76ED395 ON channel_user (user_id)');
        $this->addSql('CREATE TABLE channel_message (id INT NOT NULL, author_id INT NOT NULL, associated_to_channel_id INT NOT NULL, content TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1FE0F7EF675F31B ON channel_message (author_id)');
        $this->addSql('CREATE INDEX IDX_1FE0F7EFD049707 ON channel_message (associated_to_channel_id)');
        $this->addSql('ALTER TABLE channel_profile ADD CONSTRAINT FK_3614950E72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_profile ADD CONSTRAINT FK_3614950ECCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_user ADD CONSTRAINT FK_11C7753772F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_user ADD CONSTRAINT FK_11C77537A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_message ADD CONSTRAINT FK_1FE0F7EF675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_message ADD CONSTRAINT FK_1FE0F7EFD049707 FOREIGN KEY (associated_to_channel_id) REFERENCES channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE channel_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE channel_message_id_seq CASCADE');
        $this->addSql('ALTER TABLE channel_profile DROP CONSTRAINT FK_3614950E72F5A1AA');
        $this->addSql('ALTER TABLE channel_profile DROP CONSTRAINT FK_3614950ECCFA12B8');
        $this->addSql('ALTER TABLE channel_user DROP CONSTRAINT FK_11C7753772F5A1AA');
        $this->addSql('ALTER TABLE channel_user DROP CONSTRAINT FK_11C77537A76ED395');
        $this->addSql('ALTER TABLE channel_message DROP CONSTRAINT FK_1FE0F7EF675F31B');
        $this->addSql('ALTER TABLE channel_message DROP CONSTRAINT FK_1FE0F7EFD049707');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE channel_profile');
        $this->addSql('DROP TABLE channel_user');
        $this->addSql('DROP TABLE channel_message');
    }
}
