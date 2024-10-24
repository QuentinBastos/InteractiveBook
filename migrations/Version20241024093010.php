<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024093010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE target DROP FOREIGN KEY FK_466F2FFCC4663E4');
        $this->addSql('DROP INDEX IDX_466F2FFCC4663E4 ON target');
        $this->addSql('ALTER TABLE target ADD to_page_id INT NOT NULL, CHANGE page_id from_page_id INT NOT NULL');
        $this->addSql('ALTER TABLE target ADD CONSTRAINT FK_466F2FFC8A18804B FOREIGN KEY (from_page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE target ADD CONSTRAINT FK_466F2FFC82DE5E11 FOREIGN KEY (to_page_id) REFERENCES page (id)');
        $this->addSql('CREATE INDEX IDX_466F2FFC8A18804B ON target (from_page_id)');
        $this->addSql('CREATE INDEX IDX_466F2FFC82DE5E11 ON target (to_page_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE target DROP FOREIGN KEY FK_466F2FFC8A18804B');
        $this->addSql('ALTER TABLE target DROP FOREIGN KEY FK_466F2FFC82DE5E11');
        $this->addSql('DROP INDEX IDX_466F2FFC8A18804B ON target');
        $this->addSql('DROP INDEX IDX_466F2FFC82DE5E11 ON target');
        $this->addSql('ALTER TABLE target ADD page_id INT NOT NULL, DROP from_page_id, DROP to_page_id');
        $this->addSql('ALTER TABLE target ADD CONSTRAINT FK_466F2FFCC4663E4 FOREIGN KEY (page_id) REFERENCES page (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_466F2FFCC4663E4 ON target (page_id)');
    }
}
