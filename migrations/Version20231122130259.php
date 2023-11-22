<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122130259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image_private_message (image_id INT NOT NULL, private_message_id INT NOT NULL, PRIMARY KEY(image_id, private_message_id))');
        $this->addSql('CREATE INDEX IDX_EAB4E20A3DA5256D ON image_private_message (image_id)');
        $this->addSql('CREATE INDEX IDX_EAB4E20A5EBFB95E ON image_private_message (private_message_id)');
        $this->addSql('ALTER TABLE image_private_message ADD CONSTRAINT FK_EAB4E20A3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image_private_message ADD CONSTRAINT FK_EAB4E20A5EBFB95E FOREIGN KEY (private_message_id) REFERENCES private_message (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT fk_c53d045f5ebfb95e');
        $this->addSql('DROP INDEX idx_c53d045f5ebfb95e');
        $this->addSql('ALTER TABLE image DROP private_message_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE image_private_message DROP CONSTRAINT FK_EAB4E20A3DA5256D');
        $this->addSql('ALTER TABLE image_private_message DROP CONSTRAINT FK_EAB4E20A5EBFB95E');
        $this->addSql('DROP TABLE image_private_message');
        $this->addSql('ALTER TABLE image ADD private_message_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT fk_c53d045f5ebfb95e FOREIGN KEY (private_message_id) REFERENCES private_message (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c53d045f5ebfb95e ON image (private_message_id)');
    }
}
