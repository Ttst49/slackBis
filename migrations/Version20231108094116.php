<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108094116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE profile (id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE profile_profile (profile_source INT NOT NULL, profile_target INT NOT NULL, PRIMARY KEY(profile_source, profile_target))');
        $this->addSql('CREATE INDEX IDX_52E9749337A01814 ON profile_profile (profile_source)');
        $this->addSql('CREATE INDEX IDX_52E974932E45489B ON profile_profile (profile_target)');
        $this->addSql('ALTER TABLE profile_profile ADD CONSTRAINT FK_52E9749337A01814 FOREIGN KEY (profile_source) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile_profile ADD CONSTRAINT FK_52E974932E45489B FOREIGN KEY (profile_target) REFERENCES profile (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD profile_id INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649CCFA12B8 ON "user" (profile_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649CCFA12B8');
        $this->addSql('DROP SEQUENCE profile_id_seq CASCADE');
        $this->addSql('ALTER TABLE profile_profile DROP CONSTRAINT FK_52E9749337A01814');
        $this->addSql('ALTER TABLE profile_profile DROP CONSTRAINT FK_52E974932E45489B');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE profile_profile');
        $this->addSql('DROP INDEX UNIQ_8D93D649CCFA12B8');
        $this->addSql('ALTER TABLE "user" DROP profile_id');
    }
}
