<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260107220614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE event ADD assigned_to_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7F4BD7827 ON event (assigned_to_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7F4BD7827');
        $this->addSql('DROP INDEX IDX_3BAE0AA7F4BD7827');
        $this->addSql('ALTER TABLE event DROP assigned_to_id');
    }
}
