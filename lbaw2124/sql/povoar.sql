SET search_path TO lbaw2124;

INSERT INTO users (name,email,password,blocked,account_credit,nif)
VALUES
  ('Freya Cameron','imperdiet.ullamcorper@protonmail.couk','VSO36ISH4DT','false','1111165.03', '218526830'),
  ('Duncan Duffy','nunc.ac.sem@icloud.com','CLG26UUU2HC','true','10.71', '266740286'),
  ('Victoria Adkins','magna.sed@outlook.couk','QKU73QTG3MQ','false','69.76', '247264202'),
  ('Vladimir Clayton','ut.erat.sed@aol.edu','THP07SVZ5YS','false','6.17', '244819858'),
  ('Zia Hodge','nec.cursus@hotmail.org','CHT02BXV8HY','true','59.51', '210693983'),
  ('Clare Adams','porta.elit@hotmail.ca','UXI22QML1EY','false','77.56', '281241465'),
  ('Tallulah Lowe','dictum.cursus@google.com','WBO96EVU4JX','false','54.58', '298860201'),
  ('Sage Weaver','ut@google.couk','TOJ44BYF3KI','true','64.91', '204270499'),
  ('Rina Vance','faucibus@protonmail.couk','ENR38HHS7PS','true','79.13', '214897753'),
  ('Hadley Lewis','nascetur.ridiculus.mus@hotmail.net','YGX73TPM2RL','true','46.28', '274571056');

INSERT INTO administrator (name, email, password)
VALUES
  ('Regan Navarro','ipsum@hotmail.edu','KCN92EMV1HG'),
  ('Donovan Andrews','sed@icloud.com','QNS67VRV1LH');

INSERT INTO warehouse (code, location, postal_code)
VALUES
  (1,'Pernambuco','7179623'),
  (2,'Voronezh Oblast','8823781'),
  (3,'South Island','3673373'),
  (4,'Azad Kashmir','9610741'),
  (5,'West Nusa Tenggara','9793738');

INSERT INTO products (id_product,category,stock,original_price, id_warehouse, description)
VALUES
(1,'Printer',180,'779.98', 1, 'Best printer in the business.'),
  (2,'Printer',73,'0.73', 1, 'Compact printer for travelling.'),
  (3,'Smartphone',60,'394.83',2, 'Best camera smartphone for photographers.'),
  (4,'Tablet',83,'222.55',2, 'Best screen definition in the market.'),
  (5,'Printer',49,'290.22',4, 'Most powerful printer in the store.'),
  (6,'Smartphone',55,'958.29',3, 'Waterproof iphone 11'),
  (7,'Smartphone',88,'960.49',1, 'Motorola X2 Max'),
  (8,'Printer',17,'701.61',2, 'HP smart ink printer'),
  (9,'Printer',113,'16.68',3, 'Epson economic ink printer'),
  (10,'Tablet',76,'494.77',4, '10 inch screen size tablet'),
  (11, 'Tablet',47,'629.11',5, 'Nexus tablet, for book reading'),
  (12,'Smartphone',142,'554.51',1, 'Sony Xperia smartphone'),
  (13,'Smartphone',60,'661.89',4, 'Huawei Xenon technology phone'),
  (14,'Computer',189,'146.34',2, 'Lg 15inch thin notebook'),
  (15,'Printer',147,'907.69',1, 'Epson compact red printer'),
  (16,'Smartphone',125,'999.15',3, 'Samsung Galaxy S10'),
  (17,'Smartphone',132,'443.36',4, 'Oneplus 10 new edition'),
  (18,'Computer',28,'307.89',3, 'Lenovo thinkpad i7 score'),
  (19,'Tablet',124,'713.22',5, 'Ipad Air blue steel edition'),
  (20,'Computer',196,'730.08',4, 'LG notebook');

INSERT INTO attribute (attribute_type, id_product, value)
VALUES
  ('Weight (g)', 3, '205'),
  ('Color', 1, 'black'),
  ('Color printing speed (ppm)', 2, '20'),
  ('Black and white printing speed (ppm)', 1, '10'),
  ('Paper capacity', 2, '225'),
  ('RAM memory (Gb)', 3, '8'),
  ('Internal memory (Gb)', 4, '32'),
  ('Battery capacity (mAh)', 12, '5000'),
  ('Charger type', 6, 'USB-C'),
  ('Processor', 5, 'MT6765 Helio P35'),
  ('Processor speed (GHz)', 16, '2.3'),
  ('Operating System', 12, 'Android 10'),
  ('Phone operator', 9, 'Desbloqueado'),
  ('Screen (in)', 10, '6.5'),
  ('Back camera resolution (Mpx)', 5, '8'),
  ('Front camera resolution (Mpx)', 12, '64 + 8 + 5'),
  ('Graphics card', 4, 'Intel UHD Graphics'),
  ('Screen resolution (px)', 18, '1920 x 1080 (FHD)');


INSERT INTO address (street, country, postal_code)
VALUES
  ('22 Darfield Rd, London', 'United Kingdom', 7830067),
  ('389 Church Rd, Southampton', 'United Kingdom', 7410444),
  ('1455 Rue Sherbrooke Ouest, Montréal', 'Canada', 1030200),
  ('12 Lovegrove Lane , Ajax', 'Canada', 1650025),
  ('5 Beech Avenue, Glasgow', 'United Kingdom', 7400500),
  ('107 Rue Jean-Paul-Lemieux , Cowansville', 'Canada', 1400352),
  ('677 St Annes Rd 4, Winnipeg', 'Canada', 1760220),
  ('12 Albion Drive, Devon', 'United Kingdom', 7550950),
  ('28 Marshall St, Nottingham', 'United Kingdom', 7220165),
  (' 1359 Judd Rd , Brackendale', 'Canada', 1390782);

INSERT INTO stripe_srv (source_id)
VALUES
  (712147396),
  (771161596),
  (964602917),
  (250821269),
  (134411446),
  (505697911);

INSERT INTO orders (track_number, date_of_order, id_user, date_of_departure, date_of_arrival, order_status, total_price, charge_id, id_address, payment)
VALUES
  (1, '2021-3-13',1,'2021-3-14', '2021-3-23','Delivered',2282,456,1,'Paypal'),
  (2, '2021-3-13',2,'2021-03-15', NULL,'In Transit',1249,1234,2, 'Stripe'),
  (3, '2021-3-13',3,NULL, NULL,'Processing',1249,1234,3, 'Stripe'),
  (4, '2021-3-13',4, NULL, NULL,'À espera de pagamento',1418,NULL,4, 'Account_credit'),
  (5, '2021-3-13',5,'2021-3-14', '2021-3-23','Delivered',2294,12345,5,'Account_credit'),
  (6, '2021-3-13',6, NULL, NULL,'Canceled',3770,NULL,6,'Stripe');

INSERT INTO order_info (order_id, id_product, quantity, current_price)
VALUES
  (1, 1, 2, 780),
  (1, 4, 1, 225),
  (1, 9, 1, 497),
  (2, 9, 1 , 17),
  (2, 11, 1, 632),
  (2, 5, 2 , 300),
  (3, 9, 1, 17),
  (3, 14, 2, 200),
  (3, 7, 1, 1000),
  (3, 2, 1, 1),
  (4, 2, 4, 1),
  (4, 6,1, 970),
  (4, 8, 1, 700),
  (4, 18, 2, 310),
  (5, 6, 2, 960),
  (5, 7, 1, 940),
  (5, 15,1 , 910);

INSERT INTO notifications (description, id_user)
VALUES
  ('Your order is À espera de pagamento', 4),
  ('Your order is in transit', 2),
  ('Your order is being processed', 3),
  ('Your order was delivered', 1),
  ('Your order was delivered', 5),
  ('Your order was canceled', 6),
  ('Product in wishlist has changed price', 2),
  ('Product in shopping chart has changed price', 6),
  ('Your review has been deleted by an administrator', 5);

INSERT INTO review (rating, date, reported, id_user, id_product)
VALUES
  (2,'2021-12-04',4,1,1),
  (4,'2022-3-29',1,1,4),
  (2,'2022-12-3',3,1,9),
  (5,'2021-1-19',0,5,6),
  (5,'2021-7-16',4,5,7),
  (5,'2021-6-12',1,5,15);


INSERT INTO flagged_notification (id_notification)
VALUES
  (8);

INSERT INTO order_status_notification (id_notification, id_order)
VALUES
  (1,4),
  (2,2),
  (3,3),
  (4,1),
  (5,5),
  (5,6);

INSERT INTO product_update_notification (id_notification, id_product)
VALUES
  (6,1),
  (7,2);

INSERT INTO shopping_cart_info (id_user, id_product, amount)
VALUES
  (1, 1, 2),
  (2, 2, 4),
  (2, 6, 2),
  (6, 3, 5),
  (4, 3, 1),
  (3, 3, 2),
  (3, 6, 1),
  (9, 10, 3),
  (5, 12, 6),
  (5, 19, 1),
  (5, 11, 2),
  (7, 13, 2),
  (7, 16, 4),
  (7, 15, 1),
  (10, 1, 3);




INSERT INTO wish_list (id_user, id_product)
VALUES
  (2, 2),
  (2, 4),
  (4, 1),
  (5, 3),
  (7, 15),
  (1, 9),
  (1, 12),
  (3, 7),
  (9, 4),
  (8, 5),
  (10, 10);

INSERT INTO user_address (id_address, id_user)
VALUES
  (1, 1),
  (2, 2),
  (3, 3),
  (4, 4),
  (5, 5);

INSERT INTO user_stripe (id_user, id_stripe)
VALUES
  (1, 1),
  (2, 2),
  (3, 3),
  (4, 4),
  (5, 5),
  (6, 6);

INSERT INTO modification (id_product, id_admin, comment)
VALUES
  (1,(SELECT id FROM lbaw2124.administrator ORDER BY RANDOM() LIMIT 1) , 'Updated product price'),
  (2,(SELECT id FROM lbaw2124.administrator ORDER BY RANDOM() LIMIT 1) , 'Updated product photo'),
  (3,(SELECT id FROM lbaw2124.administrator ORDER BY RANDOM() LIMIT 1), 'Updated product description'),
  (4,(SELECT id FROM lbaw2124.administrator ORDER BY RANDOM() LIMIT 1) , 'Updated product stock');

INSERT INTO deleted (id_admin, id_review, comment)
VALUES
  ((SELECT id FROM lbaw2124.administrator ORDER BY RANDOM() LIMIT 1), 1, 'Review contained inappropriate language'),
  ((SELECT id FROM lbaw2124.administrator ORDER BY RANDOM() LIMIT 1), 2, 'Review contained hate speech'),
  ((SELECT id FROM lbaw2124.administrator ORDER BY RANDOM() LIMIT 1), 6, 'Review contained spam');
