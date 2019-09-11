<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190902143115 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comporte ADD produit_id INT NOT NULL, ADD panier_id INT NOT NULL');
        $this->addSql('ALTER TABLE comporte ADD CONSTRAINT FK_49BBCA38F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE comporte ADD CONSTRAINT FK_49BBCA38F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('CREATE INDEX IDX_49BBCA38F347EFB ON comporte (produit_id)');
        $this->addSql('CREATE INDEX IDX_49BBCA38F77D927C ON comporte (panier_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comporte DROP FOREIGN KEY FK_49BBCA38F347EFB');
        $this->addSql('ALTER TABLE comporte DROP FOREIGN KEY FK_49BBCA38F77D927C');
        $this->addSql('DROP INDEX IDX_49BBCA38F347EFB ON comporte');
        $this->addSql('DROP INDEX IDX_49BBCA38F77D927C ON comporte');
        $this->addSql('ALTER TABLE comporte DROP produit_id, DROP panier_id');
    }
}
