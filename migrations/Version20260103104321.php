<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260103104321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__table_resto AS SELECT id, nombre_personnes FROM table_resto');
        $this->addSql('DROP TABLE table_resto');
        $this->addSql('CREATE TABLE table_resto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, numero INTEGER NOT NULL, capacite INTEGER NOT NULL)');
        $this->addSql('INSERT INTO table_resto (id, numero) SELECT id, nombre_personnes FROM __temp__table_resto');
        $this->addSql('DROP TABLE __temp__table_resto');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__table_resto AS SELECT id FROM table_resto');
        $this->addSql('DROP TABLE table_resto');
        $this->addSql('CREATE TABLE table_resto (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_reservation DATE NOT NULL, heure TIME NOT NULL, nombre_personnes INTEGER NOT NULL, statut VARCHAR(20) NOT NULL, commentaire CLOB NOT NULL)');
        $this->addSql('INSERT INTO table_resto (id) SELECT id FROM __temp__table_resto');
        $this->addSql('DROP TABLE __temp__table_resto');
    }
}
