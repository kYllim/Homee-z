<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

<<<<<<< HEAD:back/migrations/Version20251206164455.php

final class Version20251123175635 extends AbstractMigration
=======
/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251217164429 extends AbstractMigration
>>>>>>> events:back/migrations/Version20251217164429.php
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE shopping_list ALTER household_id DROP NOT NULL');
        $this->addSql('ALTER TABLE shopping_list ALTER creator_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
    }
}
