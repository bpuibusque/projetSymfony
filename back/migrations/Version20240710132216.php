<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710132216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE privilege ADD commission_id INT NOT NULL, ADD role VARCHAR(255) NOT NULL, DROP can_create_comission, DROP created_at, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE privilege ADD CONSTRAINT FK_87209A87202D1EB2 FOREIGN KEY (commission_id) REFERENCES commission (id)');
        $this->addSql('CREATE INDEX IDX_87209A87202D1EB2 ON privilege (commission_id)');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_identifier_email TO UNIQ_8D93D649E7927C74');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE privilege DROP FOREIGN KEY FK_87209A87202D1EB2');
        $this->addSql('DROP INDEX IDX_87209A87202D1EB2 ON privilege');
        $this->addSql('ALTER TABLE privilege ADD can_create_comission TINYINT(1) NOT NULL, ADD created_at DATETIME NOT NULL, DROP commission_id, DROP role, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO UNIQ_IDENTIFIER_EMAIL');
    }
}
