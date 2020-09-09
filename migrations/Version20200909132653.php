<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200909132653 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE planification (id INT AUTO_INCREMENT NOT NULL, livreur_id INT DEFAULT NULL, moto_id INT DEFAULT NULL, adresse_id INT DEFAULT NULL, date DATE NOT NULL, INDEX IDX_FFC02E1BF8646701 (livreur_id), INDEX IDX_FFC02E1B78B8F2AC (moto_id), INDEX IDX_FFC02E1B4DE7DC5C (adresse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE planification ADD CONSTRAINT FK_FFC02E1BF8646701 FOREIGN KEY (livreur_id) REFERENCES livreur (id)');
        $this->addSql('ALTER TABLE planification ADD CONSTRAINT FK_FFC02E1B78B8F2AC FOREIGN KEY (moto_id) REFERENCES moto (id)');
        $this->addSql('ALTER TABLE planification ADD CONSTRAINT FK_FFC02E1B4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE planification');
    }
}
