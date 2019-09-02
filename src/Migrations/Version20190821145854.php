<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190821145854 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE appartient ADD produit_id INT DEFAULT NULL, ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appartient ADD CONSTRAINT FK_4201BAA7F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE appartient ADD CONSTRAINT FK_4201BAA7BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_4201BAA7F347EFB ON appartient (produit_id)');
        $this->addSql('CREATE INDEX IDX_4201BAA7BCF5E72D ON appartient (categorie_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE appartient DROP FOREIGN KEY FK_4201BAA7F347EFB');
        $this->addSql('ALTER TABLE appartient DROP FOREIGN KEY FK_4201BAA7BCF5E72D');
        $this->addSql('DROP INDEX IDX_4201BAA7F347EFB ON appartient');
        $this->addSql('DROP INDEX IDX_4201BAA7BCF5E72D ON appartient');
        $this->addSql('ALTER TABLE appartient DROP produit_id, DROP categorie_id');
    }
}
