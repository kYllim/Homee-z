<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251215211501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE IF EXISTS user_household_id_seq CASCADE');
        $this->addSql('CREATE TABLE person (id SERIAL NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN person.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE person_household (id SERIAL NOT NULL, person_id INT NOT NULL, household_id INT NOT NULL, role VARCHAR(255) NOT NULL, joined_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ECEACC6A217BBB47 ON person_household (person_id)');
        $this->addSql('CREATE INDEX IDX_ECEACC6AE79FF843 ON person_household (household_id)');
        $this->addSql('COMMENT ON COLUMN person_household.joined_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE person_household ADD CONSTRAINT FK_ECEACC6A217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_household ADD CONSTRAINT FK_ECEACC6AE79FF843 FOREIGN KEY (household_id) REFERENCES household (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE IF EXISTS user_household DROP CONSTRAINT IF EXISTS fk_5423a22439c60054');
        $this->addSql('ALTER TABLE IF EXISTS user_household_user DROP CONSTRAINT IF EXISTS fk_e7499ef167538441');
        $this->addSql('ALTER TABLE IF EXISTS user_household_user DROP CONSTRAINT IF EXISTS fk_e7499ef1a76ed395');
        $this->addSql('DROP TABLE IF EXISTS user_household');
        $this->addSql('DROP TABLE IF EXISTS user_household_user');
        $this->addSql('ALTER TABLE alert DROP CONSTRAINT FK_17FD46C17597D3FE');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C17597D3FE FOREIGN KEY (member_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE budget_goal DROP CONSTRAINT FK_8618E97E61220EA6');
        $this->addSql('ALTER TABLE budget_goal ADD CONSTRAINT FK_8618E97E61220EA6 FOREIGN KEY (creator_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chore DROP CONSTRAINT FK_857827D261220EA6');
        $this->addSql('ALTER TABLE chore ADD CONSTRAINT FK_857827D261220EA6 FOREIGN KEY (creator_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA761220EA6');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA761220EA6 FOREIGN KEY (creator_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B13761220EA6');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13761220EA6 FOREIGN KEY (creator_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_list DROP CONSTRAINT FK_3DC1A45961220EA6');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT FK_3DC1A45961220EA6 FOREIGN KEY (creator_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD person_id INT NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9217BBB47 ON users (person_id)');
        $this->addSql('ALTER TABLE viewer DROP CONSTRAINT FK_35CAC557597D3FE');
        $this->addSql('ALTER TABLE viewer ADD CONSTRAINT FK_35CAC557597D3FE FOREIGN KEY (member_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE alert DROP CONSTRAINT FK_17FD46C17597D3FE');
        $this->addSql('ALTER TABLE budget_goal DROP CONSTRAINT FK_8618E97E61220EA6');
        $this->addSql('ALTER TABLE chore DROP CONSTRAINT FK_857827D261220EA6');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA761220EA6');
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B13761220EA6');
        $this->addSql('ALTER TABLE shopping_list DROP CONSTRAINT FK_3DC1A45961220EA6');
        $this->addSql('ALTER TABLE "users" DROP CONSTRAINT FK_1483A5E9217BBB47');
        $this->addSql('ALTER TABLE viewer DROP CONSTRAINT FK_35CAC557597D3FE');
        $this->addSql('CREATE SEQUENCE user_household_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_household (id SERIAL NOT NULL, house_hold_id INT NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_5423a22439c60054 ON user_household (house_hold_id)');
        $this->addSql('CREATE TABLE user_household_user (user_household_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(user_household_id, user_id))');
        $this->addSql('CREATE INDEX idx_e7499ef167538441 ON user_household_user (user_household_id)');
        $this->addSql('CREATE INDEX idx_e7499ef1a76ed395 ON user_household_user (user_id)');
        $this->addSql('ALTER TABLE user_household ADD CONSTRAINT fk_5423a22439c60054 FOREIGN KEY (house_hold_id) REFERENCES household (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_household_user ADD CONSTRAINT fk_e7499ef167538441 FOREIGN KEY (user_household_id) REFERENCES user_household (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_household_user ADD CONSTRAINT fk_e7499ef1a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_household DROP CONSTRAINT FK_ECEACC6A217BBB47');
        $this->addSql('ALTER TABLE person_household DROP CONSTRAINT FK_ECEACC6AE79FF843');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_household');
        $this->addSql('ALTER TABLE budget_goal DROP CONSTRAINT fk_8618e97e61220ea6');
        $this->addSql('ALTER TABLE budget_goal ADD CONSTRAINT fk_8618e97e61220ea6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE alert DROP CONSTRAINT fk_17fd46c17597d3fe');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT fk_17fd46c17597d3fe FOREIGN KEY (member_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chore DROP CONSTRAINT fk_857827d261220ea6');
        $this->addSql('ALTER TABLE chore ADD CONSTRAINT fk_857827d261220ea6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT fk_3bae0aa761220ea6');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT fk_3bae0aa761220ea6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT fk_da88b13761220ea6');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT fk_da88b13761220ea6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE viewer DROP CONSTRAINT fk_35cac557597d3fe');
        $this->addSql('ALTER TABLE viewer ADD CONSTRAINT fk_35cac557597d3fe FOREIGN KEY (member_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX UNIQ_1483A5E9217BBB47');
        $this->addSql('ALTER TABLE "users" DROP person_id');
        $this->addSql('ALTER TABLE shopping_list DROP CONSTRAINT fk_3dc1a45961220ea6');
        $this->addSql('ALTER TABLE shopping_list ADD CONSTRAINT fk_3dc1a45961220ea6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
