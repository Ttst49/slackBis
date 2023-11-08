<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108104634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, recipient_id INT NOT NULL, sender_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3B978F9FE92F8F78 ON request (recipient_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FF624B39D ON request (sender_id)');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FF624B39D FOREIGN KEY (sender_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FE92F8F78');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FF624B39D');
        $this->addSql('DROP TABLE request');
    }
}
