use pease;

-- ******************************************************
--    DROP TABLES
-- ******************************************************
DROP table Address;
DROP table WellSample;
DROP table PersonPFCLevel;
DROP table StudyPFCLevel;
DROP table Person;
DROP table Well;
DROP table WellType;
DROP table Site;
DROP table Military;
DROP table Study;
DROP table Chemical;
DROP table ExposureType;
DROP table SampleNote;

-- ******************************************************
--    CREATE TABLES
-- ******************************************************


/* Table to store the different PFC chemicals present in the wells and blood samples */
CREATE table Chemical (
    chemID          int(11)         UNSIGNED    not null auto_increment,
    shortName       varchar(15)                 not null,
    longName        varchar(60)                 not null,
    epaPHALevel     decimal(10,6)               null,
    PRIMARY KEY (chemID)
);

/* Refrence table to store the type of exposure a given study looked at */
CREATE table ExposureType (
    exposureID        int(11)       UNSIGNED    not null,
    exposureType      varchar(45)               not null,
    PRIMARY KEY (exposureID)
);


/* Table to store the different studies on PFCs */
CREATE table Study (
    studyID         int(11)         UNSIGNED    not null auto_increment,
    exposureID      int(11)         UNSIGNED    not null,
    studyName       varchar(30)                 not null,
    studyStartDate  date                        not null,
    studyEndDate    date                        not null,
    participants    int(11)                     not null,
    PRIMARY KEY (studyID),
    FOREIGN KEY (exposureID)    REFERENCES ExposureType (exposureID) ON DELETE SET null
);


/* Reference table for the type of well - well or distribution point */
CREATE table WellType (
    wellTypeID      int(11)         UNSIGNED    not null auto_increment,
    wellType        varchar(20)                 not null,
    PRIMARY KEY (wellTypeID)
);


CREATE table Military (
    militaryID      int(11)         UNSIGNED    not null auto_increment,
    militaryBranch  varchar(20)                 not null,
    PRIMARY KEY (militaryID)
);


CREATE table Site (
    siteID          int(11)         UNSIGNED    not null auto_increment,
    militaryID      int(11)         UNSIGNED    null,
    siteName        varchar(30)                 not null,
    county          varchar(30)                 not null,
    city            varchar(30)                 not null,
    state           char(2)                     not null,
    PRIMARY KEY (siteID),
    FOREIGN KEY (militaryID)    REFERENCES Military (militaryID)    ON DELETE SET null
);


/* Table to store data on the different Pease wells */
CREATE table Well (
    wellID          int(11)         UNSIGNED    not null auto_increment,
    wellTypeID      int(11)         UNSIGNED    not null,
    siteID          int(11)         UNSIGNED    not null,
    wellName        varchar(30)                 not null,
    wellLat         decimal(20,18)              not null,
    wellLong        decimal(20,18)              not null,
    wellYeild       int(4)          UNSIGNED    null,
    wellActive      char(1)                     not null,
    PRIMARY KEY (wellID),
    FOREIGN KEY (wellTypeID)    REFERENCES WellType (wellTypeID)    ON DELETE SET null,
    FOREIGN KEY (siteID)        REFERENCES Site (siteID)            ON DELETE SET null
);


/* Table to store information about people who have had their blood tested */
CREATE table Person (
    personID        int(11)         UNSIGNED    not null auto_increment,
    personRecordID  varchar(10)                 not null,
    personAge       int(3)          UNSIGNED    not null,
    yearsExposed    int(2)          UNSIGNED    not null,
    sex             char(1)                     not null,
    PRIMARY KEY (personID)
);


/* PFC data for the different studies in the Study table.  Not all studies have the same data
   points (e.g. some use median and some use mean) which is why those columns can be null */
CREATE table StudyPFCLevel (
    studyPfcLevelID int(11)         UNSIGNED    not null auto_increment,
    studyID         int(11)         UNSIGNED    not null,
    chemID          int(11)         UNSIGNED    not null,
    pfcMin          decimal(12,6)   UNSIGNED    null,
    pfcMax          decimal(12,6)   UNSIGNED    null,
    pfcMean         decimal(12,6)   UNSIGNED    null,
    pfcGeoMean      decimal(12,6)   UNSIGNED    null,
    pfcMedian       decimal(12,6)   UNSIGNED    null,
    startAge        int(3)          UNSIGNED    null,
    endAge          int(3)          UNSIGNED    null, 
    PRIMARY KEY (studyPfcLevelID),
    FOREIGN KEY (studyID)   REFERENCES Study (studyID)              ON DELETE CASCADE,
    FOREIGN KEY (chemID)    REFERENCES Chemical (chemID)            ON DELETE SET null
);


/* Table to store PFC information about people who have had their blood tested */
CREATE table PersonPFCLevel (
    personID        int(11)         UNSIGNED    not null auto_increment,
    chemID          int(11)         UNSIGNED    not null,
    pfcLevel        decimal(12,6)   UNSIGNED    not null,
    PRIMARY KEY (personID, chemID),
    FOREIGN KEY (personID)  REFERENCES Person (personID)            ON DELETE CASCADE,
    FOREIGN KEY (chemID)    REFERENCES Chemical (chemID)            ON DELETE SET null
);


/* Some well samples have 'notes' attached to them - reference table */
CREATE table SampleNote (
    noteID          int(11)         UNSIGNED    not null auto_increment,
    noteAbr         char(1)                     not null,
    noteDescr       varchar(40)                 not null,
    PRIMARY KEY (noteID)
);


/* Table to store information about the ongoing well sampling */
CREATE table WellSample (
    sampleID        int(11)         UNSIGNED    not null auto_increment,
    wellID          int(11)         UNSIGNED    not null,
    chemID          int(11)         UNSIGNED    not null,
    noteID          int(11)         UNSIGNED    null,
    sampleDate      date                        not null,
    pfcLevel        decimal(10,7)   UNSIGNED    null,
    PRIMARY KEY (sampleID),
    FOREIGN KEY (wellID)    REFERENCES Well (wellID)                ON DELETE CASCADE,
    FOREIGN KEY (chemID)    REFERENCES Chemical (chemID)            ON DELETE SET null,
    FOREIGN KEY (noteID)    REFERENCES SampleNote (noteID)          ON DELETE SET null
);


/* Table to store the location of where the people on Pease were exposed */
CREATE table Address (
    addressID       int(11)         UNSIGNED    not null auto_increment,
    personID        int(11)         UNSIGNED    not null,
    address         varchar(45)                 not null,
    PRIMARY KEY (addressID),
    FOREIGN KEY (personID)  REFERENCES Person (personID)            ON DELETE CASCADE
);




-- ******************************************************
--    CREATE TRIGGER - Fulfiling trigger requirement 
-- ******************************************************
delimiter //
CREATE TRIGGER trig_YN_Well_wellActive BEFORE INSERT ON Well
FOR EACH ROW
BEGIN
  IF (NEW.wellActive REGEXP '^Y|N$') = 0 THEN
    SET NEW.wellYeild = null;
  END IF;
END
//
CREATE TRIGGER trig_YN_Person_sex BEFORE INSERT ON Person
FOR EACH ROW
BEGIN
  IF (NEW.sex REGEXP '^M|F$') = 0 THEN
    SET NEW.sex = null;
  END IF;
END
//

delimiter ;


-- ******************************************************
--    POPULATE TABLES
-- ******************************************************

/* chemical table */
INSERT INTO Chemical (shortName, longName, epaPHALevel) VALUES ('PFOA', 'Perfluorooctanoic acid', .4);
INSERT INTO Chemical (shortName, longName, epaPHALevel) VALUES ('PFOS', 'Perfluorooctanesulfonic acid', .2);
INSERT INTO Chemical (shortName, longName, epaPHALevel) VALUES ('PFHxS', 'Perfluorohexanesulphonic acid', null);
INSERT INTO Chemical (shortName, longName, epaPHALevel) VALUES ('PFUA', 'Perfluoroundecanoic acid', null);
INSERT INTO Chemical (shortName, longName, epaPHALevel) VALUES ('PFOSA', 'Perfluorooctane sulfonamide', null);
INSERT INTO Chemical (shortName, longName, epaPHALevel) VALUES ('PFNA', 'Perfluorononanoic acid', null);
INSERT INTO Chemical (shortName, longName, epaPHALevel) VALUES ('PFDeA', 'Perfluorodecanoic acid', null);
INSERT INTO Chemical (shortName, longName, epaPHALevel) VALUES ('PFPeA', 'Perfluorotetradecanoic acid', null);
INSERT INTO Chemical (shortName, longName, epaPHALevel) VALUES ('PFHxA', 'Perfluorohexanoic acid', null);
INSERT INTO Chemical (shortName, longName, epaPHALevel) VALUES ('PFBA', 'Perfluorobutanoic acid', null);

/* exposure type table */
INSERT INTO ExposureType (exposureType) VALUES ('Occupational');
INSERT INTO ExposureType (exposureType) VALUES ('Environmental');
INSERT INTO ExposureType (exposureType) VALUES ('General Population');

/* study table */
INSERT INTO Study (exposureID, studyName, studyStartDate, studyEndDate, participants) VALUES (1, '3M Workers (PFOS and PFOA)', '2000-01-01', '2000-12-31', 263);
INSERT INTO Study (exposureID, studyName, studyStartDate, studyEndDate, participants) VALUES (1, '3M Workers (PFHxS)', '2004-01-01', '2004-12-31', 26);
INSERT INTO Study (exposureID, studyName, studyStartDate, studyEndDate, participants) VALUES (1, 'Dupont Workers', '2004-01-01', '2004-12-31', 1025);
INSERT INTO Study (exposureID, studyName, studyStartDate, studyEndDate, participants) VALUES (2, 'Ohio River Valley - C8', '2005-01-01', '2006-12-31', 69030);
INSERT INTO Study (exposureID, studyName, studyStartDate, studyEndDate, participants) VALUES (2, 'Decatur, Alabama', '2009-01-01', '2009-12-31', 153);
INSERT INTO Study (exposureID, studyName, studyStartDate, studyEndDate, participants) VALUES (2, 'East Metro Minnesota Pilot', '2008-01-01', '2009-12-31', 196);
INSERT INTO Study (exposureID, studyName, studyStartDate, studyEndDate, participants) VALUES (3, 'Red Cross donors', '2006-01-01', '2006-12-31', 600);
INSERT INTO Study (exposureID, studyName, studyStartDate, studyEndDate, participants) VALUES (3, 'NHANES 1', '2003-01-01', '2004-12-31', 2094);
INSERT INTO Study (exposureID, studyName, studyStartDate, studyEndDate, participants) VALUES (3, 'NHANES 2', '2011-01-01', '2012-12-31', 1904);
INSERT INTO Study (exposureID, studyName, studyStartDate, studyEndDate, participants) VALUES (3, 'Schecter', '2009-01-01', '2009-12-31', 300);

/* Well Type table */
INSERT INTO WellType (wellType) VALUES ('Well');
INSERT INTO WellType (wellType) VALUES ('Distribution Point');

/* Military Branch table */
INSERT INTO Military (militaryBranch) VALUES ('Air Force');
INSERT INTO Military (militaryBranch) VALUES ('Navy');
INSERT INTO Military (militaryBranch) VALUES ('Army');
INSERT INTO Military (militaryBranch) VALUES ('Coast Guard');

/* Site ID */
INSERT INTO Site (1, 'Pease Tradeport', 'Rockingham', 'Portsmouth', 'NH');

/* well table */
INSERT INTO Well (wellTypeID, siteID, wellName, wellLat, wellLong, wellYeild, wellActive) VALUES (1, 'Haven', 43.076018, -70.818631, 699, 'N');
INSERT INTO Well (wellTypeID, siteID, wellName, wellLat, wellLong, wellYeild, wellActive) VALUES (1, 'Smith', 43.061068, -70.804976, 447, 'Y');
INSERT INTO Well (wellTypeID, siteID, wellName, wellLat, wellLong, wellYeild, wellActive) VALUES (1, 'Harrison', 43.065879, -70.804495, 331, 'Y');
INSERT INTO Well (wellTypeID, siteID, wellName, wellLat, wellLong, wellYeild, wellActive) VALUES (2, 'WWTP Distribution',43.083631, -70.795990, null, 'Y');
INSERT INTO Well (wellTypeID, siteID, wellName, wellLat, wellLong, wellYeild, wellActive) VALUES (2, 'DES Office Distribution', 43.074757, -70.802534, null, 'Y');

/* sample note table */
INSERT INTO SampleNote (noteAbr, noteDescr) VALUES ('J', 'The result is an estimated value');
INSERT INTO SampleNote (noteAbr, noteDescr) VALUES ('B', 'Detected in Blank');
INSERT INTO SampleNote (noteAbr, noteDescr) VALUES ('D', 'duplicate sample');

/* person table */
INSERT INTO Person (nhHHSID, age, yearsExposed, sex) VALUES ('PT0576', 40, 13, 'M');
INSERT INTO Person (nhHHSID, age, yearsExposed, sex) VALUES ('PT0577', 4, 2, 'F');

/* address table */
INSERT INTO Address (personID, address) VALUES (1, '325 Corporate Drive Portsmouth, NH  03801');
INSERT INTO Address (personID, address) VALUES (2, '81 New Hampshire Avenue Portsmouth, NH 03801');

/* person PFC level table */
INSERT INTO PersonPFCLevel VALUES (1, 1, 2.6);
INSERT INTO PersonPFCLevel VALUES (1, 2, 12.7);
INSERT INTO PersonPFCLevel VALUES (1, 3, 6.5);
INSERT INTO PersonPFCLevel VALUES (1, 4, 0);
INSERT INTO PersonPFCLevel VALUES (1, 5, 0);
INSERT INTO PersonPFCLevel VALUES (1, 6, .9);
INSERT INTO PersonPFCLevel VALUES (1, 7, .2);
INSERT INTO PersonPFCLevel VALUES (1, 8, .2);
INSERT INTO PersonPFCLevel VALUES (1, 9, 0);
INSERT INTO PersonPFCLevel VALUES (2, 1, 7.2);
INSERT INTO PersonPFCLevel VALUES (2, 2, 10.5);
INSERT INTO PersonPFCLevel VALUES (2, 3, 9.8);
INSERT INTO PersonPFCLevel VALUES (2, 4, .1);
INSERT INTO PersonPFCLevel VALUES (2, 5, .1);
INSERT INTO PersonPFCLevel VALUES (2, 6, 2);
INSERT INTO PersonPFCLevel VALUES (2, 7, .2);
INSERT INTO PersonPFCLevel VALUES (2, 8, .6);
INSERT INTO PersonPFCLevel VALUES (2, 9, .1);

/* study level table */
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (1, 1, 40, 12700, 1780, 1130, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (1, 2, 60, 10060, 1320, 910, null, null, 'Y');

INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (2, 1, 72, 5100, 691, null, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (2, 2, 145, 3490, 799, null, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (2, 3, 16, 1295, 290, null, null, null, 'Y');

INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (3, 1, .0094, 3.709, null, null, .2448, null, 'Y');

INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (4, 1, null, null, 77.6, 32.6, 36.9, '< 12 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (4, 2, null, null, 23.6, 20.7, 20.6, '< 12 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (4, 6, null, null, 1.9, 1.6, 1.7, '< 12 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (4, 9, null, null, 1, .7, .7, '< 12 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (4, 1, null, null, 58.1, 21.8, 25.2, '20 - 39 years', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (4, 2, null, null, 20.1, 18.1, 16.8, '20 - 39 years', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (4, 6, null, null, 1.5, 1.4, 1.4, '20 - 39 years', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (4, 9, null, null, .9, .5, .6, '20 - 39 years', 'Y');

INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (5, 1, 2.2, 144, 23.31, 16.3, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (5, 2, 5.4, 472, 56.60, 39.8, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (5, 3, .6, 59.1, 9.04, 6.4, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (5, 5, .1, .1, null, null, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (5, 6, .3, 5.5, 1.86, 1.7, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (5, 7, .2, 2.5, null, .4, null, null, 'Y');

INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (6, 1, 1.6, 177, null, 15.4, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (6, 2, 3.2, 448, null, 35.9, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (6, 3, .32, 316, null, 8.4, null, null, 'Y');

INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (7, 1, null, null, null, 3.4, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (7, 2, null, null, null, 14.5, null, null, 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (7, 3, null, null, null, 1.5, null, null, 'Y');

INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (8, 1, null, null, null, 3.89, null, '12 - 19 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (8, 1, null, null, null, 3.96, null, '20 years and older', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (8, 2, null, null, null, 19.3, null, '12 - 19 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (8, 2, null, null, null, 20.9, null, '20 years and older', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (8, 3, null, null, null, 2.44, null, '12 - 19 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (8, 3, null, null, null, 1.86, null, '20 years and older', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (8, 6, null, null, null, .852, null, '12 - 19 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (8, 6, null, null, null, .984, null, '20 years and older', 'Y');

INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 1, null, null, null, 1.8, null, '12 - 19 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 1, null, null, null, 2.12, null, '20 years and older', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 2, null, null, null, 4.16, null, '12 - 19 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 2, null, null, null, 6.71, null, '20 years and older', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 3, null, null, null, 1.28, null, '12 - 19 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 3, null, null, null, 1.28, null, '20 years and older', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 4, null, null, null, .146, null, '20 years and older', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 6, null, null, null, .741, null, '12 - 19 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 6, null, null, null, .903, null, '20 years and older', 'Y');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 7, null, null, null, .146, null, '12 - 19 years', 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (9, 7, null, null, null, .209, null, '20 years and older', 'Y');

INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (10, 1, 0, 13.5, null, null, 2.9, null, 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (10, 2, .1, 93.3, null, null, 4.1, null, 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (10, 3, 0, 31.2, null, null, 1.2, null, 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (10, 5, 0, .6, null, null, 0, null, 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (10, 6, 0, 55.8, null, null, 1.2, null, 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (10, 7, .1, 2.1, null, null, .1, null, 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (10, 8, .1, 28.9, null, null, .1, null, 'N');
INSERT INTO StudyPFCLevel (studyID, chemID, pfcMin, pfcMax, pfcMean, pfcGeoMean, pfcMedian, startAge, endAge) VALUES (10, 9, .1, .7, null, null, .1, null, 'N');


/* well sample table */    

/* PFOA */
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (1, 1, '2014-04-14', .35, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (1, 1, '2014-05-14', .32, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-04-14', .009, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-05-14', .0086, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-07-14', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-07-14', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-09-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-09-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-10-01', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-10-16', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-10-29', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-11-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-11-24', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2014-12-22', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-01-05', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-01-21', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-02-04', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-02-19', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-03-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-03-17', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-03-26', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-04-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-04-23', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-05-07', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-05-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-06-03', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-06-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-07-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-07-31', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 1, '2015-08-11', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-04-14', .0035, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-05-14', .0036, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-09-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-09-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-09-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-10-01', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-10-08', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-10-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-10-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-10-29', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-11-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-11-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-11-19', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-11-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-12-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-12-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-12-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2014-12-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-01-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-01-13', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-01-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-01-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-02-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-02-19', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-02-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-03-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-03-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-03-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-03-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-04-02', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-04-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-04-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-04-23', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-04-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-05-07', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-05-15', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-05-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-05-27', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-06-03', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-06-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-06-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-06-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-07-08', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-07-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-07-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-07-31', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-08-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-08-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 1, '2015-08-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 1, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 1, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 1, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 1, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 1, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 1, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 1, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 1, '2015-03-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 1, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 1, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 1, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 1, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 1, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 1, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 1, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 1, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 1, '2015-06-16', 0, null);


/* PFOS */
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (1, 2, '2014-04-14', 2.5, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (1, 2, '2014-05-14', 2.4, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-04-14', .048, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-05-14', .041, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-06-18', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-06-25', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-07-14', 0.027, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-07-14', 0.026, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-07-09', 0.02, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-07-14', 0.026, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-07-14', 0.027, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-07-24', 0.027, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-08-06', 0.02, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-08-21', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-09-14', 0.027, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-09-17', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-10-01', 0.031, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-10-16', 0.035, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-10-29', 0.027, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-11-12', 0.034, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-11-24', 0.038, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-12-12', 0.031, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2014-12-22', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-01-05', 0.038, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-01-21', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-02-04', 0.021, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-02-19', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-03-06', 0.031, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-03-17', 0.029, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-03-26', 0.028, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-04-09', 0.028, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-04-23', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-05-07', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-05-21', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-06-03', 0.024, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-06-16', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-06-30', 0.027, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-07-16', 0.026, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-07-31', 0.028, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 2, '2015-08-11', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-04-14', .018, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-05-14', .015, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-06-18', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-06-25', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-07-14', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-07-09', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-07-14', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-07-24', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-08-06', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-08-21', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-09-14', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-09-17', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-09-24', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-10-01', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-10-08', 0.014, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-10-16', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-10-22', 0.013, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-10-29', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-11-06', 0.013, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-11-12', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-11-19', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-11-24', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-12-04', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-12-12', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-12-16', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-12-22', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2014-12-30', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-01-05', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-01-13', 0.014, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-01-21', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-01-26', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-02-04', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-02-19', 0.014, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-02-25', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-03-06', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-03-11', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-03-17', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-03-26', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-04-02', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-04-09', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-04-16', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-04-23', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-04-30', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-05-07', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-05-15', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-05-21', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-05-27', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-06-03', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-06-12', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-06-16', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-06-24', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-06-30', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-07-08', 0.013, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-07-16', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-07-21', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-07-31', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-08-05', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-08-11', 0.015, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 2, '2015-08-18', 0.013, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 2, '2014-06-18', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 2, '2014-06-25', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 2, '2014-07-14', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 2, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 2, '2014-07-14', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 2, '2014-07-24', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 2, '2014-12-12', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 2, '2015-03-18', 0.016, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 2, '2015-06-16', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 2, '2014-06-18', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 2, '2014-06-25', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 2, '2014-07-14', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 2, '2014-07-09', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 2, '2014-07-14', 0.014, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 2, '2014-07-24', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 2, '2014-12-12', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 2, '2015-06-16', 0.010, 1);


/* PFHxS */
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (1, 3, '2014-04-14', 0.83, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (1, 3, '2014-05-14', 0.96, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-04-14', 0.036, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-05-14', 0.032, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-06-18', 0.026, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-06-25', 0.021, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-07-14', 0.021, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-07-14', 0.02, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-07-09', 0.019, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-07-14', 0.028, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-07-14', 0.029, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-07-24', 0.024, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-08-06', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-08-21', 0.015, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-09-14', 0.027, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-09-17', 0.026, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-10-01', 0.03, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-10-16', 0.031, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-10-29', 0.026, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-11-12', 0.029, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-11-24', 0.038, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-12-12', 0.031, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2014-12-22', 0.027, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-01-05', 0.035, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-01-21', 0.031, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-02-04', 0.028, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-02-19', 0.024, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-03-06', 0.025, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-03-17', 0.024, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-03-26', 0.026, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-04-09', 0.021, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-04-23', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-05-07', 0.021, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-05-21', 0.023, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-06-03', 0.023, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-06-16', 0.022, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-06-30', 0.024, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-07-16', 0.023, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-07-31', 0.023, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 3, '2015-08-11', 0.027, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-04-14', 0.013, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-05-14', 0.013, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-06-18', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-06-25', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-07-14', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-07-09', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-07-09', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-07-14', 0.014, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-07-24', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-08-06', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-08-21', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-09-14', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-09-17', 0.013, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-09-24', 0.013, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-10-01', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-10-08', 0.014, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-10-16', 0.013, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-10-22', 0.013, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-10-29', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-11-06', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-11-12', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-11-19', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-11-24', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-12-04', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-12-12', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-12-16', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-12-22', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2014-12-30', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-01-05', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-01-13', 0.013, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-01-21', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-01-26', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-02-04', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-02-19', 0.013, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-02-25', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-03-06', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-03-11', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-03-17', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-03-26', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-04-02', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-04-09', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-04-16', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-04-23', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-04-30', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-05-07', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-05-15', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-05-21', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-05-27', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-06-03', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-06-12', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-06-16', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-06-24', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-06-30', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-07-08', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-07-16', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-07-21', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-07-31', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-08-05', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-08-11', 0.017, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 3, '2015-08-18', 0.015, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 3, '2014-06-18', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 3, '2014-06-25', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 3, '2014-07-14', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 3, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 3, '2014-07-14', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 3, '2014-07-24', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 3, '2014-12-12', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 3, '2015-03-18', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 3, '2015-06-16', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 3, '2014-06-18', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 3, '2014-06-25', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 3, '2014-07-14', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 3, '2014-07-09', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 3, '2014-07-14', 0.019, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 3, '2014-07-24', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 3, '2014-12-12', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 3, '2015-06-16', 0.012, 1);

/* PFOSA */
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-09-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-09-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-10-01', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-10-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-10-29', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-11-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-11-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2014-12-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-01-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-01-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-02-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-02-19', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-03-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-03-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-03-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-04-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-04-23', 0.002, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-05-07', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-05-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-06-03', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-06-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-07-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-07-31', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 5, '2015-08-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-07-14', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-09-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-09-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-09-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-10-01', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-10-08', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-10-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-10-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-10-29', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-11-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-11-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-11-19', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-11-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-12-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-12-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-12-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2014-12-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-01-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-01-13', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-01-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-01-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-02-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-02-19', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-02-25', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-03-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-03-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-03-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-03-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-04-02', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-04-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-04-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-04-23', 0.002, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-04-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-05-07', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-05-15', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-05-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-05-27', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-06-03', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-06-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-06-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-06-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-07-08', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-07-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-07-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-07-31', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-08-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-08-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 5, '2015-08-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 5, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 5, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 5, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 5, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 5, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 5, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 5, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 5, '2015-03-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 5, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 5, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 5, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 5, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 5, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 5, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 5, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 5, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 5, '2015-06-16', 0, null);

/* PFNA */
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (1, 6, '2014-04-14', 0.017, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (1, 6, '2014-05-14', 0.017, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-04-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-05-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-09-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-09-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-10-01', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-10-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-10-29', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-11-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-11-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2014-12-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-01-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-01-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-02-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-02-19', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-03-06', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-03-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-03-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-04-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-04-23', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-05-07', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-05-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-06-03', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-06-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-07-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-07-31', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 6, '2015-08-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-04-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-05-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-09-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-09-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-09-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-10-01', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-10-08', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-10-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-10-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-10-29', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-11-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-11-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-11-19', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-11-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-12-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-12-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-12-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2014-12-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-01-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-01-13', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-01-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-01-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-02-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-02-19', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-02-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-03-06', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-03-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-03-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-03-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-04-02', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-04-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-04-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-04-23', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-04-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-05-07', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-05-15', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-05-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-05-27', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-06-03', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-06-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-06-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-06-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-07-08', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-07-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-07-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-07-31', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-08-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-08-11', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 6, '2015-08-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 6, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 6, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 6, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 6, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 6, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 6, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 6, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 6, '2015-03-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 6, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 6, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 6, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 6, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 6, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 6, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 6, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 6, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 6, '2015-06-16', 0, null);

/* PFPeA */
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-06-18', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-06-25', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-07-14', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-07-14', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-07-14', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-07-24', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-08-06', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-08-21', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-09-14', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-09-17', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-10-01', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-10-16', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-10-29', 0.015, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-11-12', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-11-24', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-12-12', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2014-12-22', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-01-05', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-01-21', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-02-04', 0.013, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-02-19', 0.014, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-03-06', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-03-17', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-03-26', 0.009, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-04-09', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-04-23', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-05-07', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-05-21', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-06-03', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-06-16', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-06-30', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-07-16', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-07-31', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 8, '2015-08-11', 0.012, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-06-18', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-07-14', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-09-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-09-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-09-24', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-10-01', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-10-08', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-10-16', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-10-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-10-29', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-11-06', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-11-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-11-19', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-11-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-12-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-12-16', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-12-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2014-12-30', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-01-05', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-01-13', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-01-21', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-01-26', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-02-04', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-02-19', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-02-25', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-03-06', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-03-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-03-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-03-26', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-04-02', 0.005, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-04-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-04-16', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-04-23', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-04-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-05-07', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-05-15', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-05-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-05-27', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-06-03', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-06-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-06-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-06-30', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-07-08', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-07-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-07-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-07-31', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-08-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-08-11', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 8, '2015-08-18', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 8, '2014-06-18', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 8, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 8, '2014-07-14', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 8, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 8, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 8, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 8, '2014-12-12', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 8, '2015-03-18', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 8, '2015-06-16', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 8, '2014-06-18', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 8, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 8, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 8, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 8, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 8, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 8, '2014-12-12', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 8, '2015-06-16', 0.004, 1);

/* PFHxA */
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (1, 9, '2014-04-14', 0.33, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (1, 9, '2014-05-14', 0.35, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-04-14', 0.0087, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-05-14', 0.01, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-06-18', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-07-14', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-07-14', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-07-09', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-09-14', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-09-17', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-10-01', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-10-16', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-10-29', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-11-12', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-11-24', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-12-12', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2014-12-22', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-01-05', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-01-21', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-02-04', 0.010, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-02-19', 0.011, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-03-06', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-03-17', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-03-26', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-04-09', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-04-23', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-05-07', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-05-21', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-06-03', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-06-30', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-07-16', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-07-31', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 9, '2015-08-11', 0.008, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-04-14', 0.0039, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-05-14', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-07-14', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-09-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-09-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-09-24', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-10-01', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-10-08', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-10-16', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-10-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-10-29', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-11-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-11-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-11-19', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-11-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-12-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-12-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-12-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2014-12-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-01-05', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-01-13', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-01-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-01-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-02-04', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-02-19', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-02-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-03-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-03-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-03-17', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-03-26', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-04-02', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-04-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-04-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-04-23', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-04-30', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-05-07', 0.002, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-05-15', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-05-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-05-27', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-06-03', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-06-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-06-16', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-06-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-06-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-07-08', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-07-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-07-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-07-31', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-08-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-08-11', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 9, '2015-08-18', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 9, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 9, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 9, '2014-07-14', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 9, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 9, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 9, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 9, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 9, '2015-03-18', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 9, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 9, '2014-06-18', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 9, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 9, '2014-07-14', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 9, '2014-07-09', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 9, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 9, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 9, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 9, '2015-06-16', 0, null);

/* PFBA */
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-06-18', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-07-14', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-07-14', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-07-09', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-09-14', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-09-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-10-01', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-10-16', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-10-29', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-11-12', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-11-24', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2014-12-22', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-01-05', 0.005, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-01-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-02-04', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-02-19', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-03-06', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-03-17', 0.004, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-03-26', 0.009, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-04-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-04-23', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-05-07', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-05-21', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-06-03', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-06-16', 0.005, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-06-30', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-07-16', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-07-31', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (3, 10, '2015-08-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-07-14', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-08-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-08-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-09-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-09-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-09-24', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-10-01', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-10-08', 0.007, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-10-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-10-22', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-10-29', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-11-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-11-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-11-19', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-11-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-12-04', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-12-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-12-22', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2014-12-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-01-05', 0.005, 2);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-01-13', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-01-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-01-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-02-04', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-02-19', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-02-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-03-06', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-03-11', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-03-17', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-03-26', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-04-02', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-04-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-04-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-04-23', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-04-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-05-07', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-05-15', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-05-21', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-05-27', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-06-03', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-06-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-06-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-06-30', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-07-08', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-07-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-07-21', 0.003, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-07-31', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-08-05', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-08-11', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (2, 10, '2015-08-18', 0.007, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 10, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 10, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 10, '2014-07-14', 0.006, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 10, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 10, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 10, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 10, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 10, '2015-03-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (4, 10, '2015-06-16', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 10, '2014-06-18', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 10, '2014-06-25', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 10, '2014-07-14', 0.002, 1);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 10, '2014-07-09', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 10, '2014-07-14', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 10, '2014-07-24', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 10, '2014-12-12', 0, null);
INSERT INTO WellSample (wellID, chemID, sampleDate, pfcLevel, noteID) VALUES (5, 10, '2015-06-16', 0, null);


/* SELECT Statments on new tables */
SELECT * FROM Chemical;
SELECT * FROM ExposureType;
SELECT * FROM Study;
SELECT * FROM WellType;
SELECT * FROM Well;
SELECT * FROM Person;
SELECT * FROM Address;
SELECT * FROM StudyPFCLevel LIMIT 10;
SELECT * FROM PersonPFCLevel LIMIT 10;
SELECT * FROM SampleNote;
SELECT * FROM WellSample LIMIT 10;