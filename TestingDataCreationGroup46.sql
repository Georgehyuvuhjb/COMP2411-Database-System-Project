CREATE TABLE Users (
  User_Name varchar(25) NOT NULL,
  actual_name varchar(100) NOT NULL,
  email_address varchar(100) NOT NULL,
  date_of_birth DATE NOT NULL,
  telephone_number NUMBER(10) NOT NULL,
  password varchar(25) NOT NULL,
  PRIMARY KEY(User_Name)
);
CREATE TABLE Admin (
  Admin_ID int NOT NULL,
  actual_name varchar(100) NOT NULL,
  email_address varchar(100) NOT NULL,
  date_of_birth DATE NOT NULL,
  telephone_number NUMBER(10) NOT NULL,
  password varchar(25) NOT NULL,
  PRIMARY KEY (Admin_ID)
);
CREATE TABLE Store (
  Store_ID int NOT NULL,
  store_description varchar(225),
  store_name varchar(25) NOT NULL,
  Admin_ID int NOT NULL,
  PRIMARY KEY (Store_ID),
  FOREIGN KEY (Admin_ID)
  REFERENCES Admin(Admin_ID)
);
CREATE TABLE Category (
  Category_ID int NOT NULL,
  category_name varchar(50) NOT NULL,
  PRIMARY KEY (Category_ID)
);
CREATE TABLE Payment_Method (
  Method_ID NUMBER(3) NOT NULL,
  method_name varchar(15) NOT NULL,
  PRIMARY KEY (Method_ID)
);
CREATE TABLE Products (
  Product_ID int NOT NULL,
  product_name varchar(50) NOT NULL,
  product_description varchar(100) NOT NULL,
  product_brand varchar(25),
  stock NUMBER(5) NOT NULL,
  out_of_factory_price NUMBER(10,2),
  dimensions varchar(20),
  weight NUMBER(7,2),
  suitable_age_range varchar(10),
  current_selling_price NUMBER(10,2) NOT NULL,
  discount NUMBER(3, 2) NOT NULL,
  Store_ID int NOT NULL,
  PRIMARY KEY (Product_ID),
  FOREIGN KEY (Store_ID) REFERENCES Store(Store_ID)
);
CREATE TABLE Product_Belong_To (
 Product_ID int NOT NULL,
 Category_ID int NOT NULL,
 FOREIGN KEY (Product_ID) REFERENCES Products(Product_ID),
 FOREIGN KEY (Category_ID) REFERENCES Category(Category_ID)
);
Create TABLE In_Cart(
  quantity NUMBER(3) NOT NULL,
  Product_ID int NOT NULL,
  User_Name varchar(25) NOT NULL,
  FOREIGN KEY (Product_ID) REFERENCES Products(Product_ID),
  FOREIGN KEY (User_Name) REFERENCES Users(User_Name)
);
CREATE TABLE Has_Payment(
  Method_ID NUMBER(3) NOT NULL,
  User_Name varchar(25) NOT NULL,
  FOREIGN KEY (Method_ID) REFERENCES Payment_Method(Method_ID),
  FOREIGN KEY (User_Name) REFERENCES Users(User_Name)
);
CREATE TABLE Shipment_Address(
  Address_ID int NOT NULL,
  address_name varchar(100) NOT NULL,
  User_Name varchar(25) NOT NULL,
  Is_active NUMBER(1) NOT NULL,
  PRIMARY KEY(User_Name,Address_ID),
  FOREIGN KEY (User_Name) REFERENCES Users(User_Name)
);
CREATE TABLE Orders(
 Order_ID int NOT NULL,
 order_date TIMESTAMP NOT NULL,
 Method_ID NUMBER(3) NOT NULL,
 Address_ID int NOT NULL,
 User_Name varchar(25) NOT NUll,
 PRIMARY KEY (Order_ID),
 FOREIGN KEY (Address_ID,User_Name) REFERENCES Shipment_Address(Address_ID,User_Name),
 FOREIGN KEY (Method_ID) REFERENCES Payment_Method(Method_ID),
 FOREIGN KEY (User_Name) REFERENCES Users(User_Name)
);
CREATE TABLE Contain_Order(
 buying_quantity NUMBER(3) NOT NULL,
 selling_price NUMBER(10,2) NOT NULL,
 Order_ID int NOT NULL,
 Product_ID int NOT NULL,
 FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID),
 FOREIGN KEY (Product_ID) REFERENCES Products(Product_ID)
);
CREATE TABLE Review(
  Review_ID int NOT NULL,
  rating NUMBER(1) NOT NULL,
  comments varchar(225),
  User_Name varchar(25) NOT NULL,
  Product_ID int NOT NULL,
  PRIMARY KEY (Review_ID,User_Name,Product_ID),
  FOREIGN KEY (User_Name) REFERENCES Users(User_Name),
  FOREIGN KEY (Product_ID) REFERENCES Products(Product_ID)
);

CREATE SEQUENCE Admin_ID_Sequence
  START WITH 1
  INCREMENT BY 1;
CREATE OR REPLACE TRIGGER Admin_ID_trigger
  BEFORE INSERT
  ON Admin
  REFERENCING NEW AS NEW
  FOR EACH ROW
  BEGIN
  SELECT Admin_ID_Sequence.nextval INTO :NEW.Admin_ID FROM dual;
  END;
/
CREATE SEQUENCE Store_ID_Sequence
  START WITH 1
  INCREMENT BY 1;
CREATE OR REPLACE TRIGGER Store_ID_trigger
  BEFORE INSERT
  ON Store
  REFERENCING NEW AS NEW
  FOR EACH ROW
  BEGIN
  SELECT Store_ID_Sequence.nextval INTO :NEW.Store_ID FROM dual;
  END;
/
CREATE SEQUENCE Category_ID_Sequence
  START WITH 1
  INCREMENT BY 1;
CREATE OR REPLACE TRIGGER Category_ID_trigger
  BEFORE INSERT
  ON Category
  REFERENCING NEW AS NEW
  FOR EACH ROW
  BEGIN
  SELECT Category_ID_Sequence.nextval INTO :NEW.Category_ID FROM dual;
  END;
/
CREATE SEQUENCE Method_ID_Sequence
  START WITH 1
  INCREMENT BY 1;
CREATE OR REPLACE TRIGGER Method_ID_trigger
  BEFORE INSERT
  ON Payment_Method
  REFERENCING NEW AS NEW
  FOR EACH ROW
  BEGIN
  SELECT Method_ID_Sequence.nextval INTO :NEW.Method_ID FROM dual;
  END;
/
CREATE SEQUENCE Product_ID_Sequence
  START WITH 1
  INCREMENT BY 1;
CREATE OR REPLACE TRIGGER Product_ID_trigger
  BEFORE INSERT
  ON Products
  REFERENCING NEW AS NEW
  FOR EACH ROW
  BEGIN
  SELECT Product_ID_Sequence.nextval INTO :NEW.Product_ID FROM dual;
  END;
/
CREATE SEQUENCE Order_ID_Sequence
  START WITH 1
  INCREMENT BY 1;
CREATE OR REPLACE TRIGGER Order_ID_trigger
  BEFORE INSERT
  ON Orders
  REFERENCING NEW AS NEW
  FOR EACH ROW
  BEGIN
  SELECT Order_ID_Sequence.nextval INTO :NEW.Order_ID FROM dual;
  END;
/

INSERT INTO Users (User_Name,actual_name,email_address,date_of_birth,telephone_number,password)
	Values('User1','Helen','Helen@gmail.com',TO_DATE('1991-11-11','YYYY-MM-DD'),2785953,'HelenIsAtShoppingMall');
INSERT INTO Users (User_Name,actual_name,email_address,date_of_birth,telephone_number,password)
	Values('User2','Shiry','Shiry@gmail.com',TO_DATE('1992-12-12','YYYY-MM-DD'),3739203,'Favorite_MegaDeth_Album');
INSERT INTO Users (User_Name,actual_name,email_address,date_of_birth,telephone_number,password)
	Values('User3','Tempary','Tempary@gmail.com',TO_DATE('1999-5-10','YYYY-MM-DD'),5739208,'TryToListentoDeathMetal');
INSERT INTO Users (User_Name,actual_name,email_address,date_of_birth,telephone_number,password)
	Values('User4','Herry','Herry@gmail.com',TO_DATE('1995-1-1','YYYY-MM-DD'),6724857,'Go_Listen_To_Slayer');
INSERT INTO Users (User_Name,actual_name,email_address,date_of_birth,telephone_number,password)
	Values('User5','UFO','UFO@gmail.com',TO_DATE('1998-8-3','YYYY-MM-DD'),9733871,'WHAT_ARE_YOU_LOOKING_AT?');

INSERT INTO Admin(actual_name, email_address,date_of_birth,telephone_number,password)
	Values('Bruce','Bruce@gmail.com',To_DATE('2000-2-2','YYYY-MM-DD'),6666666,'I_Payed_for_my_telephone');
INSERT INTO Admin(actual_name,email_address,date_of_birth,telephone_number,password)
	Values('Shaley','Shaley@gmail.com',TO_DATE('1993-3-3', 'YYYY-MM-DD'),7236578,'Shaley_Tranet');

INSERT INTO Store (store_description, store_name,Admin_ID)
	Values('This a store that sells many things','Bruce Store',1);
INSERT INTO Store (store_description,store_name,Admin_ID)
	Values('Selling clothes','Shaley Shaley',2);

INSERT INTO Category
	Values(1,'Sports');
INSERT INTO Category
	Values(2,'Clothes');
INSERT INTO Category
	Values(3,'Food');
INSERT INTO Category
	Values(4,'Drinks');
INSERT INTO Category
	Values(5,'Entertainment');
INSERT INTO Category
	Values(6,'Electronics and High-tech');
INSERT INTO Category
	Values(7,'Outdoors');
INSERT INTO Category
	Values(8,'Furniture and Appliance');
INSERT INTO Category
	Values(9,'Books and Media');
INSERT INTO Category
	Values(10,'Office Supplies');

INSERT INTO Products (product_name, product_description, product_brand, stock, out_of_factory_price, dimensions, weight, suitable_age_range, current_selling_price, discount,Store_ID)
	Values('Bruce Shoe', 'This is a shoe', 'NoBrand', 3,10.5,'20x5x5',1,'All',50,1,1);
INSERT INTO Products (product_name, product_description, product_brand, stock, out_of_factory_price, dimensions, weight, suitable_age_range, current_selling_price, discount,Store_ID)
	Values('iPhoneX','It is pretty obvious what this is', 'Apple', 20,1000,'15x5x1',2.1,'All',1200,1,1);
INSERT INTO Products (product_name, product_description, product_brand, stock, out_of_factory_price, dimensions, weight, suitable_age_range, current_selling_price, discount,Store_ID)
	Values('Bruce Beef Jerky','A light snack','NoBrand',100,5,'30x10x1',3.2,'ALL',10,0.8,1);
INSERT INTO Products (product_name,product_description,product_brand,stock,out_of_factory_price,dimensions,weight,suitable_age_range,current_selling_price, discount,Store_ID)
	Values('Bruce Headphone','This is a Headphone','NoBrand',50,50,'20x5x5',0.5,'18~64',70,1,1);
INSERT INTO Products (product_name,product_description,product_brand,stock,out_of_factory_price,dimensions,weight,suitable_age_range,current_selling_price,discount,Store_ID)
	Values('Shaley Clothes','Shaley High Quality Clothes','Shaley',100,9,'80x40x1',0.3,'11~17',11.9,0.7,2);
INSERT INTO Products (product_name, product_description, product_brand, stock, out_of_factory_price, dimensions, weight, suitable_age_range, current_selling_price, discount,Store_ID)
	Values('Nike Shoe','Nike shoe','Nike',20,50,'20x5x5',1,'All',99.99,1,2);
INSERT INTO Products (product_name, product_description, product_brand, stock, out_of_factory_price, dimensions, weight, suitable_age_range, current_selling_price, discount,Store_ID)
	Values('Adidas Shoe','Adidas shoe','Adidas',20,45,'20x5x5',0.9,'All',89.99,1,2);
INSERT INTO Products (product_name, product_description, product_brand, stock, out_of_factory_price, dimensions, weight, suitable_age_range, current_selling_price, discount,Store_ID)
	Values('Calvin Klein Men Boxer','Calvin Klein Men Boxer','Calvin Klein',50,20,'15x20x1',1,'18~64',35.99,1,2);
INSERT INTO Products (product_name,product_description,product_brand,stock,out_of_factory_price,dimensions,weight,suitable_age_range,current_selling_price,discount,Store_ID)
	Values('T-shirt','This is just a normal T-shirt','NoBrand',50,10,'100x40x1',0.4,'18~64',12.99,1,2);
INSERT INTO Products (product_name, product_description, product_brand, stock, out_of_factory_price, dimensions, weight, suitable_age_range, current_selling_price,discount,Store_ID )
	Values('Metallica ...and Justice For All','If you dont buy it, at least go to YouTube and **** listen to this amazing album','Metallica',10,16,'15x7x1',0.4,'All',29.99,1,1);

INSERT INTO Product_Belong_To
	Values(1,1);
INSERT INTO Product_Belong_To
	Values(1,2);
INSERT INTO Product_Belong_To
	Values(1,7);
INSERT INTO Product_Belong_To
	Values(2,5);
INSERT INTO Product_Belong_To
	Values(3,3);
INSERT INTO Product_Belong_To
	Values(4,5);
INSERT INTO Product_Belong_To
	Values(5,5);
INSERT INTO Product_Belong_To
	Values(6,2);
INSERT INTO Product_Belong_To
	Values(6,7);
INSERT INTO Product_Belong_To
	Values(7,1);
INSERT INTO Product_Belong_To
	Values(7,2);
INSERT INTO Product_Belong_To
	Values(7,7);
INSERT INTO Product_Belong_To
	Values(8,1);
INSERT INTO Product_Belong_To
	Values(8,2);
INSERT INTO Product_Belong_To
	Values(8,7);
INSERT INTO Product_Belong_To
	Values(9,2);
INSERT INTO Product_Belong_To
	Values(10,2);

INSERT INTO In_Cart
	Values(1,1,'User1');
INSERT INTO In_Cart
	Values(3,2,'User1');
INSERT INTO In_Cart
	Values(3,10,'User2');
INSERT INTO In_Cart
	Values(2,3,'User2');
INSERT INTO In_Cart
	Values(2,7,'User2');
INSERT INTO In_Cart
	Values(1,5,'User3');
INSERT INTO In_Cart
	Values(2,6,'User3');
INSERT INTO In_Cart
	Values(4,8,'User3');
INSERT INTO In_Cart
	Values(7,4,'User4');
INSERT INTO In_Cart
	Values(5,9,'User4');
INSERT INTO In_Cart
	Values(1,5,'User5');
INSERT INTO In_Cart
	Values(2,10,'User5');

INSERT INTO Payment_Method (method_name)
	Values('Visa Card');
INSERT INTO Payment_Method (method_name)
	Values('Alipay');
INSERT INTO Payment_Method (method_name)
	Values('Amazon');
INSERT INTO Payment_Method (method_name)
	Values('Apple Pay');
INSERT INTO Payment_Method (method_name)
	Values('WeChat Pay');

INSERT INTO Has_Payment
	Values(1,'User1');
INSERT INTO Has_Payment
	Values(2,'User1');
INSERT INTO Has_Payment
	Values(3,'User1');
INSERT INTO Has_Payment
	Values(4,'User1');
INSERT INTO Has_Payment
	Values(5,'User1');
INSERT INTO Has_Payment
	Values(1,'User2');
INSERT INTO Has_Payment
	Values(2,'User2');
INSERT INTO Has_Payment
	Values(3,'User2');
INSERT INTO Has_Payment
	Values(1,'User3');
INSERT INTO Has_Payment
	Values(3,'User3');
INSERT INTO Has_Payment
	Values(4,'User3');
INSERT INTO Has_Payment
	Values(3,'User4');
INSERT INTO Has_Payment
	Values(5,'User4');
INSERT INTO Has_Payment
	Values(1,'User5');
INSERT INTO Has_Payment
	Values(5,'User5');

INSERT INTO Shipment_Address
	Values(1,'1234 Main Street, Anytown, USA','User1',1);
INSERT INTO Shipment_Address
	Values(2,'5678 Elm Avenue, Somewhereville, USA','User1',1);
INSERT INTO Shipment_Address
	Values(3,'9012 Oak Lane, Nowhereville, USA','User1',1);
INSERT INTO Shipment_Address
	Values(4,'3456 Maple Drive, Anytown, USA','User1',1);
INSERT INTO Shipment_Address
	Values(5,'7890 Pine Street, Somewhereville, USA','User1',0);
INSERT INTO Shipment_Address
	Values(1,'1234 Cedar Avenue, Nowhereville, USA','User2',1);
INSERT INTO Shipment_Address
	Values(2,'5678 Elm Lane, Anytown, USA','User2',1);
INSERT INTO Shipment_Address
	Values(3,'9012 Oak Street, Somewhereville, USA','User2',0);
INSERT INTO Shipment_Address
	Values(1,'3456 Pine Avenue, Nowhereville, USA','User3',1);
INSERT INTO Shipment_Address
	Values(1,'7890 Maple Lane, Anytown, USA','User4',1);
INSERT INTO Shipment_Address
	Values(2,'1234 Cedar Drive, Somewhereville, USA','User4',1);
INSERT INTO Shipment_Address
	Values(1,'5678 Elm Street, Nowhereville, USA','User5',1);
INSERT INTO Shipment_Address
	Values(2,'9012 Oak Avenue, Anytown, USA','User5',1);
INSERT INTO Shipment_Address
	Values(3,'3456 Pine Lane, Somewhereville, USA','User5',1);
INSERT INTO Shipment_Address
	Values(4,'7890 Maple Drive, Nowhereville, USA','User5',0);

INSERT INTO Orders(order_date,Method_ID,Address_ID,User_Name)
	Values(TO_TIMESTAMP('2014-07-02 06:14:00', 'YYYY-MM-DD HH24:MI:SS'),1,1,'User1');
INSERT INTO Orders(order_date,Method_ID,Address_ID,User_Name)
	Values(TO_TIMESTAMP('2015-06-12 14:30:45', 'YYYY-MM-DD HH24:MI:SS'),2,4,'User1');
INSERT INTO Orders(order_date,Method_ID,Address_ID,User_Name)
	Values(TO_TIMESTAMP('2018-11-19 07:15:59', 'YYYY-MM-DD HH24:MI:SS'),2,3,'User2');
INSERT INTO Orders(order_date,Method_ID,Address_ID,User_Name)
	Values(TO_TIMESTAMP('2020-07-17 20:22:41', 'YYYY-MM-DD HH24:MI:SS'),1,1,'User3');
INSERT INTO Orders(order_date,Method_ID,Address_ID,User_Name)
	Values(TO_TIMESTAMP('2018-11-19 07:15:59', 'YYYY-MM-DD HH24:MI:SS'),5,2,'User4');
INSERT INTO Orders(order_date,Method_ID,Address_ID,User_Name)
	Values(TO_TIMESTAMP('2019-12-05 12:07:30', 'YYYY-MM-DD HH24:MI:SS'),1,3,'User5');

INSERT INTO Contain_Order
	Values(1,45,1,1);
INSERT INTO Contain_Order
	Values(10,10,1,3);
INSERT INTO Contain_Order
	Values(1,56,2,4);
INSERT INTO Contain_Order
	Values(1,12.99,3,10);
INSERT INTO Contain_Order
	Values(2,89.99,3,8);
INSERT INTO Contain_Order
	Values(2,17,3,6);
INSERT INTO Contain_Order
	Values(1,45,3,1);
INSERT INTO Contain_Order
	Values(1,50,4,1);
INSERT INTO Contain_Order
	Values(1,1200,4,2);
INSERT INTO Contain_Order
	Values(10,35.99,5,9);
INSERT INTO Contain_Order
	Values(2,99.99,6,7);

INSERT INTO Review
	Values(1,5,'A very good product','User1',1);
INSERT INTO Review
	Values(2,4,'Satisfactory','User1',3);
INSERT INTO Review
	Values(1,5,'Love the product','User4',9);
INSERT INTO Review
	Values(1,4,null,'User2',10);
INSERT INTO Review
	Values(2,5,'Good','User2',8);
