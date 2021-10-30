<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211029233935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mail ALTER user_id TYPE INT');
        $this->addSql('ALTER TABLE mail ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER username TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER address TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER address DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER phone_number TYPE VARCHAR(20)');
        $this->addSql('ALTER TABLE "user" ALTER phone_number DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mail ALTER user_id TYPE BIGINT');
        $this->addSql('ALTER TABLE mail ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER username TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE "user" ALTER address TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER address DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER phone_number TYPE TEXT');
        $this->addSql('ALTER TABLE "user" ALTER phone_number DROP DEFAULT');
        $this->addSql('ALTER TABLE "user" ALTER phone_number TYPE TEXT');
    }
}
