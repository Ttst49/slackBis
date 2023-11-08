<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108175447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE private_conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE private_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE private_conversation (id INT NOT NULL, related_to_profile_a_id INT NOT NULL, related_to_profile_b_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DCF38EEBD4EB2A39 ON private_conversation (related_to_profile_a_id)');
        $this->addSql('CREATE INDEX IDX_DCF38EEBC65E85D7 ON private_conversation (related_to_profile_b_id)');
        $this->addSql('CREATE TABLE private_message (id INT NOT NULL, author_id INT NOT NULL, associated_to_conversation_id INT NOT NULL, content TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4744FC9BF675F31B ON private_message (author_id)');
        $this->addSql('CREATE INDEX IDX_4744FC9B5C4662AF ON private_message (associated_to_conversation_id)');
        $this->addSql('ALTER TABLE private_conversation ADD CONSTRAINT FK_DCF38EEBD4EB2A39 FOREIGN KEY (related_to_profile_a_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_conversation ADD CONSTRAINT FK_DCF38EEBC65E85D7 FOREIGN KEY (related_to_profile_b_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9BF675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9B5C4662AF FOREIGN KEY (associated_to_conversation_id) REFERENCES private_conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE private_conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE private_message_id_seq CASCADE');
        $this->addSql('ALTER TABLE private_conversation DROP CONSTRAINT FK_DCF38EEBD4EB2A39');
        $this->addSql('ALTER TABLE private_conversation DROP CONSTRAINT FK_DCF38EEBC65E85D7');
        $this->addSql('ALTER TABLE private_message DROP CONSTRAINT FK_4744FC9BF675F31B');
        $this->addSql('ALTER TABLE private_message DROP CONSTRAINT FK_4744FC9B5C4662AF');
        $this->addSql('DROP TABLE private_conversation');
        $this->addSql('DROP TABLE private_message');
    }
}
