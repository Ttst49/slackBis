<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122084220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE channel ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E477E3C61F9 FOREIGN KEY (owner_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A2F98E477E3C61F9 ON channel (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE channel DROP CONSTRAINT FK_A2F98E477E3C61F9');
        $this->addSql('DROP INDEX IDX_A2F98E477E3C61F9');
        $this->addSql('ALTER TABLE channel DROP owner_id');
        $this->addSql('ALTER TABLE channel DROP created_at');
    }
}
