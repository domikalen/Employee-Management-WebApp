<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029132848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, role VARCHAR(100) NOT NULL, image VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, description CLOB DEFAULT NULL)');
        $this->addSql('DROP TABLE emoloyee');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emoloyee (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE "BINARY", role VARCHAR(100) NOT NULL COLLATE "BINARY", image VARCHAR(255) DEFAULT NULL COLLATE "BINARY", phone VARCHAR(20) DEFAULT NULL COLLATE "BINARY", email VARCHAR(100) DEFAULT NULL COLLATE "BINARY", description CLOB DEFAULT NULL COLLATE "BINARY")');
        $this->addSql('DROP TABLE employee');
    }
}
