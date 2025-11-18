DROP SCHEMA IF EXISTS Auth;
DROP SCHEMA IF EXISTS Core;

CREATE SCHEMA Auth;
CREATE SCHEMA Core;

USE Auth;

CREATE TABLE tblUser (
    intUserId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    strEmail VARCHAR(180) NOT NULL,
    strPassword VARCHAR(255) NOT NULL COMMENT 'Hashed password',
    bolVerified TINYINT(1) NOT NULL DEFAULT 0,
    bolActive TINYINT(1) NOT NULL DEFAULT 1,
    dtmCreated DATETIME NOT NULL DEFAULT NOW(),
    dtmUpdated DATETIME ON UPDATE NOW(),
    PRIMARY KEY (intUserId),
    UNIQUE KEY UK_tblUser_strEmail (strEmail),
    INDEX I_tblUser_bolActive (bolActive),
    INDEX I_tblUser_bolVerified (bolVerified),
    INDEX I_tblUser_dtmCreated (dtmCreated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE tblVerificationToken (
    intVerificationTokenId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    intUserId INT UNSIGNED NOT NULL,
    strToken VARCHAR(100) NOT NULL,
    dtmExpires DATETIME NOT NULL,
    dtmCreated DATETIME NOT NULL DEFAULT NOW(),
    dtmUpdated DATETIME ON UPDATE NOW(),
    PRIMARY KEY (intVerificationTokenId),
    UNIQUE KEY UK_tblVerificationToken_strToken (strToken),
    INDEX I_tblVerificationToken_intUserId (intUserId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE tblPasswordResetToken (
    intPasswordResetTokenId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    intUserId INT UNSIGNED NOT NULL,
    strToken VARCHAR(100) NOT NULL,
    dtmExpires DATETIME NOT NULL,
    dtmCreated DATETIME NOT NULL DEFAULT NOW(),
    dtmUpdated DATETIME ON UPDATE NOW(),
    PRIMARY KEY (intPasswordResetTokenId),
    UNIQUE KEY UK_tblPasswordResetToken_strToken (strToken),
    INDEX I_tblPasswordResetToken_intUserId (intUserId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

USE Core;

CREATE TABLE ublEmailType (
    intEmailTypeId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    strEmailTypeSubject VARCHAR(255) NOT NULL,
    strEmailTypeHandle VARCHAR(100) NOT NULL,
    strTemplate VARCHAR(255) NOT NULL,
    PRIMARY KEY (intEmailTypeId),
    UNIQUE KEY UK_ublEmailType_strEmailType (strEmailTypeHandle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO ublEmailType
    (strEmailTypeSubject, strEmailTypeHandle, strTemplate)
VALUES
    ('Verify your email address', 'VERIFY_EMAIL_ADDRESS', '_core/emails/verify-email.latte'),
('Reset your password', 'PASSWORD_RESET', '_core/emails/reset-password.latte');