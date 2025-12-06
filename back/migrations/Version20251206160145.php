<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251206160145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alert (id SERIAL NOT NULL, member_id INT NOT NULL, event_id INT DEFAULT NULL, chore_id INT DEFAULT NULL, shopping_list_id INT DEFAULT NULL, alert_type VARCHAR(255) NOT NULL, message TEXT NOT NULL, channel VARCHAR(255) NOT NULL, frequency VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_17FD46C17597D3FE ON alert (member_id)');
        $this->addSql('CREATE INDEX IDX_17FD46C171F7E88B ON alert (event_id)');
        $this->addSql('CREATE INDEX IDX_17FD46C16C576F80 ON alert (chore_id)');
        $this->addSql('CREATE INDEX IDX_17FD46C123245BF9 ON alert (shopping_list_id)');
        $this->addSql('COMMENT ON COLUMN alert.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE budget_goal (id SERIAL NOT NULL, household_id INT NOT NULL, creator_id INT NOT NULL, title VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, goal_type VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8618E97EE79FF843 ON budget_goal (household_id)');
        $this->addSql('CREATE INDEX IDX_8618E97E61220EA6 ON budget_goal (creator_id)');
        $this->addSql('COMMENT ON COLUMN budget_goal.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE chore (id SERIAL NOT NULL, household_id INT NOT NULL, creator_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_857827D2E79FF843 ON chore (household_id)');
        $this->addSql('CREATE INDEX IDX_857827D261220EA6 ON chore (creator_id)');
        $this->addSql('COMMENT ON COLUMN chore.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE event (id SERIAL NOT NULL, household_id INT NOT NULL, creator_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7E79FF843 ON event (household_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA761220EA6 ON event (creator_id)');
        $this->addSql('COMMENT ON COLUMN event.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE household (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, access_code VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN household.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ingredient (id SERIAL NOT NULL, recipe_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity VARCHAR(255) NOT NULL, unit VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6BAF787059D8A214 ON ingredient (recipe_id)');
        $this->addSql('CREATE TABLE recipe (id SERIAL NOT NULL, household_id INT NOT NULL, creator_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, instructions TEXT NOT NULL, servings INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DA88B137E79FF843 ON recipe (household_id)');
        $this->addSql('CREATE INDEX IDX_DA88B13761220EA6 ON recipe (creator_id)');
        $this->addSql('COMMENT ON COLUMN recipe.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE recipe_tag (id SERIAL NOT NULL, recipe_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_72DED3CF59D8A214 ON recipe_tag (recipe_id)');
        $this->addSql('CREATE INDEX IDX_72DED3CFBAD26311 ON recipe_tag (tag_id)');
        $this->addSql('CREATE TABLE shopping_item (id SERIAL NOT NULL, shopping_list_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, unit VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6612795F23245BF9 ON shopping_item (shopping_list_id)');
        $this->addSql('CREATE TABLE shopping_list (id SERIAL NOT NULL, household_id INT DEFAULT NULL, creator_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, comment TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3DC1A459E79FF843 ON shopping_list (household_id)');
        $this->addSql('CREATE INDEX IDX_3DC1A45961220EA6 ON shopping_list (creator_id)');
        $this->addSql('COMMENT ON COLUMN shopping_list.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tag (id SERIAL NOT NULL, label VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_household (id SERIAL NOT NULL, house_hold_id INT NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5423A22439C60054 ON user_household (house_hold_id)');
        $this->addSql('CREATE TABLE user_household_user (user_household_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(user_household_id, user_id))');
        $this->addSql('CREATE INDEX IDX_E7499EF167538441 ON user_household_user (user_household_id)');
        $this->addSql('CREATE INDEX IDX_E7499EF1A76ED395 ON user_household_user (user_id)');
        $this->addSql('CREATE TABLE "users" (id SERIAL NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, isverified BOOLEAN NOT NULL, verification_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON "users" (email)');
        $this->addSql('COMMENT ON COLUMN "users".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE viewer (id SERIAL NOT NULL, member_id INT DEFAULT NULL, target_type VARCHAR(255) NOT NULL, target INT NOT NULL, permission VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_35CAC557597D3FE ON viewer (member_id)');
        $this->addSql('COMMENT ON COLUMN viewer.created_at IS \'(DC2Type:datetime_immutable)\'');
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
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C17597D3FE FOREIGN KEY (member_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C16C576F80 FOREIGN KEY (chore_id) REFERENCES chore (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C123245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE budget_goal ADD CONSTRAINT FK_8618E97EE79FF843 FOREIGN KEY (household_id) REFERENCES household (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE budget_goal ADD CONSTRAINT FK_8618E97E61220EA6 FOREIGN KEY (creator_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chore ADD CONSTRAINT FK_857827D2E79FF843 FOREIGN KEY (household_id) REFERENCES household (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chore ADD CONSTRAINT FK_857827D261220EA6 FOREIGN KEY (creator_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7E79FF843 FOREIGN KEY (household_id) REFERENCES household (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA761220EA6 FOREIGN KEY (creator_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF787059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137E79FF843 FOREIGN KEY (household_id) REFERENCES household (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13761220EA6 FOREIGN KEY (creator_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe_tag ADD CONSTRAINT FK_72DED3CF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe_tag ADD CONSTRAINT FK_72DED3CFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_item ADD CONSTRAINT FK_6612795F23245BF9 FOREIGN KEY (shopping_list_id) REFERENCES shopping_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A459E79FF843 FOREIGN KEY (household_id) REFERENCES household (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A45961220EA6 FOREIGN KEY (creator_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_household ADD CONSTRAINT FK_5423A22439C60054 FOREIGN KEY (house_hold_id) REFERENCES household (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_household_user ADD CONSTRAINT FK_E7499EF167538441 FOREIGN KEY (user_household_id) REFERENCES user_household (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_household_user ADD CONSTRAINT FK_E7499EF1A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE viewer ADD CONSTRAINT FK_35CAC557597D3FE FOREIGN KEY (member_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE alert DROP CONSTRAINT FK_17FD46C17597D3FE');
        $this->addSql('ALTER TABLE alert DROP CONSTRAINT FK_17FD46C171F7E88B');
        $this->addSql('ALTER TABLE alert DROP CONSTRAINT FK_17FD46C16C576F80');
        $this->addSql('ALTER TABLE alert DROP CONSTRAINT FK_17FD46C123245BF9');
        $this->addSql('ALTER TABLE budget_goal DROP CONSTRAINT FK_8618E97EE79FF843');
        $this->addSql('ALTER TABLE budget_goal DROP CONSTRAINT FK_8618E97E61220EA6');
        $this->addSql('ALTER TABLE chore DROP CONSTRAINT FK_857827D2E79FF843');
        $this->addSql('ALTER TABLE chore DROP CONSTRAINT FK_857827D261220EA6');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7E79FF843');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA761220EA6');
        $this->addSql('ALTER TABLE ingredient DROP CONSTRAINT FK_6BAF787059D8A214');
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B137E79FF843');
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B13761220EA6');
        $this->addSql('ALTER TABLE recipe_tag DROP CONSTRAINT FK_72DED3CF59D8A214');
        $this->addSql('ALTER TABLE recipe_tag DROP CONSTRAINT FK_72DED3CFBAD26311');
        $this->addSql('ALTER TABLE shopping_item DROP CONSTRAINT FK_6612795F23245BF9');
        $this->addSql('ALTER TABLE shopping_list DROP CONSTRAINT FK_3DC1A459E79FF843');
        $this->addSql('ALTER TABLE shopping_list DROP CONSTRAINT FK_3DC1A45961220EA6');
        $this->addSql('ALTER TABLE user_household DROP CONSTRAINT FK_5423A22439C60054');
        $this->addSql('ALTER TABLE user_household_user DROP CONSTRAINT FK_E7499EF167538441');
        $this->addSql('ALTER TABLE user_household_user DROP CONSTRAINT FK_E7499EF1A76ED395');
        $this->addSql('ALTER TABLE viewer DROP CONSTRAINT FK_35CAC557597D3FE');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE budget_goal');
        $this->addSql('DROP TABLE chore');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE household');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_tag');
        $this->addSql('DROP TABLE shopping_item');
        $this->addSql('DROP TABLE shopping_list');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user_household');
        $this->addSql('DROP TABLE user_household_user');
        $this->addSql('DROP TABLE "users"');
        $this->addSql('DROP TABLE viewer');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
