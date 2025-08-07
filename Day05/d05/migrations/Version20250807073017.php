<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250807073017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address_orm (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, address LONGTEXT NOT NULL, INDEX IDX_D98E0911217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank_account_orm (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, iban VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_F0B0BCBF217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persons_orm (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, birthdate DATETIME NOT NULL, marital_status VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_517C0C4AF85E0677 (username), UNIQUE INDEX UNIQ_517C0C4AE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_orm (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, birthdate DATETIME NOT NULL, address LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_79D63215F85E0677 (username), UNIQUE INDEX UNIQ_79D63215E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_ormdelete (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, birthdate DATETIME NOT NULL, address LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_ormform (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, birthdate DATETIME NOT NULL, address LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_EC1BC01DF85E0677 (username), UNIQUE INDEX UNIQ_EC1BC01DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_ormupdate (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, birthdate DATETIME NOT NULL, address LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_48C0F82CF85E0677 (username), UNIQUE INDEX UNIQ_48C0F82CE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address_orm ADD CONSTRAINT FK_D98E0911217BBB47 FOREIGN KEY (person_id) REFERENCES persons_orm (id)');
        $this->addSql('ALTER TABLE bank_account_orm ADD CONSTRAINT FK_F0B0BCBF217BBB47 FOREIGN KEY (person_id) REFERENCES persons_orm (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address_orm DROP FOREIGN KEY FK_D98E0911217BBB47');
        $this->addSql('ALTER TABLE bank_account_orm DROP FOREIGN KEY FK_F0B0BCBF217BBB47');
        $this->addSql('DROP TABLE address_orm');
        $this->addSql('DROP TABLE bank_account_orm');
        $this->addSql('DROP TABLE persons_orm');
        $this->addSql('DROP TABLE user_orm');
        $this->addSql('DROP TABLE user_ormdelete');
        $this->addSql('DROP TABLE user_ormform');
        $this->addSql('DROP TABLE user_ormupdate');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
