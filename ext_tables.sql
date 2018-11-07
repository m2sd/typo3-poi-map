#
# Table structure for table 'tx_poimap_location'
#
CREATE TABLE tx_poimap_domain_model_place (
  uid                        INT(11)                                    NOT NULL AUTO_INCREMENT,
  pid                        INT(11) DEFAULT '0'                        NOT NULL,

  name                       VARCHAR(255) DEFAULT ''                    NOT NULL,
  additional_type            VARCHAR(255) DEFAULT ''                    NOT NULL,
  alternate_name             VARCHAR(255) DEFAULT ''                    NOT NULL,
  description                TEXT                                       NOT NULL,
  disambiguating_description MEDIUMTEXT                                 NOT NULL,
  image                      INT(11) DEFAULT '0'                        NOT NULL,
  url                        VARCHAR(1024) DEFAULT ''                   NOT NULL,
  geo_coordinates            VARCHAR(50) DEFAULT ''                     NOT NULL,

  tstamp                     INT(11) UNSIGNED DEFAULT '0'               NOT NULL,
  crdate                     INT(11) UNSIGNED DEFAULT '0'               NOT NULL,
  cruser_id                  INT(11) UNSIGNED DEFAULT '0'               NOT NULL,
  deleted                    TINYINT(4) UNSIGNED DEFAULT '0'            NOT NULL,
  hidden                     TINYINT(4) UNSIGNED DEFAULT '0'            NOT NULL,
  starttime                  INT(11) UNSIGNED DEFAULT '0'               NOT NULL,
  endtime                    INT(11) UNSIGNED DEFAULT '0'               NOT NULL,
  fe_group                   VARCHAR(100) DEFAULT '0'                   NOT NULL,
  sorting                    INT(11) UNSIGNED DEFAULT '0'               NOT NULL,

  t3ver_oid                  INT(11) DEFAULT '0'                        NOT NULL,
  t3ver_id                   INT(11) DEFAULT '0'                        NOT NULL,
  t3ver_wsid                 INT(11) DEFAULT '0'                        NOT NULL,
  t3ver_label                VARCHAR(255) DEFAULT ''                    NOT NULL,
  t3ver_state                TINYINT(4) DEFAULT '0'                     NOT NULL,
  t3ver_stage                INT(11) DEFAULT '0'                        NOT NULL,
  t3ver_count                INT(11) DEFAULT '0'                        NOT NULL,
  t3ver_tstamp               INT(11) DEFAULT '0'                        NOT NULL,
  t3ver_move_id              INT(11) DEFAULT '0'                        NOT NULL,
  t3_origuid                 INT(11) DEFAULT '0'                        NOT NULL,

  sys_language_uid           INT(11) DEFAULT '0'                        NOT NULL,
  l10n_parent                INT(11) DEFAULT '0'                        NOT NULL,
  l10n_diffsource            MEDIUMBLOB,
  l10n_source                INT(11) DEFAULT '0'                        NOT NULL,

  PRIMARY KEY (uid),
  KEY parent (pid, sorting),
  KEY t3ver_oid (t3ver_oid, t3ver_wsid),
  KEY language (l10n_parent, sys_language_uid)
);