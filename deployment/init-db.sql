CREATE DATABASE IF NOT EXISTS `wordpress_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS `whmcs_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS `krikkit_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'whmcs_user'@'%' IDENTIFIED BY 'WhmcsPass2026!';
GRANT ALL PRIVILEGES ON whmcs_db.* TO 'whmcs_user'@'%';

CREATE USER IF NOT EXISTS 'wp_user'@'%' IDENTIFIED BY 'WpPass2026!';
GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wp_user'@'%';

CREATE USER IF NOT EXISTS 'krikkit_user'@'%' IDENTIFIED BY 'KrikkitPass2026!';
GRANT ALL PRIVILEGES ON krikkit_db.* TO 'krikkit_user'@'%';

FLUSH PRIVILEGES;
