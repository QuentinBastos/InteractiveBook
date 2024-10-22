<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022122119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page DROP user_id');
        $this->addSql('ALTER TABLE target ADD page_id INT NOT NULL');
        $this->addSql('ALTER TABLE target ADD CONSTRAINT FK_466F2FFCC4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('CREATE INDEX IDX_466F2FFCC4663E4 ON target (page_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE page ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE target DROP FOREIGN KEY FK_466F2FFCC4663E4');
        $this->addSql('DROP INDEX IDX_466F2FFCC4663E4 ON target');
        $this->addSql('ALTER TABLE target DROP page_id');
    }
}
