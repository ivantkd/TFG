<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200312125456 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, mail_usuario VARCHAR(100) NOT NULL, begin_at DATETIME NOT NULL, end_at DATETIME DEFAULT NULL, title VARCHAR(254) NOT NULL, INDEX IDX_E00CEDDEF8D83132 (mail_usuario), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clientes (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, correo VARCHAR(50) NOT NULL, telefono VARCHAR(20) DEFAULT NULL, direccion VARCHAR(100) DEFAULT NULL, responsable VARCHAR(50) DEFAULT NULL, servicio VARCHAR(254) DEFAULT NULL, UNIQUE INDEX UNIQ_50FE07D777040BC9 (correo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departamento (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE local (id INT AUTO_INCREMENT NOT NULL, direccion VARCHAR(100) NOT NULL, poblacion VARCHAR(100) NOT NULL, correo VARCHAR(100) DEFAULT NULL, telefono VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, oficina_id INT NOT NULL, nombre VARCHAR(100) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, precio INT DEFAULT NULL, tipo VARCHAR(100) DEFAULT NULL, disponible VARCHAR(200) DEFAULT \'yes\' NOT NULL, date DATETIME DEFAULT NULL, Usuario VARCHAR(100) DEFAULT NULL, INDEX IDX_7CBE75958A8639B7 (oficina_id), INDEX IDX_7CBE7595EDD889C1 (Usuario), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mensajes (id INT AUTO_INCREMENT NOT NULL, cuerpo TINYTEXT NOT NULL, asunto VARCHAR(50) NOT NULL, fecha DATETIME NOT NULL, mailUsuario VARCHAR(100) NOT NULL, INDEX IDX_6C929C80C9D2D129 (mailUsuario), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (numero_empleado INT AUTO_INCREMENT NOT NULL, departamento_id INT NOT NULL, correo VARCHAR(100) NOT NULL, nombre VARCHAR(100) NOT NULL, apellidos VARCHAR(100) NOT NULL, cargo VARCHAR(100) NOT NULL, telefono VARCHAR(50) DEFAULT NULL, Local_id INT NOT NULL, UNIQUE INDEX UNIQ_51E5B69B77040BC9 (correo), INDEX IDX_51E5B69BA42C4357 (Local_id), INDEX IDX_51E5B69B5A91C08D (departamento_id), PRIMARY KEY(numero_empleado)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proveedores (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, correo VARCHAR(50) NOT NULL, telefono VARCHAR(20) DEFAULT NULL, producto VARCHAR(100) DEFAULT NULL, responsable VARCHAR(50) DEFAULT NULL, direccion VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_864FA8F177040BC9 (correo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, usermail VARCHAR(100) NOT NULL, departamento_id INT NOT NULL, tipo_incidente VARCHAR(150) NOT NULL, dispositivo VARCHAR(150) DEFAULT NULL, explicacion LONGTEXT DEFAULT NULL, solved VARCHAR(255) DEFAULT \'no\', solucion LONGTEXT DEFAULT NULL, INDEX IDX_54469DF4F75BD439 (usermail), INDEX IDX_54469DF45A91C08D (departamento_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_roles (role VARCHAR(50) NOT NULL, PRIMARY KEY(role)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (mail VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(100) DEFAULT NULL, apellidos VARCHAR(100) DEFAULT NULL, imagen VARCHAR(250) NOT NULL, numeroEmpleado INT NOT NULL, UserRole VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_2265B05D92B322D8 (numeroEmpleado), INDEX IDX_2265B05DA8503F73 (UserRole), PRIMARY KEY(mail)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEF8D83132 FOREIGN KEY (mail_usuario) REFERENCES usuario (mail)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE75958A8639B7 FOREIGN KEY (oficina_id) REFERENCES local (id)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595EDD889C1 FOREIGN KEY (Usuario) REFERENCES usuario (mail)');
        $this->addSql('ALTER TABLE mensajes ADD CONSTRAINT FK_6C929C80C9D2D129 FOREIGN KEY (mailUsuario) REFERENCES usuario (mail)');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BA42C4357 FOREIGN KEY (Local_id) REFERENCES local (id)');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B5A91C08D FOREIGN KEY (departamento_id) REFERENCES departamento (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4F75BD439 FOREIGN KEY (usermail) REFERENCES usuario (mail) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF45A91C08D FOREIGN KEY (departamento_id) REFERENCES departamento (id)');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D92B322D8 FOREIGN KEY (numeroEmpleado) REFERENCES persona (numero_empleado) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05DA8503F73 FOREIGN KEY (UserRole) REFERENCES user_roles (role)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B5A91C08D');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF45A91C08D');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE75958A8639B7');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BA42C4357');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05D92B322D8');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05DA8503F73');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEF8D83132');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE7595EDD889C1');
        $this->addSql('ALTER TABLE mensajes DROP FOREIGN KEY FK_6C929C80C9D2D129');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4F75BD439');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE clientes');
        $this->addSql('DROP TABLE departamento');
        $this->addSql('DROP TABLE local');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE mensajes');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE proveedores');
        $this->addSql('DROP TABLE tickets');
        $this->addSql('DROP TABLE user_roles');
        $this->addSql('DROP TABLE usuario');
    }
}
