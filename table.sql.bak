CREATE TABLE AdminUser (
EmpNo VARCHAR(40) NOT NULL UNIQUE,
username varchar(40) NOT NULL UNIQUE,
password varchar(40) NOT NULL,
admin INT NULL,
timesheet INT NULL,
estimating INT NULL,
accounting INT NULL,
dispatch INT NULL,
) 
Use Time;
CREATE SEQUENCE JobGroupCounter 
    AS int  
    START WITH 1  
    INCREMENT BY 1 ;  

CREATE TABLE JobGroup
(JobGroupID int PRIMARY KEY CLUSTERED DEFAULT (NEXT VALUE FOR JobGroupCounter),  
JobGroup VARCHAR (20) NOT NULL DEFAULT '',
CONSTRAINT [JB_id] UNIQUE NONCLUSTERED
(
[JobGroup]
)

);

CREATE TABLE JobGroupEmployee(
JobGroupID int NOT NULL,  
 
EmpNo VARCHAR(4) NOT NULL DEFAULT '', 
Job varchar(10) NOT NULL DEFAULT ''
CONSTRAINT [UQ_codes] UNIQUE NONCLUSTERED
(
   [EmpNo], [Job], [JobGroupID]
)

);

CREATE SEQUENCE PRHoursCounter 
    AS int  
    START WITH 1  
    INCREMENT BY 1 ;  

CREATE TABLE PRHours 
(PRHoursID int PRIMARY KEY CLUSTERED DEFAULT (NEXT VALUE FOR PRHoursCounter),  
EmpNo VARCHAR(4) ,
StartTime INT,
StopTime INT,
Hours FLOAT,
PayItemID VARCHAR(36));



CREATE SEQUENCE TimeClockAppCounter 
    AS int  
    START WITH 1  
    INCREMENT BY 1 ;  



CREATE TABLE TimeClockApp ( 
TimeClockID int PRIMARY KEY CLUSTERED DEFAULT (NEXT VALUE FOR TimeClockAppCounter),  
EmpNo VARCHAR(4) NOT NULL DEFAULT '', 
installationID VARCHAR(255) NOT NULL DEFAULT '',
Name varchar(10) NOT NULL DEFAULT '',
latitude DECIMAL(19,9) NOT NULL DEFAULT '0',
longitude DECIMAL(19,9) NOT NULL DEFAULT '0',
violation varchar(255) NOT NULL DEFAULT '',
event VARCHAR(10) not NULL DEFAULT '',
EmpActive INT NOT NULL DEFAULT '1',
StartTime INT NULL,
StopTime INT NULL,
image VARCHAR(255) NULL,
Dispatch VARCHAR(15) NULL,
Counter VARCHAR(4) NULL,
JobID VARCHAR(255) NULL,
Screen VARCHAR(255) NULL,
EmployeeNotes VARCHAR(255) NULL,
document VARCHAR(255) NULL,
CustSign VARCHAR(255) NULL,
customer VARCHAR(255) NULL,
Posted VARCHAR(255) NULL
);

CREATE SEQUENCE TimeClockAppHistCounter 
    AS int  
    START WITH 1  
    INCREMENT BY 1 ;  


CREATE TABLE TimeClockAppHist ( 
TimeClockHistID int PRIMARY KEY CLUSTERED DEFAULT (NEXT VALUE FOR TimeClockAppHistCounter),  
TimeClockID INT NOT NULL
EmpNo VARCHAR(4) NOT NULL DEFAULT '', 
installationID VARCHAR(255) NOT NULL DEFAULT '',
Name varchar(10) NOT NULL DEFAULT '',
latitude DECIMAL(19,9) NOT NULL DEFAULT '0',
longitude DECIMAL(19,9) NOT NULL DEFAULT '0',
violation varchar(255) NOT NULL DEFAULT '',
event VARCHAR(10) not NULL DEFAULT '',
EmpActive INT NOT NULL DEFAULT '1',
StartTime INT NULL,
StopTime INT NULL,
image VARCHAR(255) NULL,
Dispatch VARCHAR(15) NULL,
Counter VARCHAR(4) NULL,
JobID VARCHAR(255) NULL,
Screen VARCHAR(255) NULL,
EmployeeNotes VARCHAR(255) NULL,
document VARCHAR(255) NULL,
CustSign VARCHAR(255) NULL,
customer VARCHAR(255) NULL,
Posted VARCHAR(255) NULL
);


CREATE TABLE UserAppAuth (
EmpNo VARCHAR(4) PRIMARY KEY NOT NULL,
installationID varchar(255),
authorized INT NOT NULL DEFAULT '0'
);

CREATE TABLE LocationApi (
LocName varchar(150) PRIMARY KEY NOT NULL, 
Add1 varchar(30),
City varchar(25),
State varchar(2),
Zip varchar(10),
latitude numeric (28,13),
longitude numeric (28,13)
);

CREATE TABLE EmailUnsubscribe (Email VARCHAR(255) PRIMARY KEY NOT NULL);
