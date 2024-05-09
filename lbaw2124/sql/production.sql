DROP SCHEMA IF EXISTS lbaw2124 CASCADE;

CREATE SCHEMA lbaw2124;
SET search_path TO lbaw2124;

-----------------------------------------
-- Types
-----------------------------------------
CREATE TYPE lbaw2124.status AS ENUM (
    'À espera de pagamento',
    'Processing',
    'In Transit',
    'Delivered',
    'Canceled'
);

CREATE TYPE lbaw2124.attribute_types AS ENUM (
    'Weight (g)', 'Color', 'Color printing speed (ppm)', 'Black and white printing speed (ppm)',
    'Paper capacity', 'RAM memory (Gb)', 'Internal memory (Gb)', 'Battery capacity (mAh)',
    'Charger type', 'Processor', 'Processor speed (GHz)','Operating System', 'Phone operator',
    'Screen (in)', 'Back camera resolution (Mpx)', 'Front camera resolution (Mpx)', 'Graphics card',
    'Screen resolution (px)'
);

CREATE TYPE lbaw2124.payment_method AS ENUM ('Stripe', 'Paypal', 'Account_credit');

CrEATE TYPE lbaw2124.categories AS ENUM ('Smartphone', 'Computer', 'Tablet', 'Printer');

-----------------------------------------
-- Tables
-----------------------------------------
--R01
-- Note that a plural 'users' name was adopted because user is a reserved word in PostgreSQL.
CREATE TABLE lbaw2124.users
(
    id             SERIAL PRIMARY KEY,
    name           TEXT    NOT NULL,
    email          TEXT    NOT NULL UNIQUE,
    password       TEXT    NOT NULL,
    nif            INTEGER NOT NULL UNIQUE,
    profile_pic    BYTEA,
    account_credit FLOAT   NOT NULL DEFAULT 0.0,
    blocked        BOOLEAN NOT NULL DEFAULT 'false',
    CONSTRAINT positive_credits CHECK (account_credit >= 0)
);

--R02
CREATE TABLE lbaw2124.administrator
(
    id       SERIAL PRIMARY KEY,
    name     TEXT NOT NULL,
    password TEXT NOT NULL,
    email    TEXT NOT NULL UNIQUE
);

--R03
CREATE TABLE lbaw2124.warehouse
(
    id SERIAL PRIMARY KEY,
    code        INTEGER UNIQUE NOT NULL,
    location    TEXT    NOT NULL,
    postal_code INTEGER NOT NULL --text or int
);

--R04
CREATE TABLE lbaw2124.products
(
    id             SERIAL PRIMARY KEY,
    id_product     INTEGER             NOT NULL UNIQUE,
    photo          BYTEA,
    category       lbaw2124.categories NOT NULL,
    description    TEXT,
    stock          INTEGER             NOT NULL,
    original_price FLOAT               NOT NULL,
    price          FLOAT,
    id_warehouse INTEGER,
    on_sale FLOAT NOT NULL DEFAULT 1.0,
    FOREIGN KEY (id_warehouse) REFERENCES lbaw2124.warehouse (id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT stock_value CHECK (stock >= 0),
    CONSTRAINT price_value CHECK (price > 0),
    CONSTRAINT original_price_value CHECK (original_price > 0)
);

--R05
CREATE TABLE lbaw2124.attribute
(
    attribute_type lbaw2124.attribute_types    NOT NULL,
    id_product     INTEGER NOT NULL REFERENCES products (id) ON UPDATE CASCADE ON DELETE CASCADE,
    value          TEXT    NOT NULL,
    PRIMARY KEY (attribute_type, id_product)
);

--R06
CREATE TABLE lbaw2124.review
(
    id          SERIAL PRIMARY KEY,
    rating      INTEGER NOT NULL,
    description TEXT,
    date        DATE    NOT NULL,
    reported    INTEGER NOT NULL DEFAULT 0,
    id_user     INTEGER NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE SET NULL,
    id_product  INTEGER NOT NULL REFERENCES products (id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT rating_limits CHECK (
                rating >= 1
            AND rating <= 5
        ),
    UNIQUE (id_user, id_product),
    CONSTRAINT reported_positive CHECK (reported >= 0)
);

--R07
CREATE TABLE lbaw2124.address
(
    id          SERIAL PRIMARY KEY,
    street      TEXT    NOT NULL,
    country     TEXT    NOT NULL,
    postal_code INTEGER NOT NULL
);

--R08
CREATE TABLE lbaw2124.stripe_srv
(
    id        SERIAL PRIMARY KEY,
    source_id INTEGER NOT NULL
);

--R09
--order is a reserved sql word so orders was utilized
CREATE TABLE lbaw2124.orders
(
    id SERIAL PRIMARY KEY,
    track_number      INTEGER UNIQUE NOT NULL,
    date_of_order     TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_user           INTEGER        NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE SET NULL,
    date_of_departure TIMESTAMP    ,
    date_of_arrival   TIMESTAMP    ,
    order_status      status         NOT NULL,
    total_price       INTEGER        NOT NULL,
    charge_id         INTEGER,
    id_address        INTEGER         REFERENCES address (id) ON DELETE SET NULL ON UPDATE CASCADE,
    payment           payment_method NOT NULL,
    CONSTRAINT arrival_bigger_than_departure CHECK (date_of_arrival > date_of_departure),
    CONSTRAINT arrival_bigger_than_order CHECK (date_of_arrival > date_of_order),
    CONSTRAINT departure_bigger_than_order CHECK (date_of_departure > date_of_order),
    CONSTRAINT positive_price CHECK (total_price >= 0)
);

--R12
CREATE TABLE lbaw2124.order_info
(
    order_id  INTEGER NOT NULL REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_product    INTEGER NOT NULL REFERENCES products (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    quantity      INTEGER NOT NULL,
    current_price INTEGER NOT NULL,
    CONSTRAINT positive_quantity CHECK (quantity > 0),
    CONSTRAINT positive_price CHECK (current_price > 0),
    PRIMARY KEY (order_id, id_product)
);

--R11
--notification is a reserved sql word so notifications was utilized
CREATE TABLE lbaw2124.notifications
(
    id          SERIAL PRIMARY KEY,
    description TEXT    NOT NULL,
    date        DATE    NOT NULL DEFAULT CURRENT_DATE,
        CONSTRAINT date_check CHECK (date >= CURRENT_DATE),
    url         TEXT    NOT NULL DEFAULT 'url',
    id_user     INTEGER NOT NULL REFERENCES users (id) ON DELETE
        SET
        NULL ON UPDATE CASCADE
);

--R12
CREATE TABLE lbaw2124.flagged_notification
(
    id_notification INTEGER PRIMARY KEY REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE RESTRICT
);

--R13
CREATE TABLE lbaw2124.order_status_notification
(
    id_notification INTEGER REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    id_order        INTEGER REFERENCES orders (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    PRIMARY KEY (id_notification, id_order)
);

--R14
CREATE TABLE lbaw2124.product_update_notification
(
    id_notification INTEGER REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    id_product      INTEGER REFERENCES products (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    PRIMARY KEY (id_notification, id_product)
);

--R15
CREATE TABLE lbaw2124.shopping_cart_info
(
    id_user    INTEGER REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_product INTEGER REFERENCES products (id) ON UPDATE CASCADE ON DELETE CASCADE,
    amount     INTEGER NOT NULL,
    PRIMARY KEY (id_user, id_product),
    CONSTRAINT positive_amount CHECK (amount > 0)
);

--R16
CREATE TABLE lbaw2124.wish_list
(
    id_user    INTEGER REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_product INTEGER REFERENCES products (id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_product)
);

--R17
CREATE TABLE lbaw2124.user_address
(
    id_address INTEGER REFERENCES address (id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_user    INTEGER REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_address)
);

--R18
CREATE TABLE lbaw2124.user_stripe
(
    id_user   INTEGER REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_stripe INTEGER REFERENCES lbaw2124.stripe_srv (id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (id_user, id_stripe)
);

--R19
CREATE TABLE lbaw2124.modification
(
    id_product INTEGER REFERENCES products (id) ON UPDATE CASCADE ON DELETE CASCADE,
    id_admin   INTEGER   REFERENCES administrator (id) ON UPDATE CASCADE ON DELETE
        SET
        NULL,
    date       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comment    TEXT,
    PRIMARY KEY (id_admin, id_product, date)
);

--R20
CREATE TABLE lbaw2124.deleted
(
    id_admin  INTEGER   REFERENCES administrator (id) ON UPDATE CASCADE ON DELETE
        SET
        NULL,
    id_review INTEGER REFERENCES review (id) ON UPDATE CASCADE ON DELETE CASCADE,
    date      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comment   TEXT,
    PRIMARY KEY (id_admin, id_review, date)
);

--Indexes

CREATE INDEX category_product_index ON lbaw2124.products USING btree (category);
CLUSTER lbaw2124.products USING category_product_index;

CREATE INDEX rating_review_index ON lbaw2124.review USING btree (rating);
CLUSTER lbaw2124.review USING rating_review_index;

CREATE INDEX id_user_index ON lbaw2124.orders USING hash (id_user);

CREATE INDEX notif_user_index ON notifications USING btree (id_user);

CREATE INDEX order_status_index ON lbaw2124.orders USING btree (order_status);



--Trigger 1
CREATE FUNCTION block_user() RETURNS TRIGGER AS $check_block$
begin
    IF (SELECT COUNT (*) FROM (SELECT * FROM lbaw2124.deleted, lbaw2124.review WHERE review.id = deleted.id_review AND review.id_user = (SELECT id_user FROM lbaw2124.review WHERE review.id = NEW.id_review)) AS ta) >= 3 THEN
        UPDATE lbaw2124.users
        SET blocked = true
        WHERE users.id = (SELECT id_user FROM lbaw2124.review WHERE review.id = NEW.id_review);
    END IF;
    RETURN old;
end
$check_block$
    LANGUAGE plpgsql;

CREATE
   TRIGGER check_block
   AFTER
       INSERT
   ON lbaw2124.deleted
   FOR EACH ROW
EXECUTE PROCEDURE block_user();





--Trigger 2
CREATE FUNCTION alert_wrong_nif() RETURNS TRIGGER AS $invalid_nif$
DECLARE
nif_s TEXT := CAST (NEW.NIF AS int8);
control_1 INTEGER;
control_2 INTEGER;
control_3 INTEGER;
control_4 INTEGER;
control_5 INTEGER;
control_6 INTEGER;
control_7 INTEGER;
control_8 INTEGER;
control_9 INTEGER;
control_cal INTEGER;
control_div INTEGER;
control_mod INTEGER;
begin
	IF ((NEW.nif > 999999999 ) OR (NEW.nif < 100000000)) THEN
    	RAISE NOTICE 'O nif é Invalido';
	ELSE
    	control_1 := 9 * CAST(SUBSTRING (nif_s,1,1) AS INTEGER);
    	control_2 := 8 * CAST(SUBSTRING (nif_s,2,1) AS INTEGER);
    	control_3 := 7 * CAST(SUBSTRING (nif_s,3,1) AS INTEGER);
    	control_4 := 6 * CAST(SUBSTRING (nif_s,4,1) AS INTEGER);
    	control_5 := 5 * CAST(SUBSTRING (nif_s,5,1) AS INTEGER);
    	control_6 := 4 * CAST(SUBSTRING (nif_s,6,1) AS INTEGER);
 	    control_7 := 3 * CAST(SUBSTRING (nif_s,7,1) AS INTEGER);
    	control_8 := 2 * CAST(SUBSTRING (nif_s,8,1) AS INTEGER);
    	control_9 := 1 * CAST(SUBSTRING (nif_s,9,1) AS INTEGER);
    	control_cal := control_1 +  control_2 + control_3 + control_4 + control_5 + control_6 + control_7 + control_8;
    	control_div := control_cal/11;
    	control_mod := control_cal - control_div * 11;
    	IF (control_mod < 2 AND control_9 != 0) OR (control_mod >= 2 AND control_9 != (11 - control_mod)) THEN
        	RAISE NOTICE 'O nif é Invalido';
    	ELSE
        	RETURN NEW;
    	END IF;
	END IF;
	RETURN NULL;
end
$invalid_nif$ LANGUAGE plpgsql;

CREATE
   TRIGGER invalid_nif
   BEFORE
   	INSERT
   ON lbaw2124.users
   FOR EACH ROW
  	EXECUTE PROCEDURE alert_wrong_nif();




--Trigger 3
CREATE FUNCTION assign_price() RETURNS TRIGGER AS $check_price$
begin
    IF (NEW.price IS NULL) THEN
        UPDATE lbaw2124.products
        SET price = NEW.original_price
        WHERE products.id = NEW.id;
    END IF;
    RETURN old;
end
$check_price$
    LANGUAGE plpgsql;

CREATE
    TRIGGER check_price
    AFTER
        INSERT
    ON lbaw2124.products
    FOR EACH ROW
        EXECUTE PROCEDURE assign_price();


--Trigger 4
CREATE FUNCTION alert_wrong_postal_code() RETURNS TRIGGER AS $invalid_postal_code$
DECLARE
begin
	IF ((NEW.postal_code > 9999999 ) OR (NEW.postal_code < 1000000)) THEN
    	RAISE NOTICE 'O código postal é Invalido';
    ELSE
    	RETURN NEW;
	END IF;
	RETURN NULL;
end
$invalid_postal_code$ LANGUAGE plpgsql;

CREATE
   TRIGGER invalid_postal_code
   BEFORE
   	INSERT
   ON lbaw2124.address
   FOR EACH ROW
  	EXECUTE PROCEDURE alert_wrong_postal_code();


CREATE
   TRIGGER invalid_postal_code_warehouse
   BEFORE
   	INSERT
   ON lbaw2124.warehouse
   FOR EACH ROW
  	EXECUTE PROCEDURE alert_wrong_postal_code();

--Trigger 5
CREATE FUNCTION update_stock() RETURNS TRIGGER AS
$$
begin
   UPDATE products
   SET stock = 0, on_sale = false
   WHERE products.id_warehouse IS NULL;
   RETURN NULL;
end
$$ LANGUAGE plpgsql;

CREATE
   TRIGGER product_stock
   AFTER DELETE ON warehouse
   FOR EACH ROW
EXECUTE PROCEDURE update_stock();

--Trigger 6
CREATE FUNCTION order_address_check() RETURNS TRIGGER AS $$
   DECLARE st status;
begin
   SELECT orders.order_status INTO STRICT st FROM orders WHERE orders.track_number = new.track_number;
   IF st = 'In Transit'
       THEN RAISE EXCEPTION 'Your order is on route, you cannot change your address';
   END IF;
   IF st = 'Canceled'
       THEN RAISE EXCEPTION 'Your order was cancelled, you cannot change your address';
   END IF;
   IF st = 'Delivered' AND new.id_address IS NOT NULL
       THEN RAISE EXCEPTION 'Your order was delivered, you cannot change your address';
   END IF;
   RETURN NEW;
end
$$
   LANGUAGE plpgsql;

CREATE
   TRIGGER order_check
   BEFORE UPDATE OF id_address ON orders
   FOR EACH ROW
EXECUTE PROCEDURE order_address_check();

--Trigger 7
CREATE FUNCTION  order_check() RETURNS TRIGGER AS $$
begin
   IF 'À espera de pagamento' NOT IN (SELECT order_status FROM orders WHERE orders.track_number = new.track_number)
       THEN RAISE EXCEPTION 'Your order is not in the awaiting for payment stage, you cannot change your payment method';
   END IF;
   RETURN NEW;
end
$$
   LANGUAGE plpgsql;

CREATE
   TRIGGER order_check_payment
   BEFORE UPDATE OF payment, charge_id ON lbaw2124.orders
   FOR EACH ROW
EXECUTE PROCEDURE order_check();

--Trigger 8
CREATE FUNCTION  order_erase_address_check() RETURNS TRIGGER AS $$
begin
   IF 'Delivered' NOT IN (SELECT order_status FROM orders WHERE orders.id_address = old.id)
       THEN RAISE EXCEPTION 'Your order was not yet delivered, you cannot erase your its address';
   END IF;
   RETURN OLD;
end
$$
   LANGUAGE plpgsql;

CREATE
   TRIGGER order_check_address
   BEFORE DELETE ON lbaw2124.address
   FOR EACH ROW
EXECUTE PROCEDURE order_erase_address_check();

--Trigger 9
CREATE FUNCTION order_status_check() RETURNS TRIGGER AS $$
   DECLARE st status;
begin
   IF 'Delivered' IN (SELECT order_status FROM orders WHERE orders.id = new.id)
        THEN UPDATE lbaw2124.orders
        SET date_of_arrival = NOW()
        WHERE orders.id = NEW.id;
   END IF;
   RETURN NEW;
end
$$
   LANGUAGE plpgsql;

CREATE
   TRIGGER order_date_of_arrival_check
   AFTER UPDATE OF order_status ON orders
   FOR EACH ROW
EXECUTE PROCEDURE order_status_check();


--Trigger 10
CREATE FUNCTION stock_check() RETURNS TRIGGER AS $no_stock$
begin
    IF ((SELECT stock FROM products WHERE products.id = NEW.id_product) < NEW.amount) THEN
        RAISE NOTICE 'Insufficient amount of the product in stock';
    ELSE
        RETURN NEW;
    END IF;
    RETURN NULL;
end
$no_stock$
    LANGUAGE plpgsql;

CREATE
   TRIGGER no_stock
   BEFORE
       INSERT
   ON lbaw2124.shopping_cart_info
   FOR EACH ROW
EXECUTE PROCEDURE stock_check();

--Trigger 11
CREATE FUNCTION review_check() RETURNS TRIGGER AS $invalid_review$
begin
    IF (NEW.id_product NOT IN (SELECT id_product FROM orders,order_info WHERE orders.id = order_info.order_id AND orders.order_status = 'Delivered' AND orders.id_user = NEW.id_user)) THEN
        RAISE NOTICE 'cannot review products "%" havent bought "%"', New.id_user, New.id_product;
    ELSE
        RETURN NEW;
    END IF;
    RETURN NULL;
end
$invalid_review$
    LANGUAGE plpgsql;

CREATE
   TRIGGER invalid_review
   BEFORE
       INSERT
   ON lbaw2124.review
   FOR EACH ROW
EXECUTE PROCEDURE review_check();





-- FTS Indexes
ALTER TABLE products
ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION product_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.category::Text), 'A') ||
         setweight(to_tsvector('english', NEW.description), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.description <> OLD.description OR NEW.category <> OLD.category) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('english',  NEW.category::Text), 'A') ||
               setweight(to_tsvector('english', NEW.description), 'B')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER product_update
 BEFORE INSERT OR UPDATE ON products
 FOR EACH ROW
 EXECUTE PROCEDURE product_search_update();


CREATE INDEX search_idx ON products USING gist (tsvectors);


ALTER TABLE attribute
ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION attribute_search_update() RETURNS TRIGGER AS $$
BEGIN
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.attribute_type::text), 'A') ||
         setweight(to_tsvector('english', NEW.value), 'B')
        );
 END IF;
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.attribute_type <> OLD.attribute_type OR NEW.value <> OLD.value) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('english', NEW.attribute_type::text), 'A') ||
             setweight(to_tsvector('english', NEW.value), 'B')
           );
         END IF;
 END IF;
 RETURN NEW;
END $$
LANGUAGE plpgsql;


CREATE TRIGGER attribute_search_update
 BEFORE INSERT OR UPDATE ON attribute
 FOR EACH ROW
 EXECUTE PROCEDURE attribute_search_update();

CREATE INDEX search_attribute_idx ON attribute USING gist (tsvectors);



CREATE OR REPLACE PROCEDURE lbaw2124.remove_order_transaction(rejected_id INTEGER)
    LANGUAGE plpgsql AS
$$
DECLARE
    product_info RECORD;
    id_notif     INTEGER;
BEGIN
    COMMIT;
    SET TRANSACTION ISOLATION LEVEL REPEATABLE READ ;
    IF (rejected_id NOT IN
        (SELECT id FROM orders WHERE order_status = 'Canceled' OR order_status = 'Delivered'))
    THEN
        FOR product_info IN (
            SELECT id_product, quantity FROM order_info WHERE order_info.order_id = rejected_id
        )
            LOOP
                UPDATE products SET stock = stock + product_info.quantity WHERE id = product_info.id_product;
            END LOOP;
        UPDATE orders SET order_status = 'Canceled' WHERE orders.id = rejected_id;



        INSERT INTO notifications (description, date, url, id_user)
        VALUES ('You did not make the 2 day payment deadline',
                NOW(), 'url', (SELECT id_user FROM orders WHERE id = rejected_id))
        RETURNING id INTO id_notif;

        INSERT
        INTO order_status_notification
        VALUES (id_notif, rejected_id);
        COMMIT;
    END IF;
END;
$$;


CREATE OR REPLACE PROCEDURE lbaw2124.place_order_transaction(user_id INTEGER, adress_id INTEGER, payment_m payment_method)
    LANGUAGE plpgsql AS
$$

DECLARE
    product_info RECORD;
    p            INTEGER;
    id_order     INTEGER;
    id_notif INTEGER;
BEGIN
    COMMIT;
    SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;
    IF (EXiSTS ( SELECT id_product, amount FROM shopping_cart_info WHERE shopping_cart_info.id_user = user_id)) THEN
        INSERT INTO lbaw2124.orders (track_number, date_of_order, total_price, order_status, id_address, id_user,
                                     payment)
        VALUES ((SELECT MAX(track_number) FROM orders) + 1, DEFAULT, 0, 'À espera de pagamento', adress_id, user_id, payment_m) RETURNING id INTO id_order;

        -- Select all products that constitute the order and their quantities
        FOR product_info IN (
            SELECT id_product, amount FROM shopping_cart_info WHERE shopping_cart_info.id_user = user_id
        )
            LOOP
                --Loop through them and update the stock of the respective product to current_stock += order_quantity
                UPDATE products SET stock = products.stock - product_info.amount WHERE id = product_info.id_product;
                SELECT products.price
                INTO p
                FROM shopping_cart_info,
                     products
                WHERE shopping_cart_info.id_product = products.id
                  AND products.id = product_info.id_product;
                INSERT INTO lbaw2124.order_info Values (id_order, product_info.id_product, product_info.amount, p);
                UPDATE orders
                SET total_price = orders.total_price + p * product_info.amount
                WHERE orders.id = id_order;
                DELETE
                FROM lbaw2124.shopping_cart_info
                WHERE shopping_cart_info.id_user = user_id
                  AND shopping_cart_info.id_product = product_info.id_product;
            END LOOP;


            INSERT INTO notifications (description, date, url, id_user) VALUES ('Your order is À espera de pagamento.', NOW(), 'url', (SELECT id_user FROM orders WHERE orders.id = id_order)) RETURNING id INTO id_notif;
            INSERT INTO order_status_notification VALUES (id_notif, id_order);

        IF (payment_m = 'Account_credit' AND (SELECT account_credit FROM users WHERE users.id = user_id) >= (SELECT total_price FROM orders WHERE orders.id = id_order ) ) THEN
             UPDATE orders
                SET order_status = 'Processing'
                WHERE orders.id = id_order;
             UPDATE users
                 SET account_credit = account_credit - (SELECT total_price FROM orders WHERE orders.id = id_order )
                 WHERE users.id = user_id;
            INSERT INTO notifications (description, date, url, id_user) VALUES ('Your order is being processed.', NOW(), 'url', (SELECT id_user FROM orders WHERE orders.id = id_order)) RETURNING id INTO id_notif;
            INSERT INTO order_status_notification VALUES (id_notif, id_order);
        END IF;
    END IF;
    COMMIT;
END
$$;


CREATE OR REPLACE PROCEDURE update_status(order_id INTEGER, new_status status)
   LANGUAGE plpgsql AS $$
   DECLARE
   notif_description TEXT;
   st status;
   id_notif     INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

   SELECT orders.order_status INTO STRICT st FROM orders WHERE orders.id = order_id;

   -- Set notification description according to order status
   IF (st = 'Delivered' OR st = 'Canceled') THEN
       RAISE NOTICE 'You cannot change the status of an already delivered or cancelled order';

   ELSEIF (new_status = 'Processing')
       THEN  notif_description := 'Your order is being processed.';
       UPDATE orders
       SET order_status = new_status WHERE id = order_id;
   ELSEIF (new_status = 'In Transit')
       THEN  notif_description := 'Your order is on route.';
       UPDATE orders
       SET order_status = new_status, date_of_departure = CURRENT_TIMESTAMP WHERE id = order_id;
   ELSEIF new_status = 'Canceled'
       THEN  notif_description := 'Your order was cancelled.';
       UPDATE orders
       SET order_status = new_status WHERE id = order_id;
   ELSEIF new_status = 'Delivered'
       THEN notif_description := 'Your order was delivered.';
       UPDATE orders
       SET order_status = new_status, date_of_arrival = CURRENT_TIMESTAMP WHERE id = order_id;
   END IF;

   -- Send notification to user about status update
   IF (notif_description IS NOT NULL) THEN
   INSERT INTO notifications (description, date, url, id_user) VALUES (notif_description, NOW(), 'url', (SELECT id_user FROM orders WHERE orders.id = order_id)) RETURNING id INTO id_notif;

   INSERT INTO order_status_notification VALUES (id_notif, order_id);
   END IF;

   COMMIT;
END
$$;

CREATE OR REPLACE PROCEDURE product_on_sale(discount FLOAT, id_prod INTEGER)
   LANGUAGE plpgsql AS $$
   DECLARE
   user_wishlist RECORD;
   user_shopping_cart RECORD;
   id_notif INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

   UPDATE products
   SET on_sale = discount
   WHERE products.id = id_prod;

     FOR user_wishlist IN (
            SELECT id_user FROM wish_list WHERE wish_list.id_product = id_prod
        )
            LOOP
                INSERT INTO notifications (description, date, url, id_user) VALUES ('A product in your wishlist is now discounted', NOW(), 'url', user_wishlist.id_user) RETURNING id INTO id_notif;
                INSERT INTO product_update_notification VALUES (id_notif, id_prod);
            END LOOP;

        FOR user_shopping_cart IN (
            SELECT id_user FROM shopping_cart_info WHERE shopping_cart_info.id_product = id_prod
        )
            LOOP
                INSERT INTO notifications (description, date, url, id_user) VALUES ('A product in your shopping cart is now discounted', NOW(), 'url', user_shopping_cart.id_user) RETURNING id INTO id_notif;
                INSERT INTO product_update_notification VALUES (id_notif, id_prod);
            END LOOP;

   COMMIT;
END
$$;

CREATE OR REPLACE PROCEDURE product_on_stock(new_stock INTEGER, id_prod INTEGER)
   LANGUAGE plpgsql AS $$
   DECLARE
   user_wishlist RECORD;
   user_shopping_cart RECORD;
   id_notif INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

   IF ((SELECT stock FROM products WHERE products.id = id_prod) = 0) THEN
       UPDATE products
       SET stock = new_stock
       WHERE products.id = id_prod;

         FOR user_wishlist IN (
                SELECT id_user FROM wish_list WHERE wish_list.id_product = id_prod
            )
                LOOP
                    INSERT INTO notifications (description, date, url, id_user) VALUES ('A product in your wishlist is now in stock', NOW(), 'url', user_wishlist.id_user) RETURNING id INTO id_notif;
                    INSERT INTO product_update_notification VALUES (id_notif, id_prod);
                END LOOP;

            FOR user_shopping_cart IN (
                SELECT id_user FROM shopping_cart_info WHERE shopping_cart_info.id_product = id_prod
            )
                LOOP
                    INSERT INTO notifications (description, date, url, id_user) VALUES ('A product in your shopping cart is now in stock', NOW(), 'url', user_shopping_cart.id_user) RETURNING id INTO id_notif;
                    INSERT INTO product_update_notification VALUES (id_notif, id_prod);
                END LOOP;
   ELSE
        UPDATE products
        SET stock = stock + new_stock
        WHERE products.id = id_prod;
        END IF;
   COMMIT;
END
$$;

