CREATE TABLE Manager (
    mid INTEGER PRIMARY KEY,
    name CHAR (30) NOT NULL,
    password INTEGER NOT NULL
    );



CREATE TABLE ResWebsite(
    website CHAR (50) PRIMARY KEY,
    name  CHAR (30) NOT NULL
    );

CREATE TABLE ResOpenYear(
    date_opened  DATE PRIMARY KEY,
    total_year   INTEGER
    );


CREATE TABLE Restaurant (
    rid   INTEGER PRIMARY KEY,
    phone CHAR (13) UNIQUE,
    website CHAR (50) NOT NULL,
    date_opened DATE,
    FOREIGN KEY (date_opened) REFERENCES ResOpenYear,
    FOREIGN KEY (website) REFERENCES ResWebsite
    );

CREATE TABLE AddressLineTwo (
    postal_code CHAR(6) PRIMARY KEY,
    city CHAR(20) NOT NULL ,
    province CHAR(20) NOT NULL
    );

CREATE TABLE AddressLineOne(
    house_num  CHAR(20),
    street  CHAR(100),
    postal_code CHAR(6),
    rid INTEGER UNIQUE NOT NULL,
    PRIMARY KEY(house_num,street,postal_code),
    FOREIGN KEY (rid) REFERENCES Restaurant
         ON DELETE CASCADE,
   FOREIGN KEY (postal_code) REFERENCES AddressLineTwo
   );

CREATE TABLE Manages (
    mid INTEGER,
    rid INTEGER,
    PRIMARY KEY(mid, rid),
    FOREIGN KEY (mid) REFERENCES Manager
       ON DELETE CASCADE,
    FOREIGN KEY (mid) REFERENCES Restaurant
       ON DELETE CASCADE
    );


CREATE TABLE Food(
    fid    INTEGER,
    name   CHAR (30) NOT NULL,
    price  REAL NOT NULL,
    rid    INTEGER,
    PRIMARY KEY(fid, rid),
    UNIQUE (name, rid),
    FOREIGN KEY (rid) REFERENCES Restaurant
        ON DELETE CASCADE
    );


CREATE TABLE Keyword(
    description CHAR(20) PRIMARY KEY
    );

CREATE TABLE KeywordsOfFood(
    fid INTEGER,
    rid INTEGER,
    description CHAR(20),
    PRIMARY KEY (fid, rid, description),
    FOREIGN KEY (fid, rid) REFERENCES Food
        ON DELETE CASCADE,
    FOREIGN KEY (description) REFERENCES Keyword
    );


CREATE TABLE PreCusDate(
    fee    REAL,
    start_d DATE,
    end_d   DATE NOT NULL,
    PRIMARY KEY(fee, start_d)
      );

CREATE TABLE Customer(
  cid  INTEGER PRIMARY KEY,
  name CHAR(30),
  phone CHAR (13) UNIQUE,
  password INTEGER NOT NULL
  );



CREATE TABLE PremiumCustomer(
  cid    INTEGER PRIMARY KEY,
  fee    REAL NOT NULL,
  start_d DATE NOT NULL,
  FOREIGN KEY (cid) REFERENCES Customer,
  FOREIGN KEY (fee, start_d) REFERENCES PreCusDate
);


CREATE TABLE CustomerLikes(
  cid INTEGER,
  fid INTEGER,
  rid INTEGER,
  PRIMARY KEY(cid,fid,rid),
  FOREIGN KEY (cid) REFERENCES Customer,
  FOREIGN KEY (fid, rid) REFERENCES Food
    ON DELETE CASCADE
  );


CREATE TABLE Review(
    rvid INTEGER PRIMARY KEY,
    rating INTEGER NOT NULL,
    food_comment CHAR (200),
    comment_date DATE DEFAULT SYSDATE,
    cid INTEGER NOT NULL,
    rid INTEGER NOT NULL,
    FOREIGN KEY (cid) REFERENCES Customer,
    FOREIGN KEY (rid) REFERENCES Restaurant
      ON DELETE CASCADE
    );


CREATE TABLE Photo(
    pid  INTEGER PRIMARY KEY,
    rvid  INTEGER NOT NULL,
    url CHAR(100) NOT NULL,
    FOREIGN KEY (rvid) REFERENCES Review
      ON DELETE CASCADE
    );


CREATE TABLE GiftedCoupon(
   cpid INTEGER PRIMARY KEY,
   value_worth REAL NOT NULL,
   expiryDate  DATE,
   cid  INTEGER,
   FOREIGN KEY (cid) REFERENCES Customer
    );

insert into Manager values(00001,'Charles Harris',99887766);
insert into Manager values(00002,'Susan Martin',002002);
insert into Manager values(00003,'Joseph Thompson',123456);
insert into Manager values(00004,'Christopher Garcia',1379);
insert into Manager values(00005,'Angela Martinez',00);

insert into ResWebsite values('www.ea4ever.com', 'EA Forever');
insert into ResWebsite values('www.koibean.com', 'KOI Bean');
insert into ResWebsite values('www.leafbakeddonkey.com', 'Leaf Baked Donkey');
insert into ResWebsite values('www.tripleos.com', 'TripleOs');
insert into ResWebsite values('www.waitingforgodotres.com', 'Waiting for Godot');

insert into ResOpenYear values ('19-NOV-2013', 7);
insert into ResOpenYear values ('24-JAN-2002', 18);
insert into ResOpenYear values ('22-NOV-2019', 1);
insert into ResOpenYear values ('25-MAY-2007', 13);
insert into ResOpenYear values ('21-DEC-1999', 21);

insert into Restaurant values(0001,'(242)518-9654','www.ea4ever.com','19-NOV-2013');
insert into Restaurant values(0002,'(619)023-5881','www.koibean.com','24-JAN-2002');
insert into Restaurant values(0003,'(248)965-2533','www.leafbakeddonkey.com','25-MAY-2007');
insert into Restaurant values(0004,'(828)236-9825','www.tripleos.com', '22-NOV-2019');
insert into Restaurant values(0005,'(853)062-4865','www.waitingforgodotres.com','21-DEC-1999');

insert into AddressLineTwo values ('M1L3K7', 'Scarborough', 'ON');
insert into AddressLineTwo values ('P4N7C4', 'Timmins', 'ON');
insert into AddressLineTwo values ('V6B3K9', 'Vancouver', 'BC');
insert into AddressLineTwo values ('P0L1W0', 'Moose Factory', 'ON');
insert into AddressLineTwo values ('P0C6J0', 'South River', 'ON');

insert into AddressLineOne values ('964', 'Merton St', 'M1L3K7', 0001);
insert into AddressLineOne values ('122', 'Algonquin Blvd', 'P4N7C4', 0002);
insert into AddressLineOne values ('3408', 'Robson St', 'V6B3K9', 0003);
insert into AddressLineOne values ('4034', 'James St', 'P0L1W0', 0004);
insert into AddressLineOne values ('113', 'Bellwood Acres Rd', 'P4N7C4', 0005);

insert into Manages values(00001,0005);
insert into Manages values(00002,0004);
insert into Manages values(00003,0003);
insert into Manages values(00004,0002);
insert into Manages values(00005,0001);


insert into Food values(007,'Chocolate Cookies', 2.99, 0001);
insert into Food values(010,'Chocolate Cookies', 2.99, 0002);
insert into Food values(007,'Chai Latte', 2.99, 0002);
insert into Food values(021,'Margherita Pizza', 12.99, 0003);
insert into Food values(020,'Monty Mushroom', 8.99, 0004);
insert into food values(021,'Pie',2.99,0001);

insert into Keyword values('Bakery');
insert into Keyword values('Spicy');
insert into Keyword values('Vegetarian');
insert into Keyword values('Cheesy');
insert into Keyword values('Hamburger');

insert into KeywordsOfFood values(007, 0001, 'Bakery');
insert into KeywordsOfFood values(010, 0002, 'Bakery');
insert into KeywordsOfFood values(007, 0002, 'Spicy');
insert into KeywordsOfFood values(021, 0003, 'Vegetarian');
insert into KeywordsOfFood values(021, 0003, 'Cheesy');


insert into PreCusDate values(5.99,'22-MAY-2020',  '22-JUNE-2020');
insert into PreCusDate values(59.99,'05-JAN-2020',  '05-JAN-2021');
insert into PreCusDate values( 14.99,'14-MAR-2020', '14-JUN-2019');
insert into PreCusDate values(29.99,'27-DEC-2019',  '27-JUN-2020');
insert into PreCusDate values(44.99, '14-FEB-2020', '14-NOV-2020');


insert into Customer values(00000001, 'Margaret D. Hill',  '(705)386-8567','1018402025');
insert into Customer values(00000002, 'Helen K. Joseph',  '(604)716-9879','123456');
insert into Customer values(00000003, 'Laurence Vachon',  '(514)808-8108','222333');
insert into Customer values(00000004, 'Cheng Meng', '(647)298-4500','994500');
insert into Customer values(00000005, 'Agostino Loggia', '(613)628-4428','29999');



insert into PremiumCustomer values(00000001, 5.99, '22-MAY-2020');
insert into PremiumCustomer values(00000003, 59.99, '05-JAN-2020');
insert into PremiumCustomer values(00000002, 14.99, '14-MAR-2020');
insert into PremiumCustomer values(00000004, 29.99, '27-DEC-2019');
insert into PremiumCustomer values(00000005, 44.99, '14-FEB-2020');


insert into CustomerLikes values(00000001,007, 0001);
insert into CustomerLikes values(00000001,010, 0002);
insert into CustomerLikes values(00000002,021, 0003);
insert into CustomerLikes values(00000002,020, 0004);
insert into CustomerLikes values(00000003,021, 0003);

insert into Review values(1, 4, NULL, '11-NOV-2019', 00000002, 0001);
insert into Review values(2, 5, 'Comfortable environment and delicious food! Will definitely come again!', '02-JAN-2020', 00000005, 0005);
insert into Review values(3, 4, 'Cookies are great.', '13-MAR-2020', 00000003, 0002);
insert into Review values(4, 5, NULL, '22-MAY-2020', 00000001, 0001);
insert into Review values(5, 5, 'Delicious food and friendly staff!', '22-MAY-2020', 00000001, 0001);
insert into Review values(6, 5, 'Love the environment!', '13-MAR-2020', 00000003, 0001);
insert into Review values(7, 3, '', '13-MAR-2020', 00000003, 0004);
insert into Review values(8, 2, 'Need to wait for a long time', '13-MAR-2020', 00000003, 0005);
insert into Review values(9, 5, 'Best pizza in town!', '13-MAR-2020', 00000003, 0003);

insert into Photo values(23849,1,'23849.png');
insert into Photo values(23850,2,'23850.png');
insert into Photo values(25673,3,'9512312.png');
insert into Photo values(34876,4,'100.png');
insert into Photo values(34877,4,'101.png');

insert into GiftedCoupon values(2345, 5, '30-JUN-2020', 00000005);
insert into GiftedCoupon values(2346, 10, '30-JUN-2020', 00000005);
insert into GiftedCoupon values(8237, 2, '10-JUN-2020', 00000001);
insert into GiftedCoupon values(8238, 3, '31-AUG-2020', 00000001);
insert into GiftedCoupon values(9287, 8, '31-OCT-2020', 00000001);
