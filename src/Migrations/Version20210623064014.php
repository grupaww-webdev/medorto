<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623064014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loevgaard_brand_image DROP FOREIGN KEY FK_95D3C8B97E3C61F9');
        $this->addSql('ALTER TABLE sylius_product DROP FOREIGN KEY FK_677B9B7444F5D008');
        $this->addSql('DROP TABLE loevgaard_brand');
        $this->addSql('DROP TABLE loevgaard_brand_image');
        $this->addSql('DROP INDEX IDX_677B9B7444F5D008 ON sylius_product');
        $this->addSql('ALTER TABLE sylius_product DROP brand_id');
        $this->addSql('ALTER TABLE sylius_refund_order ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_refund_order ADD CONSTRAINT FK_44671DCA8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
        $this->addSql('CREATE INDEX IDX_44671DCA8D9F6D38 ON sylius_refund_order (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loevgaard_brand (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, UNIQUE INDEX UNIQ_680CAA0877153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE loevgaard_brand_image (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, path VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, INDEX IDX_95D3C8B97E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE loevgaard_brand_image ADD CONSTRAINT FK_95D3C8B97E3C61F9 FOREIGN KEY (owner_id) REFERENCES loevgaard_brand (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product ADD brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product ADD CONSTRAINT FK_677B9B7444F5D008 FOREIGN KEY (brand_id) REFERENCES loevgaard_brand (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_677B9B7444F5D008 ON sylius_product (brand_id)');
        $this->addSql('ALTER TABLE sylius_refund_order DROP FOREIGN KEY FK_44671DCA8D9F6D38');
        $this->addSql('DROP INDEX IDX_44671DCA8D9F6D38 ON sylius_refund_order');
        $this->addSql('ALTER TABLE sylius_refund_order DROP order_id');
    }
}
