<?php

declare(strict_types=1);

namespace app\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190720114838 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE polls (id INT NOT NULL, question VARCHAR(255) NOT NULL, uid uuid NOT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D3CC6EE539B0606 ON polls (uid);');
        $this->addSql('CREATE TABLE poll_votes (id INT NOT NULL, poll_id INT DEFAULT NULL, answer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE INDEX IDX_373A070E3C947C0F ON poll_votes (poll_id);');
        $this->addSql('CREATE INDEX IDX_373A070EAA334807 ON poll_votes (answer_id);');
        $this->addSql('CREATE TABLE poll_answers (id INT NOT NULL, poll_id INT DEFAULT NULL, answer VARCHAR(255) NOT NULL, PRIMARY KEY(id));');
        $this->addSql('CREATE INDEX IDX_AC854B393C947C0F ON poll_answers (poll_id);');
        $this->addSql('CREATE SEQUENCE polls_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->addSql('CREATE SEQUENCE poll_votes_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->addSql('CREATE SEQUENCE poll_answers_id_seq INCREMENT BY 1 MINVALUE 1 START 1;');
        $this->addSql('ALTER TABLE poll_votes ADD CONSTRAINT FK_373A070E3C947C0F FOREIGN KEY (poll_id) REFERENCES polls (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('ALTER TABLE poll_votes ADD CONSTRAINT FK_373A070EAA334807 FOREIGN KEY (answer_id) REFERENCES poll_answers (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('ALTER TABLE poll_answers ADD CONSTRAINT FK_AC854B393C947C0F FOREIGN KEY (poll_id) REFERENCES polls (id) NOT DEFERRABLE INITIALLY IMMEDIATE;');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE poll_votes DROP CONSTRAINT FK_373A070E3C947C0F');
        $this->addSql('ALTER TABLE poll_answers DROP CONSTRAINT FK_AC854B393C947C0F');
        $this->addSql('ALTER TABLE poll_votes DROP CONSTRAINT FK_373A070EAA334807');
        $this->addSql('DROP TABLE polls');
        $this->addSql('DROP TABLE poll_votes');
        $this->addSql('DROP TABLE poll_answers');
        $this->addSql('DROP SEQUENCE polls_id_seq');
        $this->addSql('DROP SEQUENCE poll_votes_id_seq');
        $this->addSql('DROP SEQUENCE poll_answers_id_seq');

    }
}
