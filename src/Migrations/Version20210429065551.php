<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210429065551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE odiseo_blog_articles_channels (article_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_A4C0CF5F7294869C (article_id), INDEX IDX_A4C0CF5F72F5A1AA (channel_id), PRIMARY KEY(article_id, channel_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odiseo_blog_articles_channels ADD CONSTRAINT FK_A4C0CF5F7294869C FOREIGN KEY (article_id) REFERENCES odiseo_blog_article (id)');
        $this->addSql('ALTER TABLE odiseo_blog_articles_channels ADD CONSTRAINT FK_A4C0CF5F72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id)');
        $this->addSql('ALTER TABLE odiseo_blog_article ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odiseo_blog_article ADD CONSTRAINT FK_EA96598AF675F31B FOREIGN KEY (author_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('CREATE INDEX IDX_EA96598AF675F31B ON odiseo_blog_article (author_id)');
        $this->addSql('ALTER TABLE odiseo_blog_article_comment ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE odiseo_blog_article_comment ADD CONSTRAINT FK_3DD1AC47F675F31B FOREIGN KEY (author_id) REFERENCES sylius_shop_user (id)');
        $this->addSql('CREATE INDEX IDX_3DD1AC47F675F31B ON odiseo_blog_article_comment (author_id)');
        $this->addSql('ALTER TABLE sylius_channel ADD enable_facebook_messenger TINYINT(1) DEFAULT NULL, ADD facebook_page_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE odiseo_blog_articles_channels');
        $this->addSql('ALTER TABLE odiseo_blog_article DROP FOREIGN KEY FK_EA96598AF675F31B');
        $this->addSql('DROP INDEX IDX_EA96598AF675F31B ON odiseo_blog_article');
        $this->addSql('ALTER TABLE odiseo_blog_article DROP author_id');
        $this->addSql('ALTER TABLE odiseo_blog_article_comment DROP FOREIGN KEY FK_3DD1AC47F675F31B');
        $this->addSql('DROP INDEX IDX_3DD1AC47F675F31B ON odiseo_blog_article_comment');
        $this->addSql('ALTER TABLE odiseo_blog_article_comment DROP author_id');
        $this->addSql('ALTER TABLE sylius_channel DROP enable_facebook_messenger, DROP facebook_page_id');
    }
}
