drop schema if exists lbaw2124 cascade;
SET search_path TO lbaw2124;
create schema lbaw2124;

DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS cards CASCADE;
DROP TABLE IF EXISTS items CASCADE;

CREATE TYPE lbaw2124.status AS ENUM (
    'À espera de pagamento',
    'Em processamento',
    'Em trânsito',
    'Entregue',
    'Cancelada'
    );

CREATE TYPE lbaw2124.attribute_types AS ENUM (
    'Peso (g)', 'Cor', 'Velocidade de impressão a cores (ppm)', 'Velocidade de impressão a preto e branco (ppm)',
    'Capacidade de Entrada', 'Memória RAM (Gb)', 'Memória interna (Gb)', 'Capacidade da bateria (mAh)',
    'Tipo de carregador', 'Processador', 'Velocidade do processador (GHz)','Sistema Operativo', 'Operadora de telefone',
    'Ecrã (in)', 'Resolução da câmera traseira (Mpx)', 'Resolução da câmera frontal (Mpx)', 'Placa gráfica',
    'Resolução do ecrã (px)'
    );

CREATE TYPE lbaw2124.payment_method AS ENUM ('Stripe', 'Paypal', 'Account_credit');

CrEATE TYPE lbaw2124.categories AS ENUM ('Telemóvel', 'Computador', 'Tablet', 'Impressora');

CREATE TABLE users
(
    id             SERIAL PRIMARY KEY,
    name           TEXT    NOT NULL,
    email          TEXT    NOT NULL UNIQUE,
    password       TEXT    NOT NULL,
    nif            INTEGER UNIQUE,
    profile_pic    TEXT DEFAULT 'default_ppic.jpg',
    account_credit FLOAT   NOT NULL DEFAULT 0.0,
    blocked        BOOLEAN NOT NULL DEFAULT 'false',
    remember_token VARCHAR,
    CONSTRAINT positive_credits CHECK (account_credit >= 0)
);

INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'user@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
  null,
  DEFAULT,
  DEFAULT,
  DEFAULT,
  null
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO users VALUES (
  0,
  'Deleted User',
  'deleted_user@example.com',
  'none',
  null,
  DEFAULT,
  DEFAULT,
  DEFAULT,
  null
);

CREATE TABLE lbaw2124.admins
(
    id       SERIAL PRIMARY KEY,
    name     TEXT NOT NULL,
    password TEXT NOT NULL,
    profile_pic   TEXT NOT NULL DEFAULT 'default_ppic.jpg',
    email    TEXT NOT NULL UNIQUE
);

INSERT INTO admins VALUES (
  DEFAULT,
  'John Doe',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
  DEFAULT,
  'admin@example.com'
); -- Password is 1234. Generated using Hash::make('1234')

--R03
CREATE TABLE lbaw2124.warehouse
(
    id          SERIAL PRIMARY KEY,
    code        INTEGER UNIQUE NOT NULL,
    location    TEXT           NOT NULL,
    postal_code INTEGER        NOT NULL --text or int
);

--R04
CREATE TABLE lbaw2124.products
(
    id             SERIAL PRIMARY KEY,
    id_product     TEXT             NOT NULL UNIQUE DEFAULT md5('p'||now()::text||random()::text),
    name           TEXT NOT NULL,
    photo          TEXT DEFAULT 'shoppingcart.png',
    category       lbaw2124.categories NOT NULL,
    description    TEXT,
    stock          INTEGER             NOT NULL,
    original_price FLOAT               NOT NULL,
    price          FLOAT,
    id_warehouse   INTEGER,
    on_sale        BOOL               NOT NULL DEFAULT false,
    FOREIGN KEY (id_warehouse) REFERENCES lbaw2124.warehouse (id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT stock_value CHECK (stock >= 0),
    CONSTRAINT price_value CHECK (price > 0),
    CONSTRAINT original_price_value CHECK (original_price > 0)
);

--R05
CREATE TABLE lbaw2124.attributes
(
    attribute_type lbaw2124.attribute_types NOT NULL,
    id_product     INTEGER                  NOT NULL REFERENCES products (id) ON UPDATE CASCADE ON DELETE CASCADE,
    value          TEXT                     NOT NULL,
    PRIMARY KEY (attribute_type, id_product)
);

--R06
CREATE TABLE lbaw2124.review
(
    id          SERIAL PRIMARY KEY,
    rating      INTEGER NOT NULL,
    description TEXT,
    date        DATE    NOT NULL,
    hidden      bool NOT NULL DEFAULT false,
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
CREATE TABLE lbaw2124.addresses
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
    id                SERIAL PRIMARY KEY,
    track_number      TEXT UNIQUE NOT NULL DEFAULT md5('o'||now()::text||random()::text),
    date_of_order     TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_user           INTEGER        NOT NULL REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE,
    date_of_departure TIMESTAMP,
    date_of_arrival   TIMESTAMP,
    order_status      status         NOT NULL,
    total_price       FLOAT        NOT NULL,
    charge_id         INTEGER,
    id_address        INTEGER         REFERENCES addresses (id) ON DELETE SET NULL ON UPDATE CASCADE,
    payment           payment_method NOT NULL,
    CONSTRAINT arrival_bigger_than_departure CHECK (date_of_arrival > date_of_departure),
    CONSTRAINT arrival_bigger_than_order CHECK (date_of_arrival > date_of_order),
    CONSTRAINT departure_bigger_than_order CHECK (date_of_departure > date_of_order),
    CONSTRAINT positive_price CHECK (total_price >= 0)
);

--R12
CREATE TABLE lbaw2124.order_info
(
    order_id      INTEGER NOT NULL REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE,
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
    id_user     INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
);

--R12
CREATE TABLE lbaw2124.flagged_notification
(
    id_notification INTEGER PRIMARY KEY REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE
);

--R13
CREATE TABLE lbaw2124.order_status_notification
(
    id_notification INTEGER REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE ,
    id_order        INTEGER REFERENCES orders (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    PRIMARY KEY (id_notification, id_order)
);

--R14
CREATE TABLE lbaw2124.product_update_notification
(
    id_notification INTEGER REFERENCES notifications (id) ON UPDATE CASCADE ON DELETE CASCADE,
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
CREATE TABLE lbaw2124.user_addresses
(
    id_address INTEGER REFERENCES addresses (id) ON UPDATE CASCADE ON DELETE CASCADE,
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

CREATE TABLE lbaw2124.admin_dashboard(
    id SERIAL PRIMARY KEY,
    date       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comment TEXT
);

CREATE TABLE lbaw2124.dashboard_review(
    id SERIAL PRIMARY KEY REFERENCES admin_dashboard (id) ON UPDATE CASCADE ON DELETE SET NULL,
    id_review   INTEGER   REFERENCES review (id) ON UPDATE CASCADE ON DELETE SET NULL,
    id_user INTEGER REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE lbaw2124.dashboard_user(
    id SERIAL PRIMARY KEY REFERENCES admin_dashboard (id) ON UPDATE CASCADE ON DELETE SET NULL,
    id_user   INTEGER   REFERENCES users (id) ON UPDATE CASCADE ON DELETE
        SET
        NULL
);


CREATE TABLE lbaw2124.admin_history(
    id SERIAL PRIMARY KEY,
    id_admin   INTEGER   REFERENCES admins (id) ON UPDATE CASCADE ON DELETE
        SET
        NULL,
    date       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comment    TEXT
);

--R19
CREATE TABLE lbaw2124.modification
(
    id INTEGER PRIMARY KEY REFERENCES admin_history (id) ON UPDATE CASCADE ON DELETE SET NULL ,
    id_product INTEGER REFERENCES products (id) ON UPDATE CASCADE ON DELETE CASCADE
);

--R20
CREATE TABLE lbaw2124.hidden_review
(
    id INTEGER PRIMARY KEY REFERENCES admin_history (id) ON UPDATE CASCADE ON DELETE SET NULL ,
    id_review INTEGER REFERENCES review (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE lbaw2124.blocked
(
    id INTEGER PRIMARY KEY REFERENCES admin_history (id) ON UPDATE CASCADE ON DELETE SET NULL ,
    email TEXT NOT NULL
);

CREATE TABLE lbaw2124.deleted
(
    id INTEGER PRIMARY KEY REFERENCES admin_history (id) ON UPDATE CASCADE ON DELETE SET NULL ,
    email TEXT NOT NULL
);

CREATE TABLE lbaw2124.order_update
(
    id INTEGER PRIMARY KEY REFERENCES admin_history (id) ON UPDATE CASCADE ON DELETE SET NULL ,
    id_order INTEGER REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE lbaw2124.password_resets
(
    id SERIAL PRIMARY KEY,
    email TEXT UNIQUE NOT NULL,
    token TEXT UNIQUE NOT NULL,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

);

CREATE FUNCTION stop_repeated_email() RETURNS TRIGGER AS $$
begin
    IF EXISTS(SELECT email FROM users WHERE NEW.email = email) THEN RAISE EXCEPTION 'Este email já existe.';
    ELSEIF (EXISTS(SELECT email FROM admins WHERE NEW.email = email)) THEN RAISE EXCEPTION 'Este email já existe.';
    ELSE
        RETURN new;
    END IF;

end
$$
    LANGUAGE plpgsql;


CREATE
    TRIGGER repeated_email_1
    BEFORE
        INSERT
    ON lbaw2124.admins
    FOR EACH ROW
EXECUTE PROCEDURE stop_repeated_email();

CREATE
    TRIGGER repeated_email_1
    BEFORE
        INSERT
    ON lbaw2124.users
    FOR EACH ROW
EXECUTE PROCEDURE stop_repeated_email();


--Indexes

CREATE INDEX category_product_index ON lbaw2124.products USING hash (category);


CREATE INDEX rating_review_index ON lbaw2124.review USING btree (rating);
CLUSTER lbaw2124.review USING rating_review_index;

CREATE INDEX id_user_index ON lbaw2124.orders USING btree (id_user);
CLUSTER lbaw2124.orders USING id_user_index;

CREATE INDEX notif_user_index ON notifications USING hash (id_user);

CREATE INDEX order_status_index ON lbaw2124.orders USING hash (order_status);

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
    	RAISE EXCEPTION 'O NIF inserido é Invalido.';
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
        	RAISE EXCEPTION 'O NIF inserido é Invalido.';
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

CREATE
   TRIGGER invalid_nif2
   BEFORE
    UPDATE
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
    	RAISE EXCEPTION 'O código postal inserido é invalido.';
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
   ON lbaw2124.addresses
   FOR EACH ROW
  	EXECUTE PROCEDURE alert_wrong_postal_code();

CREATE
   TRIGGER invalid_postal_code_update
   BEFORE
   	UPDATE
   ON lbaw2124.addresses
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
CREATE FUNCTION order_addresses_check() RETURNS TRIGGER AS $$
   DECLARE st status;
begin
   SELECT orders.order_status INTO STRICT st FROM orders WHERE orders.id = new.id;
   IF st = 'Em trânsito'
       THEN RAISE EXCEPTION 'A sua encomenda já está a caminho, não podes mudar a morada inserida.';
   END IF;
   IF st = 'Cancelada'
       THEN RAISE EXCEPTION 'A tua encomenda foi cancelada, não podes mudar a morada.';
   END IF;
   IF st = 'Entregue' AND new.id_address IS NOT NULL
       THEN RAISE EXCEPTION 'A tua encomenda já foi entregue, não podes mudar a morada.';
   END IF;
   RETURN NEW;
end
$$
   LANGUAGE plpgsql;

CREATE
   TRIGGER order_check
   BEFORE UPDATE OF id_address ON orders
   FOR EACH ROW
EXECUTE PROCEDURE order_addresses_check();

--Trigger 7
CREATE FUNCTION order_check() RETURNS TRIGGER AS $$
begin
   IF 'À espera de pagamento' NOT IN (SELECT order_status FROM orders WHERE orders.id = new.id)
       THEN RAISE EXCEPTION 'A tua encomenda já foi paga, não podes mudar o método de pagamento.';
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
CREATE FUNCTION  order_erase_addresses_check() RETURNS TRIGGER AS $$
begin
   IF 'Entregue' NOT IN (SELECT order_status FROM orders WHERE orders.id_address = old.id)
       THEN RAISE EXCEPTION 'Uma encomenda com este endereço ainda não foi entregue, não podes eleminá-lo.';
   END IF;
   RETURN OLD;
end
$$
   LANGUAGE plpgsql;

CREATE
   TRIGGER order_check_addresses
   BEFORE DELETE ON lbaw2124.addresses
   FOR EACH ROW
EXECUTE PROCEDURE order_erase_addresses_check();

--Trigger 9
CREATE FUNCTION order_status_check() RETURNS TRIGGER AS $$
   DECLARE st status;
begin
   IF 'Entregue' IN (SELECT order_status FROM orders WHERE orders.id = new.id)
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
        RAISE EXCEPTION 'Não existe quantidade suficiente deste produto.';
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
    IF (NEW.id_product NOT IN (SELECT id_product FROM orders,order_info WHERE orders.id = order_info.order_id AND orders.order_status = 'Entregue' AND orders.id_user = NEW.id_user)) THEN
        RAISE EXCEPTION 'Não pode avaliar produtos que não comprou.';
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

CREATE FUNCTION get_total(id_usr INTEGER)
RETURNS FLOAT
LANGUAGE plpgsql
AS $$
DECLARE
    total FLOAT := 0;
    item RECORD;
BEGIN
    FOR item IN (
                SELECT price, amount FROM shopping_cart_info JOIN products ON products.id = shopping_cart_info.id_product WHERE id_user = id_usr
            )
            LOOP
            total = (total + item.price * item.amount);
            END LOOP;
    RETURN total;
END
$$;

ALTER TABLE attributes
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
 BEFORE INSERT OR UPDATE ON attributes
 FOR EACH ROW
 EXECUTE PROCEDURE attribute_search_update();

CREATE INDEX search_attribute_idx ON attributes USING gist (tsvectors);

CREATE VIEW search_at AS
    SELECT id, products.id_product as id_product, name, category,
        description, price, photo, attributes.tsvectors as tsvectors
    FROM products
    JOIN attributes ON products.id = attributes.id_product;


CREATE OR REPLACE PROCEDURE lbaw2124.remove_order_transaction(rejected_id INTEGER)
    LANGUAGE plpgsql AS
$$
DECLARE
    product_info RECORD;
    --id_notif     INTEGER;
BEGIN
    COMMIT;
    SET TRANSACTION ISOLATION LEVEL REPEATABLE READ ;
    IF (rejected_id NOT IN
        (SELECT id FROM orders WHERE order_status = 'Cancelada' OR order_status = 'Entregue' OR order_status = 'Em trânsito'))
    THEN
        FOR product_info IN (
            SELECT id_product, quantity FROM order_info WHERE order_info.order_id = rejected_id
        )
            LOOP
                UPDATE products SET stock = stock + product_info.quantity WHERE id = product_info.id_product;
            END LOOP;
        UPDATE orders SET order_status = 'Cancelada' WHERE orders.id = rejected_id;

        COMMIT;
    ELSE
        RAISE EXCEPTION 'Não é possível cancelar esta encomenda.';
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
        INSERT INTO lbaw2124.orders (date_of_order, total_price, order_status, id_address, id_user,
                                     payment)
        VALUES (DEFAULT, 0, 'À espera de pagamento', adress_id, user_id, payment_m) RETURNING id INTO id_order;

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


            INSERT INTO notifications (description, date, url, id_user) VALUES ('A sua encomenda está à espera de pagamento.', NOW(), 'url', (SELECT id_user FROM orders WHERE orders.id = id_order)) RETURNING id INTO id_notif;
            INSERT INTO order_status_notification VALUES (id_notif, id_order);

        IF (payment_m = 'Account_credit' AND (SELECT account_credit FROM users WHERE users.id = user_id) >= (SELECT total_price FROM orders WHERE orders.id = id_order ) ) THEN
             UPDATE orders
                SET order_status = 'Em processamento'
                WHERE orders.id = id_order;
             UPDATE users
                 SET account_credit = account_credit - (SELECT total_price FROM orders WHERE orders.id = id_order )
                 WHERE users.id = user_id;
            INSERT INTO notifications (description, date, url, id_user) VALUES ('A sua encomenda está a ser processada.', NOW(), 'url', (SELECT id_user FROM orders WHERE orders.id = id_order)) RETURNING id INTO id_notif;
            INSERT INTO order_status_notification VALUES (id_notif, id_order);
        END IF;
    END IF;
    COMMIT;
END
$$;


CREATE OR REPLACE PROCEDURE update_status(order_id INTEGER, new_status status, id_adm INTEGER)
   LANGUAGE plpgsql AS $$
   DECLARE
   notif_description TEXT;
   st status;
   id_notif     INTEGER;
   id_history     INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

   SELECT orders.order_status INTO STRICT st FROM orders WHERE orders.id = order_id;

   -- Set notification description according to order status
   IF (st = 'Entregue' OR st = 'Cancelada') THEN
       RAISE EXCEPTION 'O estado de uma encomenda entregue ou cancelada não pode ser alterado.';

   ELSEIF (new_status = 'Em processamento')
       THEN  notif_description := 'A sua encomenda está a ser processada.';
       UPDATE orders
       SET order_status = new_status WHERE id = order_id;
   ELSEIF (new_status = 'Em trânsito')
       THEN  notif_description := 'A sua encomenda está em trânsito.';
       UPDATE orders
       SET order_status = new_status, date_of_departure = CURRENT_TIMESTAMP WHERE id = order_id;
   ELSEIF new_status = 'Cancelada'
       THEN  notif_description := 'A sua encomenda foi cancelada.';
       UPDATE orders
       SET order_status = new_status WHERE id = order_id;
       CALL remove_order_transaction(order_id);
   ELSEIF new_status = 'Entregue'
       THEN notif_description := 'A sua encomenda foi entregue.';
       UPDATE orders
       SET order_status = new_status, date_of_arrival = CURRENT_TIMESTAMP WHERE id = order_id;

   ELSE
       RAISE EXCEPTION 'Estado inválido para a encomenda.';
   END IF;

   -- Send notification to user about status update
   IF (notif_description IS NOT NULL) THEN
       INSERT INTO notifications (description, date, url, id_user) VALUES (notif_description, NOW(), 'url', (SELECT id_user FROM orders WHERE orders.id = order_id)) RETURNING id INTO id_notif;
       INSERT INTO order_status_notification VALUES (id_notif, order_id);

       INSERT INTO admin_history (id_admin,comment) VALUES(id_adm,'Estado da encomenda alterado.') RETURNING id INTO id_history;
       INSERT INTO order_update (id, id_order) VALUES (id_history, order_id);
   END IF;

   COMMIT;
END
$$;

CREATE OR REPLACE PROCEDURE product_on_sale(discount FLOAT, id_prod INTEGER, admin_id INTEGER)
   LANGUAGE plpgsql AS $$
   DECLARE
   user_wishlist RECORD;
   user_shopping_cart RECORD;
   id_notif INTEGER;
   id_hist INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

   UPDATE products
   SET on_sale = true
   WHERE products.id = id_prod;

   UPDATE products
   SET price = round((price - (price * discount))::numeric,2)
   WHERE products.id = id_prod;

   INSERT INTO admin_history (id_admin,comment) VALUES(admin_id,'Produto colocado em desconto.') RETURNING id INTO id_hist;
   INSERT INTO modification (id, id_product) VALUES (id_hist, id_prod);

     FOR user_wishlist IN (
            SELECT id_user FROM wish_list WHERE wish_list.id_product = id_prod
        )
            LOOP
                INSERT INTO notifications (description, date, url, id_user) VALUES ('Um produto na sua wishlist está em promoção.', NOW(), 'url', user_wishlist.id_user) RETURNING id INTO id_notif;
                INSERT INTO product_update_notification VALUES (id_notif, id_prod);
            END LOOP;

        FOR user_shopping_cart IN (
            SELECT id_user FROM shopping_cart_info WHERE shopping_cart_info.id_product = id_prod
        )
            LOOP
                INSERT INTO notifications (description, date, url, id_user) VALUES ('Um produto no seu carrinho está em promoção', NOW(), 'url', user_shopping_cart.id_user) RETURNING id INTO id_notif;
                INSERT INTO product_update_notification VALUES (id_notif, id_prod);
            END LOOP;

   COMMIT;
END
$$;

CREATE OR REPLACE PROCEDURE product_on_stock(new_stock INTEGER, id_prod INTEGER, admin_id INTEGER)
   LANGUAGE plpgsql AS $$
   DECLARE
   user_wishlist RECORD;
   user_shopping_cart RECORD;
   id_notif INTEGER;
   id_hist INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

   IF ((SELECT stock FROM products WHERE products.id = id_prod) = 0) THEN
       UPDATE products
       SET stock = new_stock
       WHERE products.id = id_prod;

       INSERT INTO admin_history (id_admin,comment) VALUES(admin_id,'Stock do produto alterado.') RETURNING id INTO id_hist;
       INSERT INTO modification (id, id_product) VALUES (id_hist, id_prod);

         FOR user_wishlist IN (
                SELECT id_user FROM wish_list WHERE wish_list.id_product = id_prod
            )
                LOOP
                    INSERT INTO notifications (description, date, url, id_user) VALUES ('Um produto na sua wishlist passou a estar disponível.', NOW(), 'url', user_wishlist.id_user) RETURNING id INTO id_notif;
                    INSERT INTO product_update_notification VALUES (id_notif, id_prod);
                END LOOP;

            FOR user_shopping_cart IN (
                SELECT id_user FROM shopping_cart_info WHERE shopping_cart_info.id_product = id_prod
            )
                LOOP
                    INSERT INTO notifications (description, date, url, id_user) VALUES ('Um produto no seu carrinho passou a estar disponível.', NOW(), 'url', user_shopping_cart.id_user) RETURNING id INTO id_notif;
                    INSERT INTO product_update_notification VALUES (id_notif, id_prod);
                END LOOP;
   ELSE
        UPDATE products
        SET stock = new_stock
        WHERE products.id = id_prod;

        INSERT INTO admin_history (id_admin,comment) VALUES(admin_id,'Stock do produto alterado.') RETURNING id INTO id_hist;
        INSERT INTO modification (id, id_product) VALUES (id_hist, id_prod);
        END IF;
   COMMIT;
END
$$;

CREATE OR REPLACE PROCEDURE lbaw2124.remove_category_transaction(category_to_delete TEXT)
    LANGUAGE plpgsql AS
$$
DECLARE
    search_at_def TEXT;
    exec_text TEXT;
    column_data record;
    table_name varchar(255);
    column_name varchar(255);
BEGIN
  search_at_def := pg_get_viewdef('search_at');
  DROP VIEW search_at;

    ALTER TYPE categories RENAME TO categories_old;
    EXECUTE format(
        'CREATE TYPE categories AS ENUM (%s)',
        (
            SELECT string_agg(quote_literal(value), ',')
            FROM unnest(enum_range(NULL::categories_old)) value
            WHERE value::TEXT <> category_to_delete
        )
    );

    ALTER TABLE products
    ALTER COLUMN category TYPE categories
    USING (category::text::categories);


    DROP TYPE categories_old;

  exec_text := format('create view search_at as %s',
      search_at_def);
  EXECUTE exec_text;
END
$$;

CREATE OR REPLACE PROCEDURE delete(delete_id INTEGER, admin_id INTEGER)
   LANGUAGE plpgsql AS $$
   DECLARE
   id_history     INTEGER;

BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
    IF NOT EXISTS(SELECT users.id FROM lbaw2124.users WHERE users.id = delete_id) THEN
        RAISE EXCEPTION 'O utilizador não existe!';
    ELSE
        UPDATE review
        SET id_user = 0 WHERE review.id_user = delete_id;
        DELETE FROM notifications WHERE notifications.id_user = delete_id;
        DELETE FROM orders WHERE orders.id_user = delete_id;
        DELETE FROM shopping_cart_info WHERE shopping_cart_info.id_user = delete_id;
        DELETE FROM wish_list WHERE wish_list.id_user = delete_id;
        DELETE FROM user_addresses WHERE user_addresses.id_user = delete_id;
        DELETE FROM user_stripe WHERE user_stripe.id_user = delete_id;

        IF (admin_id IS NOT null) THEN
            INSERT INTO admin_history (id_admin,comment) VALUES(admin_id,'Conta de utilizador eleminada.') RETURNING id INTO id_history;
            INSERT INTO deleted (id, email) VALUES (id_history, (SELECT email FROM users WHERE id = delete_id));
        END IF;

        DELETE FROM users WHERE users.id = delete_id;

    END IF;
   COMMIT;
END
$$;

CREATE OR REPLACE PROCEDURE block(user_id INTEGER, admin_id INTEGER)
   LANGUAGE plpgsql AS $$
    DECLARE
    id_history     INTEGER;
    id_notif     INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
    IF NOT EXISTS(SELECT users.id FROM lbaw2124.users WHERE users.id = user_id AND users.blocked = false) THEN
        RAISE EXCEPTION 'O utilizador não existe ou já está bloqueado';
    ELSE
        UPDATE users
        SET blocked = true WHERE users.id = user_id;

        INSERT INTO notifications (description, date, url, id_user) VALUES ('Foste bloqueado de fazer reviews!', NOW(), 'url', user_id) RETURNING id INTO id_notif;
        INSERT INTO flagged_notification VALUES (id_notif);

        INSERT INTO admin_history (id_admin,comment) VALUES(admin_id,'Utilizador bloqueado de fazer avaliações.') RETURNING id INTO id_history;
        INSERT INTO blocked (id, email) VALUES (id_history, (SELECT users.email FROM users WHERE users.id = user_id));
    END IF;
   COMMIT;
END
$$;

CREATE OR REPLACE PROCEDURE unblock(user_id INTEGER, admin_id INTEGER)
   LANGUAGE plpgsql AS $$
    DECLARE
    id_history     INTEGER;
    id_notif     INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;
    IF NOT EXISTS(SELECT users.id FROM lbaw2124.users WHERE users.id = user_id AND users.blocked = true) THEN
        RAISE EXCEPTION 'O utilizador não existe ou já está bloqueado';
    ELSE
        UPDATE users
        SET blocked = false WHERE users.id = user_id;

        INSERT INTO notifications (description, date, url, id_user) VALUES ('Já podes fazer review novamente!', NOW(), 'url', user_id) RETURNING id INTO id_notif;
        INSERT INTO flagged_notification VALUES (id_notif);

        INSERT INTO admin_history (id_admin,comment) VALUES(admin_id,'Utilizador está novamente autorizado a fazer avaliações.') RETURNING id INTO id_history;
        INSERT INTO blocked (id, email) VALUES (id_history, (SELECT email FROM users WHERE id = user_id));
    END IF;
   COMMIT;
END
$$;

CREATE OR REPLACE PROCEDURE hide_review(review_id INTEGER, admin_id INTEGER, reason INTEGER)
   LANGUAGE plpgsql AS $$
    DECLARE
    id_history     INTEGER;
    id_notif     INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

    UPDATE review
    SET hidden = true WHERE review.id = review_id;

    INSERT INTO notifications (description, date, url, id_user) VALUES ('A tua avaliação foi apagada por um administrador. Razão: '+ reason, NOW(), 'url', admin_id) RETURNING id INTO id_notif;
    INSERT INTO flagged_notification VALUES (id_notif);

    INSERT INTO admin_history (id_admin,comment) VALUES(admin_id,'Avaliação escondida. Razão: '+ reason) RETURNING id INTO id_history;
    INSERT INTO hidden_review (id, id_review) VALUES (id_history, review_id);

   COMMIT;
END
$$;

CREATE OR REPLACE PROCEDURE show_review(review_id INTEGER, admin_id INTEGER)
   LANGUAGE plpgsql AS $$
    DECLARE
    id_history     INTEGER;
    id_notif     INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

    UPDATE review
    SET hidden = false WHERE review.id = review_id;

    INSERT INTO notifications (description, date, url, id_user) VALUES ('A tua avaliação foi restaurada por um administrador.', NOW(), 'url', admin_id) RETURNING id INTO id_notif;
    INSERT INTO flagged_notification VALUES (id_notif);

    INSERT INTO admin_history (id_admin,comment) VALUES(admin_id,'Avaliação novamente visível.') RETURNING id INTO id_history;
    INSERT INTO hidden_review (id, id_review) VALUES (id_history, review_id);

   COMMIT;
END
$$;

CREATE OR REPLACE PROCEDURE report_review(review_id INTEGER, user_id INTEGER)
   LANGUAGE plpgsql AS $$
    DECLARE
    id_dash     INTEGER;
BEGIN
   COMMIT;
   SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

    UPDATE review
    SET reported = reported + 1 WHERE review.id = review_id;

    INSERT INTO admin_dashboard (comment) VALUES ('Avaliação reportada por um utilizador') RETURNING id INTO id_dash;
    INSERT INTO dashboard_review (id, id_review, id_user) VALUES (id_dash, review_id, user_id);

   COMMIT;
END
$$;


--Povoar
INSERT INTO users (name, email, password, blocked, account_credit, nif)
VALUES ('Freya Cameron', 'imperdiet.ullamcorper@protonmail.couk', '$2a$10$YV.4KAaLwOpBzvRubjNbFOMdrvWBceMYSDIFkl9GrWnd4b6a5g9nu', 'false', '1111165.03', '218526830'), --VSO36ISH4DT
       ('Duncan Duffy', 'nunc.ac.sem@icloud.com', '$2a$10$HMQY5xsZZWqJR0PMDBulIu.gga1qubnqTPuxCz5ulVdFdALtlcCci', 'true', '10.71', '266740286'), --CLG26UUU2HC
       ('Victoria Adkins', 'magna.sed@outlook.couk', '$2a$10$kk2LAUeQOHL78S58q2kKQ.G52mWB0tV7O7E4Qqam.QbJ4xjg0TMoa', 'false', '69.76', '247264202'), --QKU73QTG3MQ
       ('Vladimir Clayton', 'ut.erat.sed@aol.edu', '$2a$10$fXR4/8qVS00v7K6uo3mF6u4Bji1KoPhvedH6ORvcSevu0BIfFNM62', 'false', '6.17', '244819858'), --THP07SVZ5YS
       ('Zia Hodge', 'nec.cursus@hotmail.org', '$2a$10$5SS1NAI8TAMX1VYi2tdwSO/ndFJg0fSvianjYnW03K..SawZPW02W', 'true', '59.51', '210693983'), --CHT02BXV8HY
       ('Clare Adams', 'porta.elit@hotmail.ca', '$2a$10$hipQhOYg1xoKEr2GH7eAruZUajEDsYNYZa63/bLXaBuq4GGfVnEIe', 'false', '77.56', '281241465'), --UXI22QML1EY
       ('Tallulah Lowe', 'dictum.cursus@google.com', '$2a$10$OW6/4LExwDtLTiNAhLIikunLpnh8g4GvCw5Gfh9CZ8BB6A5Ek4HWe', 'false', '54.58', '298860201'), --WBO96EVU4JX
       ('Sage Weaver', 'ut@google.couk', '$2a$10$o0HkKWlMuS.fmsvFrobVcOrMO4k/RNwtDIiBUpv.UKIr495Nbqyx.', 'true', '64.91', '204270499'), --TOJ44BYF3KI
       ('Rina Vance', 'faucibus@protonmail.couk', '$2a$10$b3wZh3zuckc8OiZAldjMMOIgneNrO9efIuVp2GIlt7mST3SqM8ocK', 'true', '79.13', '214897753'), --ENR38HHS7PS
       ('Hadley Lewis', 'nascetur.ridiculus.mus@hotmail.net', '$2a$10$vU3wkRKPuVWpCC2lnodocOvZ.lcmDXUx.l0nzhBihJg6ib4xXIHey', 'true', '46.28', '274571056'); --YGX73TPM2RL

INSERT INTO admins (name, email, password)
VALUES ('Regan Navarro', 'ipsum@hotmail.edu', '$2a$10$p4Yh8CBgmJDgd7XTWvYAPeX44jjszb7ubh8VeJBCqjISbxICrr0.W'), --KCN92EMV1HG
       ('Donovan Andrews', 'sed@icloud.com', '$2a$10$6FlnlV5uYLIpS0o/ML28oeFMQgSW9GRE7YgDldmHtKGwLZFtrYHY.'); --QNS67VRV1LH

INSERT INTO warehouse (code, location, postal_code)
VALUES (1, 'Pernambuco', '7179623'),
       (2, 'Voronezh Oblast', '8823781'),
       (3, 'South Island', '3673373'),
       (4, 'Azad Kashmir', '9610741'),
       (5, 'West Nusa Tenggara', '9793738');

INSERT INTO products (name, category, stock, original_price, id_warehouse, description, photo)
VALUES ('HP Deskjet 3760', 'Impressora', 12, '779.98', 1, 'Melhor impressora do mercado. Acesso a Wifi.', 'hpdeskjet.jpg'),
       ('Fujifilm Instax Mini', 'Impressora', 73, '100.03', 1, 'Impressora compacta para viajar.', 'fujifilminstax.jpg'),
       ('Google Pixel 4', 'Telemóvel', 60, '394.83', 2, 'Telémovel com a melhor câmera para fotografias.', 'pixel4.jpg'),
       ('Samsung TAB6', 'Tablet', 83, '222.55', 2, 'Melhor definição de ecrã no mercado.', 'samsungtab6.jpg'),
       ('OKI ML5721', 'Impressora', 49, '290.22', 4, 'Impressora mais completa da loja.', 'okiml57.jpg'),
       ('Huawei Mate10 Pro', 'Telemóvel', 55, '958.29', 3, 'Telemóvel à prova de água.', 'huaweimate10.jpg'),
       ('Motorola g30 ', 'Telemóvel', 88, '960.49', 1, 'Telemóvel com várias câmeras.', 'motog30.jpg'),
       ('HP ink4000', 'Impressora', 17, '701.61', 2, 'Impressora com HP smart ink.', 'hpenvyprinter.jpg'),
       ('Epson200','Impressora', 113, '16.68', 3, 'Impressora com Epson economic ink. ', 'epsonxp3100.jpg'),
       ('Apple Ipad 10.2','Tablet', 76, '494.77', 4, 'Tablet com um ecrã de 10 polegadas.', 'ipad102.jpg'),
       ('Lenovo Tab M10','Tablet', 47, '629.11', 5, 'Tablet para leitura.', 'lenovom10.jpg'),
       ('Sony Xperia XZ  (Forest Blue)','Telemóvel', 142, '554.51', 1, 'Telemóvel Sony Xperia.', 'xperiaxz.jpg'),
       ('Huawei p30 PRO','Telemóvel', 60, '661.89', 4, 'Telemóvel com tecnologia Huawei Xenon.', 'huaweip30pro.jpg'),
       ('Lg 15inch Gram','Computador', 189, '146.34', 2, 'Portátil fino da Lg de 15 polegadas.', 'lggram15.jpg'),
       ('Epson Expression Home XP-4150','Impressora', 147, '907.69', 1, 'Impressora de escritório Epson.', 'epsonexpression.jpg'),
       ('Samsung Galaxy S10 White','Telemóvel', 125, '999.15', 3, 'Samsung Galaxy S10.', 'galaxys10white.jpg'),
       ('Oneplus 9 Blue','Telemóvel', 132, '443.36', 4, 'Oneplus 10 nova edição.', 'oneplus9blue.jpg'),
       ('Lenovo thinkpad T14','Computador', 28, '307.89', 3, 'Lenovo thinkpad i7 score.', 'thinkpadt14.jpg' ),
       ('Ipad Air Limited Edition: Blue Sky', 'Tablet', 124, '713.22', 5, 'Ipad Air edição azul metálico.', 'ipadairbluesky.jpg'),
       ('Asus Zenbook 14','Computador', 196, '730.08', 4, 'Portátil Asus.', 'asuszenbook14.jpg');

INSERT INTO attributes (attribute_type, id_product, value)
VALUES ('Peso (g)', 3, '205'),
       ('Cor', 1, 'Preto'),
       ('Velocidade de impressão a cores (ppm)', 2, '20'),
       ('Velocidade de impressão a preto e branco (ppm)', 1, '10'),
       ('Capacidade de Entrada', 2, '225'),
       ('Memória RAM (Gb)', 3, '8'),
       ('Memória interna (Gb)', 4, '32'),
       ('Capacidade da bateria (mAh)', 12, '5000'),
       ('Tipo de carregador', 6, 'USB-C'),
       ('Processador', 5, 'MT6765 Helio P35'),
       ('Velocidade do processador (GHz)', 16, '2.3'),
       ('Sistema Operativo', 12, 'Android 10'),
       ('Operadora de telefone', 9, 'Desbloqueado'),
       ('Ecrã (in)', 10, '6.5'),
       ('Resolução da câmera traseira (Mpx)', 5, '8'),
       ('Resolução da câmera frontal (Mpx)', 12, '64 + 8 + 5'),
       ('Placa gráfica', 4, 'Intel UHD Graphics'),
       ('Resolução do ecrã (px)', 18, '1920 x 1080 (FHD)');


INSERT INTO addresses (street, country, postal_code)
VALUES ('22 Darfield Rd, London', 'United Kingdom', 7830067),
       ('389 Church Rd, Southampton', 'United Kingdom', 7410444),
       ('1455 Rue Sherbrooke Ouest, Montréal', 'Canada', 1030200),
       ('12 Lovegrove Lane , Ajax', 'Canada', 1650025),
       ('5 Beech Avenue, Glasgow', 'United Kingdom', 7400500),
       ('107 Rue Jean-Paul-Lemieux , Cowansville', 'Canada', 1400352),
       ('677 St Annes Rd 4, Winnipeg', 'Canada', 1760220),
       ('12 Albion Drive, Devon', 'United Kingdom', 7550950),
       ('28 Marshall St, Nottingham', 'United Kingdom', 7220165),
       ('1359 Judd Rd , Brackendale', 'Canada', 1390782);

INSERT INTO stripe_srv (source_id)
VALUES (712147396),
       (771161596),
       (964602917),
       (250821269),
       (134411446),
       (505697911);

INSERT INTO orders (date_of_order, id_user, date_of_departure, date_of_arrival, order_status, total_price,
                    charge_id, id_address, payment)
VALUES ( '2021-3-13', 1, '2021-3-14', '2021-3-23', 'Entregue', 2282, 456, 1, 'Paypal'),
       ( '2021-3-13', 2, '2021-03-15', NULL, 'Em trânsito', 1249, 1234, 2, 'Stripe'),
       ( '2021-3-13', 3, NULL, NULL, 'Em processamento', 1249, 1234, 3, 'Stripe'),
       ( '2021-3-13', 4, NULL, NULL, 'À espera de pagamento', 1418, NULL, 4, 'Account_credit'),
       ( '2021-3-13', 5, '2021-3-14', '2021-3-23', 'Entregue', 2294, 12345, 5, 'Account_credit'),
       ( '2021-3-13', 6, NULL, NULL, 'Cancelada', 3770, NULL, 6, 'Stripe'),
       ( '2021-3-13', 1, '2021-3-14', '2021-3-23', 'Em trânsito', 2282, 456, 1, 'Paypal');

INSERT INTO order_info (order_id, id_product, quantity, current_price)
VALUES (1, 1, 2, 780),
       (1, 4, 1, 225),
       (1, 9, 1, 497),
       (1, 6, 1, 497),
       (2, 9, 1, 17),
       (2, 11, 1, 632),
       (2, 5, 2, 300),
       (3, 1, 2, 300),
       (3, 9, 1, 17),
       (3, 14, 2, 200),
       (3, 7, 1, 1000),
       (3, 2, 1, 1),
       (4, 2, 4, 1),
       (4, 6, 1, 970),
       (4, 8, 1, 700),
       (4, 18, 2, 310),
       (5, 6, 2, 960),
       (5, 7, 1, 940),
       (5, 15, 1, 910),
       (6, 4, 1 , 230);

INSERT INTO notifications (description, id_user)
VALUES ('A tua encomenda está à espera de pagamento.', 4),
       ('A tua encomenda está em transito.', 2),
       ('A tua encomenda está a ser processada.', 3),
       ('A tua encomenda foi entregue.', 1),
       ('A tua encomenda foi entregue.', 5),
       ('A tua encomenda foi cancelada.', 6),
       ('Um produto na sua wishlist mudou de preço', 2),
       ('Um produto no seu carrinho mudou de preço', 6),
       ('Uma avaliação sua foi apagada por um administrador.', 5);

INSERT INTO review (rating, description, date, reported, id_user, id_product,hidden)
VALUES (2,'Mau produto. Formato pouco prático', '2021-12-04', 4, 1, 1, true),
       (4,null, '2022-3-29', 1, 1, 4, true),
       (2,null, '2022-12-3', 3, 1, 9,true),
       (5,null, '2021-1-19', 0, 5, 6,DEFAULT),
       (5,null, '2021-7-16', 4, 5, 7,DEFAULT),
       (5,null, '2021-6-12', 1, 5, 15,DEFAULT);


INSERT INTO flagged_notification (id_notification)
VALUES (8);

INSERT INTO order_status_notification (id_notification, id_order)
VALUES (1, 4),
       (2, 2),
       (3, 3),
       (4, 1),
       (5, 5),
       (5, 6);

INSERT INTO product_update_notification (id_notification, id_product)
VALUES (6, 1),
       (7, 2);

INSERT INTO shopping_cart_info (id_user, id_product, amount)
VALUES (1, 1, 2),
       (1, 7, 5),
       (1, 3, 3),
       (1, 6, 2),
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
VALUES (2, 2),
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

INSERT INTO user_addresses (id_address, id_user)
VALUES (1, 1),
       (2, 2),
       (3, 3),
       (4, 4),
       (5, 5);

INSERT INTO user_stripe (id_user, id_stripe)
VALUES (1, 1),
       (2, 2),
       (3, 3),
       (4, 4),
       (5, 5),
       (6, 6);


INSERT INTO admin_history(id_admin, comment)
VALUES (1,'Atualização do preço do produto.'),
       (1, 'Atualização da foto do produto'),
       (1, 'Atualização da descriçção do produto.'),
       (1, 'Atualização do stock do produto.'),
       (1,'A avaliação era irrelevante.'),
       (1, 'A avaliação continha linguagem imprópria.'),
       (1, 'A avaliação foi avalida como spam.');

INSERT INTO modification (id, id_product)
VALUES (1, 2),
       (2, 3),
       (3, 4),
       (4, 5);

INSERT INTO hidden_review (id, id_review)
VALUES (5,1),
       (6,2),
       (7,3);
