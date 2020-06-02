<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200602141953 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hoodtrooper_place (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, coordinate_lat VARCHAR(255) NOT NULL, coordinate_lng VARCHAR(255) NOT NULL, description VARCHAR(2000) DEFAULT NULL, recommendation_level VARCHAR(2000) DEFAULT NULL, place_image_filename VARCHAR(255) DEFAULT NULL, discover_date DATE NOT NULL, private_area_place TINYINT(1) DEFAULT NULL, INDEX IDX_6532035CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hoodtrooper_place_comment (id INT AUTO_INCREMENT NOT NULL, comment_author_id INT NOT NULL, comment_related_place_id INT NOT NULL, comment_text VARCHAR(255) NOT NULL, INDEX IDX_CB3D119E1F0B124D (comment_author_id), INDEX IDX_CB3D119EDBADA802 (comment_related_place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hoodtrooper_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, sex VARCHAR(1) DEFAULT NULL, birth_date DATE NOT NULL, UNIQUE INDEX UNIQ_A3C81B92E7927C74 (email), UNIQUE INDEX UNIQ_A3C81B92A188FE64 (nickname), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hoodtrooper_place ADD CONSTRAINT FK_6532035CF675F31B FOREIGN KEY (author_id) REFERENCES hoodtrooper_user (id)');
        $this->addSql('ALTER TABLE hoodtrooper_place_comment ADD CONSTRAINT FK_CB3D119E1F0B124D FOREIGN KEY (comment_author_id) REFERENCES hoodtrooper_user (id)');
        $this->addSql('ALTER TABLE hoodtrooper_place_comment ADD CONSTRAINT FK_CB3D119EDBADA802 FOREIGN KEY (comment_related_place_id) REFERENCES hoodtrooper_place (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hoodtrooper_place_comment DROP FOREIGN KEY FK_CB3D119EDBADA802');
        $this->addSql('ALTER TABLE hoodtrooper_place DROP FOREIGN KEY FK_6532035CF675F31B');
        $this->addSql('ALTER TABLE hoodtrooper_place_comment DROP FOREIGN KEY FK_CB3D119E1F0B124D');
        $this->addSql('DROP TABLE hoodtrooper_place');
        $this->addSql('DROP TABLE hoodtrooper_place_comment');
        $this->addSql('DROP TABLE hoodtrooper_user');
    }
}
