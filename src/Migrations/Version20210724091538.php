<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210724091538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_shipment_pickup_point (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL, zip_code VARCHAR(15) NOT NULL, city VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_refund_order ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_refund_order ADD CONSTRAINT FK_44671DCA8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
        $this->addSql('CREATE INDEX IDX_44671DCA8D9F6D38 ON sylius_refund_order (order_id)');
        $this->addSql('ALTER TABLE sylius_shipment ADD pick_up_point_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE sylius_shipment ADD CONSTRAINT FK_FD707B33212544F7 FOREIGN KEY (pick_up_point_id) REFERENCES app_shipment_pickup_point (id)');
        $this->addSql('CREATE INDEX IDX_FD707B33212544F7 ON sylius_shipment (pick_up_point_id)');
        $this->addSql('ALTER TABLE sylius_shipping_method ADD pickup_point_provider VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_shipment DROP FOREIGN KEY FK_FD707B33212544F7');
        $this->addSql('DROP TABLE app_shipment_pickup_point');
        $this->addSql('ALTER TABLE sylius_refund_order DROP FOREIGN KEY FK_44671DCA8D9F6D38');
        $this->addSql('DROP INDEX IDX_44671DCA8D9F6D38 ON sylius_refund_order');
        $this->addSql('ALTER TABLE sylius_refund_order DROP order_id');
        $this->addSql('DROP INDEX IDX_FD707B33212544F7 ON sylius_shipment');
        $this->addSql('ALTER TABLE sylius_shipment DROP pick_up_point_id');
        $this->addSql('ALTER TABLE sylius_shipping_method DROP pickup_point_provider');
    }
}
