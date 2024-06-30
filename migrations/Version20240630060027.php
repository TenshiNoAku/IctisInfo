<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240630060027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE audience_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE class_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE schedule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE audience (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE class_type (id INT NOT NULL, class_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE schedule (id INT NOT NULL, class_type_id INT NOT NULL, audience_id INT NOT NULL, class_time_id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A3811FB39EB6F ON schedule (class_type_id)');
        $this->addSql('CREATE INDEX IDX_5A3811FB848CC616 ON schedule (audience_id)');
        $this->addSql('CREATE INDEX IDX_5A3811FB9B9FBAC7 ON schedule (class_time_id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB39EB6F FOREIGN KEY (class_type_id) REFERENCES class_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB848CC616 FOREIGN KEY (audience_id) REFERENCES audience (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB9B9FBAC7 FOREIGN KEY (class_time_id) REFERENCES class_time (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE audience_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE class_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE schedule_id_seq CASCADE');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB39EB6F');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB848CC616');
        $this->addSql('ALTER TABLE schedule DROP CONSTRAINT FK_5A3811FB9B9FBAC7');
        $this->addSql('DROP TABLE audience');
        $this->addSql('DROP TABLE class_type');
        $this->addSql('DROP TABLE schedule');
    }
}
