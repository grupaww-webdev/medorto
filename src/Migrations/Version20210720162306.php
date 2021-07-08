<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210720162306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sylius_product_refunds (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, discount NUMERIC(5, 2) NOT NULL, description LONGTEXT NOT NULL, active TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_858F30574584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_product_refunds ADD CONSTRAINT FK_858F30574584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE sylius_order_item ADD product_refund_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_order_item ADD CONSTRAINT FK_77B587ED7638B758 FOREIGN KEY (product_refund_id) REFERENCES sylius_product_refunds (id)');
        $this->addSql('CREATE INDEX IDX_77B587ED7638B758 ON sylius_order_item (product_refund_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_order_item DROP FOREIGN KEY FK_77B587ED7638B758');
        $this->addSql('DROP TABLE sylius_product_refunds');
        $this->addSql('DROP INDEX IDX_77B587ED7638B758 ON sylius_order_item');
        $this->addSql('ALTER TABLE sylius_order_item DROP product_refund_id');
    }
}
