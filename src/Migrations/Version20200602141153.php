<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200602141153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hoodtrooper_place_comment (id INT AUTO_INCREMENT NOT NULL, comment_author_id INT NOT NULL, comment_related_place_id INT NOT NULL, comment_text VARCHAR(255) NOT NULL, INDEX IDX_CB3D119E1F0B124D (comment_author_id), INDEX IDX_CB3D119EDBADA802 (comment_related_place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hoodtrooper_place_comment ADD CONSTRAINT FK_CB3D119E1F0B124D FOREIGN KEY (comment_author_id) REFERENCES hoodtrooper_user (id)');
        $this->addSql('ALTER TABLE hoodtrooper_place_comment ADD CONSTRAINT FK_CB3D119EDBADA802 FOREIGN KEY (comment_related_place_id) REFERENCES hoodtrooper_place (id)');
        $this->addSql('ALTER TABLE hoodtrooper_place ADD CONSTRAINT FK_6532035CF675F31B FOREIGN KEY (author_id) REFERENCES hoodtrooper_user (id)');
        $this->addSql('CREATE INDEX IDX_6532035CF675F31B ON hoodtrooper_place (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE hoodtrooper_place_comment');
        $this->addSql('ALTER TABLE hoodtrooper_place DROP FOREIGN KEY FK_6532035CF675F31B');
        $this->addSql('DROP INDEX IDX_6532035CF675F31B ON hoodtrooper_place');
    }
}
