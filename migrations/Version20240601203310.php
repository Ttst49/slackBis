<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240601203310 extends AbstractMigration
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
        $this->addSql('CREATE SEQUENCE group_conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE group_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE group_message_response_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE private_conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE private_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE private_message_response_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE relation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE channel (id INT NOT NULL, owner_id INT NOT NULL, name TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A2F98E477E3C61F9 ON channel (owner_id)');
        $this->addSql('CREATE TABLE channel_profile (channel_id INT NOT NULL, profile_id INT NOT NULL, PRIMARY KEY(channel_id, profile_id))');
        $this->addSql('CREATE INDEX IDX_3614950E72F5A1AA ON channel_profile (channel_id)');
        $this->addSql('CREATE INDEX IDX_3614950ECCFA12B8 ON channel_profile (profile_id)');
        $this->addSql('CREATE TABLE channel_user (channel_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(channel_id, user_id))');
        $this->addSql('CREATE INDEX IDX_11C7753772F5A1AA ON channel_user (channel_id)');
        $this->addSql('CREATE INDEX IDX_11C77537A76ED395 ON channel_user (user_id)');
        $this->addSql('CREATE TABLE channel_message (id INT NOT NULL, author_id INT NOT NULL, associated_to_channel_id INT NOT NULL, content TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1FE0F7EF675F31B ON channel_message (author_id)');
        $this->addSql('CREATE INDEX IDX_1FE0F7EFD049707 ON channel_message (associated_to_channel_id)');
        $this->addSql('CREATE TABLE group_conversation (id INT NOT NULL, owner_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_66C86CD07E3C61F9 ON group_conversation (owner_id)');
        $this->addSql('CREATE TABLE group_conversation_profile (group_conversation_id INT NOT NULL, profile_id INT NOT NULL, PRIMARY KEY(group_conversation_id, profile_id))');
        $this->addSql('CREATE INDEX IDX_54DD05CBB73F9E4F ON group_conversation_profile (group_conversation_id)');
        $this->addSql('CREATE INDEX IDX_54DD05CBCCFA12B8 ON group_conversation_profile (profile_id)');
        $this->addSql('CREATE TABLE group_conversation_user (group_conversation_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(group_conversation_id, user_id))');
        $this->addSql('CREATE INDEX IDX_84A89A69B73F9E4F ON group_conversation_user (group_conversation_id)');
        $this->addSql('CREATE INDEX IDX_84A89A69A76ED395 ON group_conversation_user (user_id)');
        $this->addSql('CREATE TABLE group_message (id INT NOT NULL, author_id INT NOT NULL, group_conversation_id INT NOT NULL, content TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_30BD6473F675F31B ON group_message (author_id)');
        $this->addSql('CREATE INDEX IDX_30BD6473B73F9E4F ON group_message (group_conversation_id)');
        $this->addSql('CREATE TABLE group_message_response (id INT NOT NULL, author_id INT NOT NULL, related_to_group_message_id INT NOT NULL, content TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C8EC3607F675F31B ON group_message_response (author_id)');
        $this->addSql('CREATE INDEX IDX_C8EC360737E3EDCC ON group_message_response (related_to_group_message_id)');
        $this->addSql('CREATE TABLE image (id INT NOT NULL, uploaded_by_id INT NOT NULL, private_message_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C53D045FA2B28FE8 ON image (uploaded_by_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F5EBFB95E ON image (private_message_id)');
        $this->addSql('COMMENT ON COLUMN image.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE private_conversation (id INT NOT NULL, related_to_profile_a_id INT NOT NULL, related_to_profile_b_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DCF38EEBD4EB2A39 ON private_conversation (related_to_profile_a_id)');
        $this->addSql('CREATE INDEX IDX_DCF38EEBC65E85D7 ON private_conversation (related_to_profile_b_id)');
        $this->addSql('CREATE TABLE private_message (id INT NOT NULL, author_id INT NOT NULL, associated_to_conversation_id INT NOT NULL, content TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4744FC9BF675F31B ON private_message (author_id)');
        $this->addSql('CREATE INDEX IDX_4744FC9B5C4662AF ON private_message (associated_to_conversation_id)');
        $this->addSql('CREATE TABLE private_message_response (id INT NOT NULL, author_id INT NOT NULL, related_to_private_message_id INT NOT NULL, content TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_52A6F8C3F675F31B ON private_message_response (author_id)');
        $this->addSql('CREATE INDEX IDX_52A6F8C3CBF54F4C ON private_message_response (related_to_private_message_id)');
        $this->addSql('CREATE TABLE profile (id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, visibility BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE relation (id INT NOT NULL, user_a_id INT NOT NULL, user_b_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62894749415F1F91 ON relation (user_a_id)');
        $this->addSql('CREATE INDEX IDX_6289474953EAB07F ON relation (user_b_id)');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, recipient_id INT NOT NULL, sender_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3B978F9FE92F8F78 ON request (recipient_id)');
        $this->addSql('CREATE INDEX IDX_3B978F9FF624B39D ON request (sender_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, profile_id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649CCFA12B8 ON "user" (profile_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E477E3C61F9 FOREIGN KEY (owner_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_profile ADD CONSTRAINT FK_3614950E72F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_profile ADD CONSTRAINT FK_3614950ECCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_user ADD CONSTRAINT FK_11C7753772F5A1AA FOREIGN KEY (channel_id) REFERENCES channel (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_user ADD CONSTRAINT FK_11C77537A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_message ADD CONSTRAINT FK_1FE0F7EF675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE channel_message ADD CONSTRAINT FK_1FE0F7EFD049707 FOREIGN KEY (associated_to_channel_id) REFERENCES channel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_conversation ADD CONSTRAINT FK_66C86CD07E3C61F9 FOREIGN KEY (owner_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_conversation_profile ADD CONSTRAINT FK_54DD05CBB73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_conversation_profile ADD CONSTRAINT FK_54DD05CBCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_conversation_user ADD CONSTRAINT FK_84A89A69B73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_conversation_user ADD CONSTRAINT FK_84A89A69A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_message ADD CONSTRAINT FK_30BD6473F675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_message ADD CONSTRAINT FK_30BD6473B73F9E4F FOREIGN KEY (group_conversation_id) REFERENCES group_conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_message_response ADD CONSTRAINT FK_C8EC3607F675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_message_response ADD CONSTRAINT FK_C8EC360737E3EDCC FOREIGN KEY (related_to_group_message_id) REFERENCES group_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA2B28FE8 FOREIGN KEY (uploaded_by_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F5EBFB95E FOREIGN KEY (private_message_id) REFERENCES private_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_conversation ADD CONSTRAINT FK_DCF38EEBD4EB2A39 FOREIGN KEY (related_to_profile_a_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_conversation ADD CONSTRAINT FK_DCF38EEBC65E85D7 FOREIGN KEY (related_to_profile_b_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9BF675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_message ADD CONSTRAINT FK_4744FC9B5C4662AF FOREIGN KEY (associated_to_conversation_id) REFERENCES private_conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_message_response ADD CONSTRAINT FK_52A6F8C3F675F31B FOREIGN KEY (author_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE private_message_response ADD CONSTRAINT FK_52A6F8C3CBF54F4C FOREIGN KEY (related_to_private_message_id) REFERENCES private_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749415F1F91 FOREIGN KEY (user_a_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_6289474953EAB07F FOREIGN KEY (user_b_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE request ADD CONSTRAINT FK_3B978F9FF624B39D FOREIGN KEY (sender_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE channel_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE channel_message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE group_conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE group_message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE group_message_response_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE private_conversation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE private_message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE private_message_response_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE profile_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE relation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE channel DROP CONSTRAINT FK_A2F98E477E3C61F9');
        $this->addSql('ALTER TABLE channel_profile DROP CONSTRAINT FK_3614950E72F5A1AA');
        $this->addSql('ALTER TABLE channel_profile DROP CONSTRAINT FK_3614950ECCFA12B8');
        $this->addSql('ALTER TABLE channel_user DROP CONSTRAINT FK_11C7753772F5A1AA');
        $this->addSql('ALTER TABLE channel_user DROP CONSTRAINT FK_11C77537A76ED395');
        $this->addSql('ALTER TABLE channel_message DROP CONSTRAINT FK_1FE0F7EF675F31B');
        $this->addSql('ALTER TABLE channel_message DROP CONSTRAINT FK_1FE0F7EFD049707');
        $this->addSql('ALTER TABLE group_conversation DROP CONSTRAINT FK_66C86CD07E3C61F9');
        $this->addSql('ALTER TABLE group_conversation_profile DROP CONSTRAINT FK_54DD05CBB73F9E4F');
        $this->addSql('ALTER TABLE group_conversation_profile DROP CONSTRAINT FK_54DD05CBCCFA12B8');
        $this->addSql('ALTER TABLE group_conversation_user DROP CONSTRAINT FK_84A89A69B73F9E4F');
        $this->addSql('ALTER TABLE group_conversation_user DROP CONSTRAINT FK_84A89A69A76ED395');
        $this->addSql('ALTER TABLE group_message DROP CONSTRAINT FK_30BD6473F675F31B');
        $this->addSql('ALTER TABLE group_message DROP CONSTRAINT FK_30BD6473B73F9E4F');
        $this->addSql('ALTER TABLE group_message_response DROP CONSTRAINT FK_C8EC3607F675F31B');
        $this->addSql('ALTER TABLE group_message_response DROP CONSTRAINT FK_C8EC360737E3EDCC');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045FA2B28FE8');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F5EBFB95E');
        $this->addSql('ALTER TABLE private_conversation DROP CONSTRAINT FK_DCF38EEBD4EB2A39');
        $this->addSql('ALTER TABLE private_conversation DROP CONSTRAINT FK_DCF38EEBC65E85D7');
        $this->addSql('ALTER TABLE private_message DROP CONSTRAINT FK_4744FC9BF675F31B');
        $this->addSql('ALTER TABLE private_message DROP CONSTRAINT FK_4744FC9B5C4662AF');
        $this->addSql('ALTER TABLE private_message_response DROP CONSTRAINT FK_52A6F8C3F675F31B');
        $this->addSql('ALTER TABLE private_message_response DROP CONSTRAINT FK_52A6F8C3CBF54F4C');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_62894749415F1F91');
        $this->addSql('ALTER TABLE relation DROP CONSTRAINT FK_6289474953EAB07F');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FE92F8F78');
        $this->addSql('ALTER TABLE request DROP CONSTRAINT FK_3B978F9FF624B39D');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649CCFA12B8');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE channel_profile');
        $this->addSql('DROP TABLE channel_user');
        $this->addSql('DROP TABLE channel_message');
        $this->addSql('DROP TABLE group_conversation');
        $this->addSql('DROP TABLE group_conversation_profile');
        $this->addSql('DROP TABLE group_conversation_user');
        $this->addSql('DROP TABLE group_message');
        $this->addSql('DROP TABLE group_message_response');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE private_conversation');
        $this->addSql('DROP TABLE private_message');
        $this->addSql('DROP TABLE private_message_response');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE request');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
