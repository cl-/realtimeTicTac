-- Dropping and creating a table (note PRIMARY KEY)
DROP TABLE worldstate;
CREATE TABLE worldstate (
	objectname VARCHAR(30) NOT NULL,
	xpos INTEGER,
	ypos INTEGER,
	world VARCHAR(30) NOT NULL,
	mTimestamp VARCHAR(100),
	lastdatemodified timestamp,
	PRIMARY KEY (objectname, world)
);

INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('x1', 30, 100, 'default', '0');
INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('x2', 30, 160, 'default', '0');
INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('x3', 30, 220, 'default', '0');
INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('x4', 30, 280, 'default', '0');
INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('x5', 30, 340, 'default', '0');
INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('o1', 30, 400, 'default', '0');
INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('o2', 30, 460, 'default', '0');
INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('o3', 30, 520, 'default', '0');
INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('o4', 30, 580, 'default', '0');
INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('o5', 30, 640, 'default', '0');
INSERT INTO worldstate(objectname, xpos, ypos, world, mTimestamp) VALUES ('tttboard', 150, 150, 'default', '0');

--INSERT INTO tasktable(usernames, taskname, totalworkunits, progressedunits, datecreated) VALUES('paul', 'task1', 5, '0', 'now');
--SELECT * from tasktable where usernames = 'paul' order by datecreated;
--Below are examples
