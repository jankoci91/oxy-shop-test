<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118091930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'init schema';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql(file_get_contents(__DIR__.'/Version20201118091930.sql'));
    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
