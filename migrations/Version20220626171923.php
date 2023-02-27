<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220626171923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repertoire ADD repertoire_parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repertoire ADD CONSTRAINT FK_3C367876A3AB6AA3 FOREIGN KEY (repertoire_parent_id) REFERENCES repertoire (id)');
        $this->addSql('CREATE INDEX IDX_3C367876A3AB6AA3 ON repertoire (repertoire_parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repertoire DROP FOREIGN KEY FK_3C367876A3AB6AA3');
        $this->addSql('DROP INDEX IDX_3C367876A3AB6AA3 ON repertoire');
        $this->addSql('ALTER TABLE repertoire DROP repertoire_parent_id');
    }
}
