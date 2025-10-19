DROP SCHEMA IF EXISTS Application;
DROP SCHEMA IF EXISTS Auth;

CREATE SCHEMA Application;
CREATE SCHEMA Auth;

USE Application;

CREATE TABLE tblAppSetting (
    intAppSettingId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    strAppSettingName VARCHAR(255) NOT NULL,
    strAppSettingValue VARCHAR(255) NOT NULL,
    UNIQUE KEY (strAppSettingName),
    PRIMARY KEY (intAppSettingId)
);

INSERT INTO tblAppSetting
    (strAppSettingName, strAppSettingValue)
VALUES
    ('app.name', 'My App');