<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414160511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE sortie ADD lieu_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sortie ADD CONSTRAINT FK_3C3FD3F26AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3C3FD3F26AB213CC ON sortie (lieu_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE sortie DROP FOREIGN KEY FK_3C3FD3F26AB213CC
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3C3FD3F26AB213CC ON sortie
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sortie DROP lieu_id
        SQL);
    }
}
