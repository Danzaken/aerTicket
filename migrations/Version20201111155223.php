<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111155223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E21DFC797 FOREIGN KEY (carrier_id) REFERENCES carrier (id)');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60EF631AB5C FOREIGN KEY (departure_airport_id) REFERENCES airport (id)');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E7F43E343 FOREIGN KEY (arrival_airport_id) REFERENCES airport (id)');
        $this->addSql('CREATE INDEX IDX_C257E60E21DFC797 ON flight (carrier_id)');
        $this->addSql('CREATE INDEX IDX_C257E60EF631AB5C ON flight (departure_airport_id)');
        $this->addSql('CREATE INDEX IDX_C257E60E7F43E343 ON flight (arrival_airport_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E21DFC797');
        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60EF631AB5C');
        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E7F43E343');
        $this->addSql('DROP INDEX IDX_C257E60E21DFC797 ON flight');
        $this->addSql('DROP INDEX IDX_C257E60EF631AB5C ON flight');
        $this->addSql('DROP INDEX IDX_C257E60E7F43E343 ON flight');
    }
}
