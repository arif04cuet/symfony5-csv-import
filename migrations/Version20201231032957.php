<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201231032957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD invoice_source_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517443A96FF68 FOREIGN KEY (invoice_source_file_id) REFERENCES invoice_source_file (id)');
        $this->addSql('CREATE INDEX IDX_906517443A96FF68 ON invoice (invoice_source_file_id)');
        $this->addSql('ALTER TABLE invoice_source_file ADD customer_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice_source_file ADD CONSTRAINT FK_9A5BD1209395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_9A5BD1209395C3F3 ON invoice_source_file (customer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517443A96FF68');
        $this->addSql('DROP INDEX IDX_906517443A96FF68 ON invoice');
        $this->addSql('ALTER TABLE invoice DROP invoice_source_file_id');
        $this->addSql('ALTER TABLE invoice_source_file DROP FOREIGN KEY FK_9A5BD1209395C3F3');
        $this->addSql('DROP INDEX IDX_9A5BD1209395C3F3 ON invoice_source_file');
        $this->addSql('ALTER TABLE invoice_source_file DROP customer_id');
    }
}
