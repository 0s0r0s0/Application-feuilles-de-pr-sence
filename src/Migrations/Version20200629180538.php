<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200629180538 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE absence (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendrier (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, begin_at DATETIME NOT NULL, end_at DATETIME DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_B2753CB98C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, job_id INT DEFAULT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_5D9F75A16BF700BD (status_id), INDEX IDX_5D9F75A1BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE extra_time (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, duration INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE month (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(11) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, label LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notice (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, title VARCHAR(255) NOT NULL, begin_at DATETIME NOT NULL, INDEX IDX_480D45C28C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE off_time (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, date_t DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timesheet (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, month_id INT NOT NULL, year INT NOT NULL, datas LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_77A4E8D48C03F15C (employee_id), INDEX IDX_77A4E8D4A0CBDE4 (month_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_table (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, absence_id INT DEFAULT NULL, day_id INT NOT NULL, am_start_at TIME DEFAULT NULL, am_end_at TIME DEFAULT NULL, pm_start_at TIME DEFAULT NULL, pm_end_at TIME DEFAULT NULL, INDEX IDX_B35B6E3A8C03F15C (employee_id), INDEX IDX_B35B6E3A2DFF238F (absence_id), INDEX IDX_B35B6E3A9C24126 (day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calendrier ADD CONSTRAINT FK_B2753CB98C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A16BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C28C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE timesheet ADD CONSTRAINT FK_77A4E8D48C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE timesheet ADD CONSTRAINT FK_77A4E8D4A0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id)');
        $this->addSql('ALTER TABLE time_table ADD CONSTRAINT FK_B35B6E3A8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE time_table ADD CONSTRAINT FK_B35B6E3A2DFF238F FOREIGN KEY (absence_id) REFERENCES absence (id)');
        $this->addSql('ALTER TABLE time_table ADD CONSTRAINT FK_B35B6E3A9C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE time_table DROP FOREIGN KEY FK_B35B6E3A2DFF238F');
        $this->addSql('ALTER TABLE time_table DROP FOREIGN KEY FK_B35B6E3A9C24126');
        $this->addSql('ALTER TABLE calendrier DROP FOREIGN KEY FK_B2753CB98C03F15C');
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C28C03F15C');
        $this->addSql('ALTER TABLE timesheet DROP FOREIGN KEY FK_77A4E8D48C03F15C');
        $this->addSql('ALTER TABLE time_table DROP FOREIGN KEY FK_B35B6E3A8C03F15C');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1BE04EA9');
        $this->addSql('ALTER TABLE timesheet DROP FOREIGN KEY FK_77A4E8D4A0CBDE4');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A16BF700BD');
        $this->addSql('DROP TABLE absence');
        $this->addSql('DROP TABLE calendrier');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE extra_time');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE month');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE notice');
        $this->addSql('DROP TABLE off_time');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE timesheet');
        $this->addSql('DROP TABLE time_table');
    }
}
