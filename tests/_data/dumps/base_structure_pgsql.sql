--
-- PostgreSQL database dump
--

-- Dumped from database version 13.4
-- Dumped by pg_dump version 13.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.ticket_subscription DROP CONSTRAINT IF EXISTS fk_ticket_product_subscription_to_ticket;
ALTER TABLE IF EXISTS ONLY public.ticket_subscription DROP CONSTRAINT IF EXISTS fk_ticket_product_subscription_to_abonents_products;
ALTER TABLE IF EXISTS ONLY public.revshare_rates DROP CONSTRAINT IF EXISTS "fk_revshare_$product_id";
ALTER TABLE IF EXISTS ONLY public.relation_ticket_to_billing DROP CONSTRAINT IF EXISTS fk_relation_ticket_to_billing_to_ticket;
ALTER TABLE IF EXISTS ONLY public.relation_ticket_to_billing DROP CONSTRAINT IF EXISTS fk_relation_ticket_to_billing_to_billing_journal;
ALTER TABLE IF EXISTS ONLY public.sys_relation_users_tokens_to_tokens DROP CONSTRAINT IF EXISTS fk_rel_tokens_to_parent_token;
ALTER TABLE IF EXISTS ONLY public.sys_relation_users_tokens_to_tokens DROP CONSTRAINT IF EXISTS fk_rel_tokens_to_child_token;
ALTER TABLE IF EXISTS ONLY public.relation_abonents_to_products DROP CONSTRAINT IF EXISTS fk_ratp_to_products;
ALTER TABLE IF EXISTS ONLY public.relation_abonents_to_products DROP CONSTRAINT IF EXISTS fk_ratp_to_abonents;
ALTER TABLE IF EXISTS ONLY public.products_journal DROP CONSTRAINT IF EXISTS fk_ps_to_rel_abonents_to_products;
ALTER TABLE IF EXISTS ONLY public.subscriptions DROP CONSTRAINT IF EXISTS "fk-subscriptions-product_id";
ALTER TABLE IF EXISTS ONLY public.products DROP CONSTRAINT IF EXISTS "fk-products-user_id";
ALTER TABLE IF EXISTS ONLY public.products DROP CONSTRAINT IF EXISTS "fk-products-partner_id";
ALTER TABLE IF EXISTS ONLY public.partners DROP CONSTRAINT IF EXISTS "fk-partners-category_id";
DROP TRIGGER IF EXISTS update_updated_at ON public.subscriptions;
DROP TRIGGER IF EXISTS update_updated_at ON public.products;
DROP TRIGGER IF EXISTS update_updated_at ON public.partners;
DROP TRIGGER IF EXISTS update_updated_at ON public.contracts;
DROP TRIGGER IF EXISTS update_updated_at ON public.abonents;
DROP INDEX IF EXISTS public.users_options_user_id_option;
DROP INDEX IF EXISTS public.users_options_user_id;
DROP INDEX IF EXISTS public."user";
DROP INDEX IF EXISTS public.sys_users_tokens_user_id_auth_token;
DROP INDEX IF EXISTS public.sys_users_login;
DROP INDEX IF EXISTS public.sys_users_is_pwd_outdated;
DROP INDEX IF EXISTS public.sys_users_email;
DROP INDEX IF EXISTS public.sys_users_deleted;
DROP INDEX IF EXISTS public.sys_users_daddy;
DROP INDEX IF EXISTS public.sys_relation_users_tokens_to_tokens_parent_id_child_id;
DROP INDEX IF EXISTS public.sys_relation_users_to_permissions_user_id_permission_id;
DROP INDEX IF EXISTS public.sys_permissions_priority;
DROP INDEX IF EXISTS public.sys_permissions_name;
DROP INDEX IF EXISTS public.sys_permissions_module;
DROP INDEX IF EXISTS public.sys_permissions_controller_action_verb;
DROP INDEX IF EXISTS public.sys_permissions_collections_name;
DROP INDEX IF EXISTS public.sys_permissions_collections_default;
DROP INDEX IF EXISTS public.sys_options_option;
DROP INDEX IF EXISTS public.sys_notifications_type_receiver_object_id;
DROP INDEX IF EXISTS public.sys_notifications_type;
DROP INDEX IF EXISTS public.sys_notifications_receiver;
DROP INDEX IF EXISTS public.sys_notifications_object_id;
DROP INDEX IF EXISTS public.sys_notifications_initiator;
DROP INDEX IF EXISTS public.sys_file_storage_tags_file_tag;
DROP INDEX IF EXISTS public.sys_file_storage_path;
DROP INDEX IF EXISTS public.sys_file_storage_model_name_model_key;
DROP INDEX IF EXISTS public.sys_file_storage_deleted;
DROP INDEX IF EXISTS public.sys_file_storage_daddy;
DROP INDEX IF EXISTS public.status;
DROP INDEX IF EXISTS public.ssions_collections_to_permissions_collections_master_id_slave_i;
DROP INDEX IF EXISTS public.rmissions_collections_to_permissions_collection_id_permission_i;
DROP INDEX IF EXISTS public.reserved_at;
DROP INDEX IF EXISTS public.relation_users_to_phones_user_id_phone_id;
DROP INDEX IF EXISTS public.relation_model;
DROP INDEX IF EXISTS public.relation_contracts_to_products_contract_id_product_id;
DROP INDEX IF EXISTS public.relation_abonents_to_products_abonent_id_product_id;
DROP INDEX IF EXISTS public.ref_partners_categories_name;
DROP INDEX IF EXISTS public.ref_partners_categories_deleted;
DROP INDEX IF EXISTS public.processed;
DROP INDEX IF EXISTS public.priority;
DROP INDEX IF EXISTS public.phones_status;
DROP INDEX IF EXISTS public.phones_phone;
DROP INDEX IF EXISTS public.phones_deleted;
DROP INDEX IF EXISTS public.operation_identifier;
DROP INDEX IF EXISTS public.model_name_model_key;
DROP INDEX IF EXISTS public.model_key;
DROP INDEX IF EXISTS public.model_class_model_key;
DROP INDEX IF EXISTS public.model_class;
DROP INDEX IF EXISTS public.model;
DROP INDEX IF EXISTS public."in_revshare_$deleted";
DROP INDEX IF EXISTS public."idx-products-deleted";
DROP INDEX IF EXISTS public."idx-partners-payment_period";
DROP INDEX IF EXISTS public."idx-partners-name";
DROP INDEX IF EXISTS public."idx-partners-inn";
DROP INDEX IF EXISTS public."idx-partners-deleted";
DROP INDEX IF EXISTS public."idx-name-partner_id-type_id";
DROP INDEX IF EXISTS public."idx-contracts-numbers";
DROP INDEX IF EXISTS public."idx-contracts-deleted";
DROP INDEX IF EXISTS public."idx-abonents-phone";
DROP INDEX IF EXISTS public."idx-abonents-deleted";
DROP INDEX IF EXISTS public.i_billing_journal_to_rel_abonents_to_products;
DROP INDEX IF EXISTS public.history_tag;
DROP INDEX IF EXISTS public.event;
DROP INDEX IF EXISTS public.domain;
DROP INDEX IF EXISTS public.delegate;
DROP INDEX IF EXISTS public.daddy;
DROP INDEX IF EXISTS public.channel;
DROP INDEX IF EXISTS public._relation_users_to_permissions_collections_user_id_collection_i;
ALTER TABLE IF EXISTS ONLY public.users_options DROP CONSTRAINT IF EXISTS users_options_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_users_tokens DROP CONSTRAINT IF EXISTS sys_users_tokens_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_users DROP CONSTRAINT IF EXISTS sys_users_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_status DROP CONSTRAINT IF EXISTS sys_status_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_relation_users_tokens_to_tokens DROP CONSTRAINT IF EXISTS sys_relation_users_tokens_to_tokens_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_relation_users_to_permissions DROP CONSTRAINT IF EXISTS sys_relation_users_to_permissions_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_relation_users_to_permissions_collections DROP CONSTRAINT IF EXISTS sys_relation_users_to_permissions_collections_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_relation_permissions_collections_to_permissions DROP CONSTRAINT IF EXISTS sys_relation_permissions_collections_to_permissions_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_relation_permissions_collections_to_permissions_collections DROP CONSTRAINT IF EXISTS sys_relation_permissions_collections_to_permissions_collec_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_permissions DROP CONSTRAINT IF EXISTS sys_permissions_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_permissions_collections DROP CONSTRAINT IF EXISTS sys_permissions_collections_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_options DROP CONSTRAINT IF EXISTS sys_options_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_notifications DROP CONSTRAINT IF EXISTS sys_notifications_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_import DROP CONSTRAINT IF EXISTS sys_import_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_history_tags DROP CONSTRAINT IF EXISTS sys_history_tags_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_history DROP CONSTRAINT IF EXISTS sys_history_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_file_storage_tags DROP CONSTRAINT IF EXISTS sys_file_storage_tags_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_file_storage DROP CONSTRAINT IF EXISTS sys_file_storage_pkey;
ALTER TABLE IF EXISTS ONLY public.sys_exceptions DROP CONSTRAINT IF EXISTS sys_exceptions_pkey;
ALTER TABLE IF EXISTS ONLY public.subscriptions DROP CONSTRAINT IF EXISTS subscriptions_pkey;
ALTER TABLE IF EXISTS ONLY public.revshare_rates DROP CONSTRAINT IF EXISTS revshare_rates_pkey;
ALTER TABLE IF EXISTS ONLY public.relation_users_to_phones DROP CONSTRAINT IF EXISTS relation_users_to_phones_pkey;
ALTER TABLE IF EXISTS ONLY public.relation_ticket_to_billing DROP CONSTRAINT IF EXISTS relation_ticket_to_billing_pkey;
ALTER TABLE IF EXISTS ONLY public.relation_contracts_to_products DROP CONSTRAINT IF EXISTS relation_contracts_to_products_pkey;
ALTER TABLE IF EXISTS ONLY public.relation_abonents_to_products DROP CONSTRAINT IF EXISTS relation_abonents_to_products_pkey;
ALTER TABLE IF EXISTS ONLY public.ref_partners_categories DROP CONSTRAINT IF EXISTS ref_partners_categories_pkey;
ALTER TABLE IF EXISTS ONLY public.queue DROP CONSTRAINT IF EXISTS queue_pkey;
ALTER TABLE IF EXISTS ONLY public.products DROP CONSTRAINT IF EXISTS products_pkey;
ALTER TABLE IF EXISTS ONLY public.products_journal DROP CONSTRAINT IF EXISTS product_statuses_pkey;
ALTER TABLE IF EXISTS ONLY public.ticket_subscription DROP CONSTRAINT IF EXISTS "pk_ticket_product_subscription_$id";
ALTER TABLE IF EXISTS ONLY public.ticket DROP CONSTRAINT IF EXISTS "pk_ticket_$id";
ALTER TABLE IF EXISTS ONLY public.billing_journal DROP CONSTRAINT IF EXISTS pk_billing_journal;
ALTER TABLE IF EXISTS ONLY public.phones DROP CONSTRAINT IF EXISTS phones_pkey;
ALTER TABLE IF EXISTS ONLY public.partners DROP CONSTRAINT IF EXISTS partners_pkey;
ALTER TABLE IF EXISTS ONLY public.migration DROP CONSTRAINT IF EXISTS migration_pkey;
ALTER TABLE IF EXISTS ONLY public.contracts DROP CONSTRAINT IF EXISTS contracts_pkey;
ALTER TABLE IF EXISTS ONLY public.abonents DROP CONSTRAINT IF EXISTS abonents_pkey;
ALTER TABLE IF EXISTS public.users_options ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_users_tokens ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_users ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_status ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_relation_users_tokens_to_tokens ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_relation_users_to_permissions_collections ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_relation_users_to_permissions ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_relation_permissions_collections_to_permissions_collections ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_relation_permissions_collections_to_permissions ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_permissions_collections ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_permissions ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_options ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_notifications ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_import ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_history_tags ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_history ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_file_storage_tags ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_file_storage ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.sys_exceptions ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.subscriptions ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.revshare_rates ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.relation_users_to_phones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.relation_ticket_to_billing ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.relation_contracts_to_products ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.relation_abonents_to_products ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ref_partners_categories ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.products ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.phones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.partners ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.contracts ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.abonents ALTER COLUMN id DROP DEFAULT;
DROP SEQUENCE IF EXISTS public.users_options_id_seq;
DROP TABLE IF EXISTS public.users_options;
DROP TABLE IF EXISTS public.ticket_subscription;
DROP TABLE IF EXISTS public.ticket;
DROP SEQUENCE IF EXISTS public.sys_users_tokens_id_seq;
DROP TABLE IF EXISTS public.sys_users_tokens;
DROP SEQUENCE IF EXISTS public.sys_users_id_seq;
DROP TABLE IF EXISTS public.sys_users;
DROP SEQUENCE IF EXISTS public.sys_status_id_seq;
DROP TABLE IF EXISTS public.sys_status;
DROP SEQUENCE IF EXISTS public.sys_relation_users_tokens_to_tokens_id_seq;
DROP TABLE IF EXISTS public.sys_relation_users_tokens_to_tokens;
DROP SEQUENCE IF EXISTS public.sys_relation_users_to_permissions_id_seq;
DROP SEQUENCE IF EXISTS public.sys_relation_users_to_permissions_collections_id_seq;
DROP TABLE IF EXISTS public.sys_relation_users_to_permissions_collections;
DROP TABLE IF EXISTS public.sys_relation_users_to_permissions;
DROP SEQUENCE IF EXISTS public.sys_relation_permissions_collections_to_permissions_id_seq;
DROP SEQUENCE IF EXISTS public.sys_relation_permissions_collections_to_permissions_coll_id_seq;
DROP TABLE IF EXISTS public.sys_relation_permissions_collections_to_permissions_collections;
DROP TABLE IF EXISTS public.sys_relation_permissions_collections_to_permissions;
DROP SEQUENCE IF EXISTS public.sys_permissions_id_seq;
DROP SEQUENCE IF EXISTS public.sys_permissions_collections_id_seq;
DROP TABLE IF EXISTS public.sys_permissions_collections;
DROP TABLE IF EXISTS public.sys_permissions;
DROP SEQUENCE IF EXISTS public.sys_options_id_seq;
DROP TABLE IF EXISTS public.sys_options;
DROP SEQUENCE IF EXISTS public.sys_notifications_id_seq;
DROP TABLE IF EXISTS public.sys_notifications;
DROP SEQUENCE IF EXISTS public.sys_import_id_seq;
DROP TABLE IF EXISTS public.sys_import;
DROP SEQUENCE IF EXISTS public.sys_history_tags_id_seq;
DROP TABLE IF EXISTS public.sys_history_tags;
DROP SEQUENCE IF EXISTS public.sys_history_id_seq;
DROP TABLE IF EXISTS public.sys_history;
DROP SEQUENCE IF EXISTS public.sys_file_storage_tags_id_seq;
DROP TABLE IF EXISTS public.sys_file_storage_tags;
DROP SEQUENCE IF EXISTS public.sys_file_storage_id_seq;
DROP TABLE IF EXISTS public.sys_file_storage;
DROP SEQUENCE IF EXISTS public.sys_exceptions_id_seq;
DROP TABLE IF EXISTS public.sys_exceptions;
DROP SEQUENCE IF EXISTS public.subscriptions_id_seq;
DROP TABLE IF EXISTS public.subscriptions;
DROP SEQUENCE IF EXISTS public.revshare_rates_id_seq;
DROP TABLE IF EXISTS public.revshare_rates;
DROP SEQUENCE IF EXISTS public.relation_users_to_phones_id_seq;
DROP TABLE IF EXISTS public.relation_users_to_phones;
DROP SEQUENCE IF EXISTS public.relation_ticket_to_billing_id_seq;
DROP TABLE IF EXISTS public.relation_ticket_to_billing;
DROP SEQUENCE IF EXISTS public.relation_contracts_to_products_id_seq;
DROP TABLE IF EXISTS public.relation_contracts_to_products;
DROP SEQUENCE IF EXISTS public.relation_abonents_to_products_id_seq;
DROP TABLE IF EXISTS public.relation_abonents_to_products;
DROP SEQUENCE IF EXISTS public.ref_partners_categories_id_seq;
DROP TABLE IF EXISTS public.ref_partners_categories;
DROP SEQUENCE IF EXISTS public.queue_id_seq;
DROP TABLE IF EXISTS public.queue;
DROP SEQUENCE IF EXISTS public.products_id_seq;
DROP TABLE IF EXISTS public.products;
DROP SEQUENCE IF EXISTS public.product_statuses_id_seq;
DROP TABLE IF EXISTS public.products_journal;
DROP SEQUENCE IF EXISTS public.phones_id_seq;
DROP TABLE IF EXISTS public.phones;
DROP SEQUENCE IF EXISTS public.partners_id_seq;
DROP TABLE IF EXISTS public.partners;
DROP TABLE IF EXISTS public.migration;
DROP SEQUENCE IF EXISTS public.contracts_id_seq;
DROP TABLE IF EXISTS public.contracts;
DROP TABLE IF EXISTS public.billing_journal;
DROP SEQUENCE IF EXISTS public.abonents_id_seq;
DROP TABLE IF EXISTS public.abonents;
DROP FUNCTION IF EXISTS public.update_updated_at_column();
--
-- Name: update_updated_at_column(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.update_updated_at_column() RETURNS trigger
    LANGUAGE plpgsql
    AS $$ BEGIN NEW.updated_at = now(); RETURN NEW; END; $$;


ALTER FUNCTION public.update_updated_at_column() OWNER TO root;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: abonents; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.abonents (
    id integer NOT NULL,
    surname character varying(64),
    name character varying(64),
    patronymic character varying(64),
    phone character varying(255) NOT NULL,
    deleted boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.abonents OWNER TO root;

--
-- Name: COLUMN abonents.surname; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.abonents.surname IS 'Фамилия абонента';


--
-- Name: COLUMN abonents.name; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.abonents.name IS 'Имя абонента';


--
-- Name: COLUMN abonents.patronymic; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.abonents.patronymic IS 'Отчество абонента';


--
-- Name: COLUMN abonents.phone; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.abonents.phone IS 'Номер абонента';


--
-- Name: COLUMN abonents.deleted; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.abonents.deleted IS 'Флаг активности';


--
-- Name: COLUMN abonents.created_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.abonents.created_at IS 'Дата создания абонента';


--
-- Name: COLUMN abonents.updated_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.abonents.updated_at IS 'Дата обновления абонента';


--
-- Name: abonents_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.abonents_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.abonents_id_seq OWNER TO root;

--
-- Name: abonents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.abonents_id_seq OWNED BY public.abonents.id;


--
-- Name: billing_journal; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.billing_journal (
    id character(36) NOT NULL,
    rel_abonents_to_products_id integer NOT NULL,
    price numeric(8,2) NOT NULL,
    status_id smallint NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.billing_journal OWNER TO root;

--
-- Name: COLUMN billing_journal.rel_abonents_to_products_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.billing_journal.rel_abonents_to_products_id IS 'Связь с продуктом и абонентом';


--
-- Name: COLUMN billing_journal.price; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.billing_journal.price IS 'Величина списания';


--
-- Name: COLUMN billing_journal.status_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.billing_journal.status_id IS 'Статус списания';


--
-- Name: contracts; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.contracts (
    id integer NOT NULL,
    contract_number character varying(64) NOT NULL,
    contract_number_nfs character varying(64) NOT NULL,
    signing_date timestamp(0) without time zone,
    deleted boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.contracts OWNER TO root;

--
-- Name: COLUMN contracts.contract_number; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.contracts.contract_number IS '№ договора';


--
-- Name: COLUMN contracts.contract_number_nfs; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.contracts.contract_number_nfs IS '№ контракта';


--
-- Name: COLUMN contracts.signing_date; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.contracts.signing_date IS 'Дата подписания договора';


--
-- Name: COLUMN contracts.deleted; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.contracts.deleted IS 'Флаг активности';


--
-- Name: COLUMN contracts.created_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.contracts.created_at IS 'Дата создания договора';


--
-- Name: COLUMN contracts.updated_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.contracts.updated_at IS 'Дата обновления договора';


--
-- Name: contracts_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.contracts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contracts_id_seq OWNER TO root;

--
-- Name: contracts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.contracts_id_seq OWNED BY public.contracts.id;


--
-- Name: migration; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.migration (
    version character varying(180) NOT NULL,
    apply_time integer
);


ALTER TABLE public.migration OWNER TO root;

--
-- Name: partners; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.partners (
    id integer NOT NULL,
    name character varying(64) NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    deleted boolean DEFAULT false NOT NULL,
    inn character varying(12) NOT NULL,
    updated_at timestamp(0) without time zone,
    category_id integer NOT NULL,
    phone character varying(11) DEFAULT NULL::character varying,
    email character varying(255) DEFAULT NULL::character varying,
    comment text
);


ALTER TABLE public.partners OWNER TO root;

--
-- Name: COLUMN partners.name; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.partners.name IS 'Название партнера';


--
-- Name: COLUMN partners.created_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.partners.created_at IS 'Дата создания партнера';


--
-- Name: COLUMN partners.deleted; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.partners.deleted IS 'Флаг активности';


--
-- Name: COLUMN partners.inn; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.partners.inn IS 'ИНН партнера';


--
-- Name: COLUMN partners.updated_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.partners.updated_at IS 'Дата обновления партнера';


--
-- Name: COLUMN partners.category_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.partners.category_id IS 'id категории партнера';


--
-- Name: COLUMN partners.phone; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.partners.phone IS 'Телефон поддержки партнера';


--
-- Name: COLUMN partners.email; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.partners.email IS 'Почтовый адрес поддержки партнера';


--
-- Name: COLUMN partners.comment; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.partners.comment IS 'Комментарий';


--
-- Name: partners_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.partners_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.partners_id_seq OWNER TO root;

--
-- Name: partners_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.partners_id_seq OWNED BY public.partners.id;


--
-- Name: phones; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.phones (
    id integer NOT NULL,
    phone character varying(255) NOT NULL,
    create_date timestamp(0) without time zone DEFAULT now(),
    status integer,
    deleted boolean DEFAULT false NOT NULL
);


ALTER TABLE public.phones OWNER TO root;

--
-- Name: COLUMN phones.phone; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.phones.phone IS 'Телефон';


--
-- Name: COLUMN phones.create_date; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.phones.create_date IS 'Дата регистрации';


--
-- Name: COLUMN phones.status; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.phones.status IS 'Статус';


--
-- Name: phones_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.phones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.phones_id_seq OWNER TO root;

--
-- Name: phones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.phones_id_seq OWNED BY public.phones.id;


--
-- Name: products_journal; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.products_journal (
    id character(36) NOT NULL,
    rel_abonents_to_products_id integer NOT NULL,
    status_id integer NOT NULL,
    expire_date timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.products_journal OWNER TO root;

--
-- Name: COLUMN products_journal.expire_date; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products_journal.expire_date IS 'Дата окончания предоставления услуги';


--
-- Name: product_statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.product_statuses_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.product_statuses_id_seq OWNER TO root;

--
-- Name: product_statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.product_statuses_id_seq OWNED BY public.products_journal.id;


--
-- Name: products; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.products (
    id integer NOT NULL,
    name character varying(64) NOT NULL,
    description character varying(255) NOT NULL,
    type_id integer,
    user_id integer NOT NULL,
    partner_id integer NOT NULL,
    deleted boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) without time zone,
    price numeric(8,2) DEFAULT 0 NOT NULL,
    start_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    end_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    payment_period smallint DEFAULT 0,
    ext_description text NOT NULL
);


ALTER TABLE public.products OWNER TO root;

--
-- Name: COLUMN products.name; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.name IS 'Название продукта';


--
-- Name: COLUMN products.description; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.description IS 'Описание продукта';


--
-- Name: COLUMN products.type_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.type_id IS 'id типа (подписка, бандл и т.д)';


--
-- Name: COLUMN products.user_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.user_id IS 'id пользователя, создателя';


--
-- Name: COLUMN products.partner_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.partner_id IS 'id партнера, к кому привязан';


--
-- Name: COLUMN products.deleted; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.deleted IS 'Флаг активности';


--
-- Name: COLUMN products.created_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.created_at IS 'Дата создания партнера';


--
-- Name: COLUMN products.updated_at; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.updated_at IS 'Дата обновления продукта';


--
-- Name: COLUMN products.start_date; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.start_date IS 'Дата начала действия продукта';


--
-- Name: COLUMN products.end_date; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.end_date IS 'Дата окончания действия продукта';


--
-- Name: COLUMN products.payment_period; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.payment_period IS 'Периодичность списания';


--
-- Name: COLUMN products.ext_description; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.products.ext_description IS 'Полное описание продукта';


--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.products_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.products_id_seq OWNER TO root;

--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;


--
-- Name: queue; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.queue (
    id character(36) NOT NULL,
    channel character varying(255) NOT NULL,
    job bytea NOT NULL,
    pushed_at integer NOT NULL,
    reserved_at integer,
    done_at integer,
    delay integer DEFAULT 0 NOT NULL,
    ttr integer NOT NULL,
    attempt integer,
    priority integer DEFAULT 1024 NOT NULL
);


ALTER TABLE public.queue OWNER TO root;

--
-- Name: queue_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.queue_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.queue_id_seq OWNER TO root;

--
-- Name: queue_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.queue_id_seq OWNED BY public.queue.id;


--
-- Name: ref_partners_categories; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.ref_partners_categories (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    deleted boolean DEFAULT false NOT NULL
);


ALTER TABLE public.ref_partners_categories OWNER TO root;

--
-- Name: ref_partners_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.ref_partners_categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ref_partners_categories_id_seq OWNER TO root;

--
-- Name: ref_partners_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.ref_partners_categories_id_seq OWNED BY public.ref_partners_categories.id;


--
-- Name: relation_abonents_to_products; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.relation_abonents_to_products (
    id integer NOT NULL,
    abonent_id integer NOT NULL,
    product_id integer NOT NULL
);


ALTER TABLE public.relation_abonents_to_products OWNER TO root;

--
-- Name: relation_abonents_to_products_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.relation_abonents_to_products_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.relation_abonents_to_products_id_seq OWNER TO root;

--
-- Name: relation_abonents_to_products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.relation_abonents_to_products_id_seq OWNED BY public.relation_abonents_to_products.id;


--
-- Name: relation_contracts_to_products; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.relation_contracts_to_products (
    id integer NOT NULL,
    contract_id integer NOT NULL,
    product_id integer NOT NULL
);


ALTER TABLE public.relation_contracts_to_products OWNER TO root;

--
-- Name: relation_contracts_to_products_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.relation_contracts_to_products_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.relation_contracts_to_products_id_seq OWNER TO root;

--
-- Name: relation_contracts_to_products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.relation_contracts_to_products_id_seq OWNED BY public.relation_contracts_to_products.id;


--
-- Name: relation_ticket_to_billing; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.relation_ticket_to_billing (
    id integer NOT NULL,
    ticket_id character(36) NOT NULL,
    billing_id character(36) NOT NULL
);


ALTER TABLE public.relation_ticket_to_billing OWNER TO root;

--
-- Name: relation_ticket_to_billing_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.relation_ticket_to_billing_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.relation_ticket_to_billing_id_seq OWNER TO root;

--
-- Name: relation_ticket_to_billing_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.relation_ticket_to_billing_id_seq OWNED BY public.relation_ticket_to_billing.id;


--
-- Name: relation_users_to_phones; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.relation_users_to_phones (
    id integer NOT NULL,
    user_id integer NOT NULL,
    phone_id integer NOT NULL
);


ALTER TABLE public.relation_users_to_phones OWNER TO root;

--
-- Name: relation_users_to_phones_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.relation_users_to_phones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.relation_users_to_phones_id_seq OWNER TO root;

--
-- Name: relation_users_to_phones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.relation_users_to_phones_id_seq OWNED BY public.relation_users_to_phones.id;


--
-- Name: revshare_rates; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.revshare_rates (
    id integer NOT NULL,
    type smallint NOT NULL,
    rate numeric(3,2) NOT NULL,
    condition_value integer NOT NULL,
    product_id integer NOT NULL,
    deleted boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.revshare_rates OWNER TO root;

--
-- Name: COLUMN revshare_rates.rate; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.revshare_rates.rate IS 'Процентная ставка';


--
-- Name: COLUMN revshare_rates.condition_value; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.revshare_rates.condition_value IS 'Пороговое значение для активации ставки';


--
-- Name: COLUMN revshare_rates.deleted; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.revshare_rates.deleted IS 'Флаг активности';


--
-- Name: revshare_rates_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.revshare_rates_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.revshare_rates_id_seq OWNER TO root;

--
-- Name: revshare_rates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.revshare_rates_id_seq OWNED BY public.revshare_rates.id;


--
-- Name: subscriptions; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.subscriptions (
    id integer NOT NULL,
    product_id integer NOT NULL,
    trial_count integer DEFAULT 0 NOT NULL,
    units smallint DEFAULT 1 NOT NULL
);


ALTER TABLE public.subscriptions OWNER TO root;

--
-- Name: COLUMN subscriptions.product_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.subscriptions.product_id IS 'id продукта';


--
-- Name: COLUMN subscriptions.units; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.subscriptions.units IS 'Единица измерения триального периода';


--
-- Name: subscriptions_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.subscriptions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.subscriptions_id_seq OWNER TO root;

--
-- Name: subscriptions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.subscriptions_id_seq OWNED BY public.subscriptions.id;


--
-- Name: sys_exceptions; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_exceptions (
    id integer NOT NULL,
    "timestamp" timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    user_id integer,
    code integer,
    file character varying(255),
    line integer,
    message text,
    trace text,
    get text,
    post text,
    known boolean DEFAULT false NOT NULL,
    "statusCode" integer
);


ALTER TABLE public.sys_exceptions OWNER TO root;

--
-- Name: COLUMN sys_exceptions.get; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_exceptions.get IS 'GET';


--
-- Name: COLUMN sys_exceptions.post; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_exceptions.post IS 'POST';


--
-- Name: COLUMN sys_exceptions.known; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_exceptions.known IS 'Known error';


--
-- Name: COLUMN sys_exceptions."statusCode"; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_exceptions."statusCode" IS 'HTTP status code';


--
-- Name: sys_exceptions_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_exceptions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_exceptions_id_seq OWNER TO root;

--
-- Name: sys_exceptions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_exceptions_id_seq OWNED BY public.sys_exceptions.id;


--
-- Name: sys_file_storage; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_file_storage (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    model_name character varying(255) DEFAULT NULL::character varying,
    model_key integer,
    at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    daddy integer,
    delegate character varying(255) DEFAULT NULL::character varying,
    deleted boolean DEFAULT false NOT NULL
);


ALTER TABLE public.sys_file_storage OWNER TO root;

--
-- Name: sys_file_storage_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_file_storage_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_file_storage_id_seq OWNER TO root;

--
-- Name: sys_file_storage_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_file_storage_id_seq OWNED BY public.sys_file_storage.id;


--
-- Name: sys_file_storage_tags; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_file_storage_tags (
    id integer NOT NULL,
    file integer NOT NULL,
    tag character varying(255) NOT NULL
);


ALTER TABLE public.sys_file_storage_tags OWNER TO root;

--
-- Name: sys_file_storage_tags_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_file_storage_tags_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_file_storage_tags_id_seq OWNER TO root;

--
-- Name: sys_file_storage_tags_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_file_storage_tags_id_seq OWNED BY public.sys_file_storage_tags.id;


--
-- Name: sys_history; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_history (
    id integer NOT NULL,
    at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    "user" integer,
    model_class character varying(255) DEFAULT NULL::character varying,
    model_key integer,
    old_attributes bytea,
    new_attributes bytea,
    relation_model character varying(255) DEFAULT NULL::character varying,
    scenario character varying(255) DEFAULT NULL::character varying,
    event character varying(255) DEFAULT NULL::character varying,
    operation_identifier character varying(255) DEFAULT NULL::character varying,
    delegate integer
);


ALTER TABLE public.sys_history OWNER TO root;

--
-- Name: COLUMN sys_history.old_attributes; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_history.old_attributes IS 'Old serialized attributes';


--
-- Name: COLUMN sys_history.new_attributes; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_history.new_attributes IS 'New serialized attributes';


--
-- Name: sys_history_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_history_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_history_id_seq OWNER TO root;

--
-- Name: sys_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_history_id_seq OWNED BY public.sys_history.id;


--
-- Name: sys_history_tags; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_history_tags (
    id integer NOT NULL,
    history integer NOT NULL,
    tag character varying(255) NOT NULL
);


ALTER TABLE public.sys_history_tags OWNER TO root;

--
-- Name: sys_history_tags_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_history_tags_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_history_tags_id_seq OWNER TO root;

--
-- Name: sys_history_tags_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_history_tags_id_seq OWNED BY public.sys_history_tags.id;


--
-- Name: sys_import; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_import (
    id integer NOT NULL,
    model character varying(255) NOT NULL,
    domain integer NOT NULL,
    data bytea,
    processed integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.sys_import OWNER TO root;

--
-- Name: sys_import_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_import_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_import_id_seq OWNER TO root;

--
-- Name: sys_import_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_import_id_seq OWNED BY public.sys_import.id;


--
-- Name: sys_notifications; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_notifications (
    id integer NOT NULL,
    type integer DEFAULT 0 NOT NULL,
    initiator integer,
    receiver integer,
    object_id integer,
    comment text,
    "timestamp" timestamp(0) without time zone DEFAULT now()
);


ALTER TABLE public.sys_notifications OWNER TO root;

--
-- Name: COLUMN sys_notifications.type; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_notifications.type IS 'Тип уведомления';


--
-- Name: COLUMN sys_notifications.initiator; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_notifications.initiator IS 'автор уведомления, null - система';


--
-- Name: COLUMN sys_notifications.receiver; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_notifications.receiver IS 'получатель уведомления, null - определяется типом';


--
-- Name: COLUMN sys_notifications.object_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_notifications.object_id IS 'идентификатор объекта уведомления, null - определяется типом';


--
-- Name: sys_notifications_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_notifications_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_notifications_id_seq OWNER TO root;

--
-- Name: sys_notifications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_notifications_id_seq OWNED BY public.sys_notifications.id;


--
-- Name: sys_options; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_options (
    id integer NOT NULL,
    option character varying(256) NOT NULL,
    value bytea
);


ALTER TABLE public.sys_options OWNER TO root;

--
-- Name: COLUMN sys_options.option; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_options.option IS 'Option name';


--
-- Name: COLUMN sys_options.value; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_options.value IS 'Serialized option value';


--
-- Name: sys_options_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_options_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_options_id_seq OWNER TO root;

--
-- Name: sys_options_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_options_id_seq OWNED BY public.sys_options.id;


--
-- Name: sys_permissions; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_permissions (
    id integer NOT NULL,
    name character varying(128) NOT NULL,
    controller character varying(255) DEFAULT NULL::character varying,
    action character varying(255) DEFAULT NULL::character varying,
    verb character varying(255) DEFAULT NULL::character varying,
    comment text,
    priority integer DEFAULT 0 NOT NULL,
    module character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.sys_permissions OWNER TO root;

--
-- Name: COLUMN sys_permissions.name; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_permissions.name IS 'Название доступа';


--
-- Name: COLUMN sys_permissions.controller; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_permissions.controller IS 'Контроллер, к которому устанавливается доступ, null для внутреннего доступа';


--
-- Name: COLUMN sys_permissions.action; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_permissions.action IS 'Действие, для которого устанавливается доступ, null для всех действий контроллера';


--
-- Name: COLUMN sys_permissions.verb; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_permissions.verb IS 'REST-метод, для которого устанавливается доступ';


--
-- Name: COLUMN sys_permissions.comment; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_permissions.comment IS 'Описание доступа';


--
-- Name: COLUMN sys_permissions.priority; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_permissions.priority IS 'Приоритет использования (больше - выше)';


--
-- Name: sys_permissions_collections; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_permissions_collections (
    id integer NOT NULL,
    name character varying(128) NOT NULL,
    comment text,
    "default" boolean DEFAULT false NOT NULL
);


ALTER TABLE public.sys_permissions_collections OWNER TO root;

--
-- Name: COLUMN sys_permissions_collections.name; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_permissions_collections.name IS 'Название группы доступа';


--
-- Name: COLUMN sys_permissions_collections.comment; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_permissions_collections.comment IS 'Описание группы доступа';


--
-- Name: COLUMN sys_permissions_collections."default"; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_permissions_collections."default" IS 'Включение группы по умолчанию';


--
-- Name: sys_permissions_collections_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_permissions_collections_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_permissions_collections_id_seq OWNER TO root;

--
-- Name: sys_permissions_collections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_permissions_collections_id_seq OWNED BY public.sys_permissions_collections.id;


--
-- Name: sys_permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_permissions_id_seq OWNER TO root;

--
-- Name: sys_permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_permissions_id_seq OWNED BY public.sys_permissions.id;


--
-- Name: sys_relation_permissions_collections_to_permissions; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_relation_permissions_collections_to_permissions (
    id integer NOT NULL,
    collection_id integer NOT NULL,
    permission_id integer NOT NULL
);


ALTER TABLE public.sys_relation_permissions_collections_to_permissions OWNER TO root;

--
-- Name: COLUMN sys_relation_permissions_collections_to_permissions.collection_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_relation_permissions_collections_to_permissions.collection_id IS 'Ключ группы доступа';


--
-- Name: COLUMN sys_relation_permissions_collections_to_permissions.permission_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_relation_permissions_collections_to_permissions.permission_id IS 'Ключ правила доступа';


--
-- Name: sys_relation_permissions_collections_to_permissions_collections; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_relation_permissions_collections_to_permissions_collections (
    id integer NOT NULL,
    master_id integer NOT NULL,
    slave_id integer NOT NULL
);


ALTER TABLE public.sys_relation_permissions_collections_to_permissions_collections OWNER TO root;

--
-- Name: sys_relation_permissions_collections_to_permissions_coll_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_relation_permissions_collections_to_permissions_coll_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_relation_permissions_collections_to_permissions_coll_id_seq OWNER TO root;

--
-- Name: sys_relation_permissions_collections_to_permissions_coll_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_relation_permissions_collections_to_permissions_coll_id_seq OWNED BY public.sys_relation_permissions_collections_to_permissions_collections.id;


--
-- Name: sys_relation_permissions_collections_to_permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_relation_permissions_collections_to_permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_relation_permissions_collections_to_permissions_id_seq OWNER TO root;

--
-- Name: sys_relation_permissions_collections_to_permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_relation_permissions_collections_to_permissions_id_seq OWNED BY public.sys_relation_permissions_collections_to_permissions.id;


--
-- Name: sys_relation_users_to_permissions; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_relation_users_to_permissions (
    id integer NOT NULL,
    user_id integer NOT NULL,
    permission_id integer NOT NULL
);


ALTER TABLE public.sys_relation_users_to_permissions OWNER TO root;

--
-- Name: COLUMN sys_relation_users_to_permissions.user_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_relation_users_to_permissions.user_id IS 'Ключ объекта доступа';


--
-- Name: COLUMN sys_relation_users_to_permissions.permission_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_relation_users_to_permissions.permission_id IS 'Ключ правила доступа';


--
-- Name: sys_relation_users_to_permissions_collections; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_relation_users_to_permissions_collections (
    id integer NOT NULL,
    user_id integer NOT NULL,
    collection_id integer NOT NULL
);


ALTER TABLE public.sys_relation_users_to_permissions_collections OWNER TO root;

--
-- Name: COLUMN sys_relation_users_to_permissions_collections.user_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_relation_users_to_permissions_collections.user_id IS 'Ключ объекта доступа';


--
-- Name: COLUMN sys_relation_users_to_permissions_collections.collection_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_relation_users_to_permissions_collections.collection_id IS 'Ключ группы доступа';


--
-- Name: sys_relation_users_to_permissions_collections_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_relation_users_to_permissions_collections_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_relation_users_to_permissions_collections_id_seq OWNER TO root;

--
-- Name: sys_relation_users_to_permissions_collections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_relation_users_to_permissions_collections_id_seq OWNED BY public.sys_relation_users_to_permissions_collections.id;


--
-- Name: sys_relation_users_to_permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_relation_users_to_permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_relation_users_to_permissions_id_seq OWNER TO root;

--
-- Name: sys_relation_users_to_permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_relation_users_to_permissions_id_seq OWNED BY public.sys_relation_users_to_permissions.id;


--
-- Name: sys_relation_users_tokens_to_tokens; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_relation_users_tokens_to_tokens (
    id integer NOT NULL,
    parent_id integer NOT NULL,
    child_id integer NOT NULL
);


ALTER TABLE public.sys_relation_users_tokens_to_tokens OWNER TO root;

--
-- Name: sys_relation_users_tokens_to_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_relation_users_tokens_to_tokens_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_relation_users_tokens_to_tokens_id_seq OWNER TO root;

--
-- Name: sys_relation_users_tokens_to_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_relation_users_tokens_to_tokens_id_seq OWNED BY public.sys_relation_users_tokens_to_tokens.id;


--
-- Name: sys_status; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_status (
    id integer NOT NULL,
    model_name character varying(255) DEFAULT NULL::character varying,
    model_key integer,
    status integer NOT NULL,
    at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP,
    daddy integer,
    delegate character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.sys_status OWNER TO root;

--
-- Name: sys_status_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_status_id_seq OWNER TO root;

--
-- Name: sys_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_status_id_seq OWNED BY public.sys_status.id;


--
-- Name: sys_users; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_users (
    id integer NOT NULL,
    login character varying(64) NOT NULL,
    password character varying(255) NOT NULL,
    salt character varying(255) DEFAULT NULL::character varying,
    email character varying(255) NOT NULL,
    comment text,
    create_date timestamp(0) without time zone NOT NULL,
    daddy integer,
    deleted boolean DEFAULT false,
    is_pwd_outdated boolean DEFAULT false NOT NULL,
    restore_code character varying(255),
    surname character varying(255),
    name character varying(255)
);


ALTER TABLE public.sys_users OWNER TO root;

--
-- Name: COLUMN sys_users.login; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.login IS 'Логин';


--
-- Name: COLUMN sys_users.password; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.password IS 'Хеш пароля';


--
-- Name: COLUMN sys_users.salt; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.salt IS 'Unique random salt hash';


--
-- Name: COLUMN sys_users.email; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.email IS 'email';


--
-- Name: COLUMN sys_users.comment; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.comment IS 'Служебный комментарий пользователя';


--
-- Name: COLUMN sys_users.create_date; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.create_date IS 'Дата регистрации';


--
-- Name: COLUMN sys_users.daddy; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.daddy IS 'ID зарегистрировавшего/проверившего пользователя';


--
-- Name: COLUMN sys_users.deleted; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.deleted IS 'Флаг удаления';


--
-- Name: COLUMN sys_users.is_pwd_outdated; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.is_pwd_outdated IS 'Ожидается смена пароля';


--
-- Name: COLUMN sys_users.restore_code; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.restore_code IS 'Код восстановления';


--
-- Name: COLUMN sys_users.surname; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.surname IS 'Фамилия пользователя';


--
-- Name: COLUMN sys_users.name; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users.name IS 'Имя пользователя';


--
-- Name: sys_users_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_users_id_seq OWNER TO root;

--
-- Name: sys_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_users_id_seq OWNED BY public.sys_users.id;


--
-- Name: sys_users_tokens; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.sys_users_tokens (
    id integer NOT NULL,
    user_id integer NOT NULL,
    auth_token character varying(40) NOT NULL,
    created timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    valid timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    ip character varying(255) DEFAULT NULL::character varying,
    user_agent character varying(255) DEFAULT NULL::character varying,
    type_id smallint NOT NULL
);


ALTER TABLE public.sys_users_tokens OWNER TO root;

--
-- Name: COLUMN sys_users_tokens.user_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users_tokens.user_id IS 'user id foreign key';


--
-- Name: COLUMN sys_users_tokens.auth_token; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users_tokens.auth_token IS 'Bearer auth token';


--
-- Name: COLUMN sys_users_tokens.created; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users_tokens.created IS 'Таймстамп создания';


--
-- Name: COLUMN sys_users_tokens.valid; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users_tokens.valid IS 'Действует до';


--
-- Name: COLUMN sys_users_tokens.ip; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users_tokens.ip IS 'Адрес авторизации';


--
-- Name: COLUMN sys_users_tokens.user_agent; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users_tokens.user_agent IS 'User-Agent';


--
-- Name: COLUMN sys_users_tokens.type_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.sys_users_tokens.type_id IS 'Тип токена';


--
-- Name: sys_users_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.sys_users_tokens_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sys_users_tokens_id_seq OWNER TO root;

--
-- Name: sys_users_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.sys_users_tokens_id_seq OWNED BY public.sys_users_tokens.id;


--
-- Name: ticket; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.ticket (
    id character(36) NOT NULL,
    type smallint NOT NULL,
    created_by integer,
    created_at timestamp(0) without time zone NOT NULL,
    completed_at timestamp(0) without time zone,
    stage_id integer NOT NULL,
    status smallint NOT NULL,
    journal_data jsonb
);


ALTER TABLE public.ticket OWNER TO root;

--
-- Name: ticket_subscription; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.ticket_subscription (
    id character(36) NOT NULL,
    action smallint NOT NULL,
    rel_abonents_to_products_id integer
);


ALTER TABLE public.ticket_subscription OWNER TO root;

--
-- Name: users_options; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.users_options (
    id integer NOT NULL,
    user_id integer,
    option character varying(256) NOT NULL,
    value bytea
);


ALTER TABLE public.users_options OWNER TO root;

--
-- Name: COLUMN users_options.user_id; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.users_options.user_id IS 'System user id';


--
-- Name: COLUMN users_options.option; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.users_options.option IS 'Option name';


--
-- Name: COLUMN users_options.value; Type: COMMENT; Schema: public; Owner: root
--

COMMENT ON COLUMN public.users_options.value IS 'Serialized option value';


--
-- Name: users_options_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.users_options_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_options_id_seq OWNER TO root;

--
-- Name: users_options_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.users_options_id_seq OWNED BY public.users_options.id;


--
-- Name: abonents id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.abonents ALTER COLUMN id SET DEFAULT nextval('public.abonents_id_seq'::regclass);


--
-- Name: contracts id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.contracts ALTER COLUMN id SET DEFAULT nextval('public.contracts_id_seq'::regclass);


--
-- Name: partners id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.partners ALTER COLUMN id SET DEFAULT nextval('public.partners_id_seq'::regclass);


--
-- Name: phones id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.phones ALTER COLUMN id SET DEFAULT nextval('public.phones_id_seq'::regclass);


--
-- Name: products id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);


--
-- Name: ref_partners_categories id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.ref_partners_categories ALTER COLUMN id SET DEFAULT nextval('public.ref_partners_categories_id_seq'::regclass);


--
-- Name: relation_abonents_to_products id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_abonents_to_products ALTER COLUMN id SET DEFAULT nextval('public.relation_abonents_to_products_id_seq'::regclass);


--
-- Name: relation_contracts_to_products id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_contracts_to_products ALTER COLUMN id SET DEFAULT nextval('public.relation_contracts_to_products_id_seq'::regclass);


--
-- Name: relation_ticket_to_billing id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_ticket_to_billing ALTER COLUMN id SET DEFAULT nextval('public.relation_ticket_to_billing_id_seq'::regclass);


--
-- Name: relation_users_to_phones id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_users_to_phones ALTER COLUMN id SET DEFAULT nextval('public.relation_users_to_phones_id_seq'::regclass);


--
-- Name: revshare_rates id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.revshare_rates ALTER COLUMN id SET DEFAULT nextval('public.revshare_rates_id_seq'::regclass);


--
-- Name: subscriptions id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.subscriptions ALTER COLUMN id SET DEFAULT nextval('public.subscriptions_id_seq'::regclass);


--
-- Name: sys_exceptions id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_exceptions ALTER COLUMN id SET DEFAULT nextval('public.sys_exceptions_id_seq'::regclass);


--
-- Name: sys_file_storage id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_file_storage ALTER COLUMN id SET DEFAULT nextval('public.sys_file_storage_id_seq'::regclass);


--
-- Name: sys_file_storage_tags id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_file_storage_tags ALTER COLUMN id SET DEFAULT nextval('public.sys_file_storage_tags_id_seq'::regclass);


--
-- Name: sys_history id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_history ALTER COLUMN id SET DEFAULT nextval('public.sys_history_id_seq'::regclass);


--
-- Name: sys_history_tags id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_history_tags ALTER COLUMN id SET DEFAULT nextval('public.sys_history_tags_id_seq'::regclass);


--
-- Name: sys_import id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_import ALTER COLUMN id SET DEFAULT nextval('public.sys_import_id_seq'::regclass);


--
-- Name: sys_notifications id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_notifications ALTER COLUMN id SET DEFAULT nextval('public.sys_notifications_id_seq'::regclass);


--
-- Name: sys_options id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_options ALTER COLUMN id SET DEFAULT nextval('public.sys_options_id_seq'::regclass);


--
-- Name: sys_permissions id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_permissions ALTER COLUMN id SET DEFAULT nextval('public.sys_permissions_id_seq'::regclass);


--
-- Name: sys_permissions_collections id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_permissions_collections ALTER COLUMN id SET DEFAULT nextval('public.sys_permissions_collections_id_seq'::regclass);


--
-- Name: sys_relation_permissions_collections_to_permissions id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_permissions_collections_to_permissions ALTER COLUMN id SET DEFAULT nextval('public.sys_relation_permissions_collections_to_permissions_id_seq'::regclass);


--
-- Name: sys_relation_permissions_collections_to_permissions_collections id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_permissions_collections_to_permissions_collections ALTER COLUMN id SET DEFAULT nextval('public.sys_relation_permissions_collections_to_permissions_coll_id_seq'::regclass);


--
-- Name: sys_relation_users_to_permissions id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_users_to_permissions ALTER COLUMN id SET DEFAULT nextval('public.sys_relation_users_to_permissions_id_seq'::regclass);


--
-- Name: sys_relation_users_to_permissions_collections id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_users_to_permissions_collections ALTER COLUMN id SET DEFAULT nextval('public.sys_relation_users_to_permissions_collections_id_seq'::regclass);


--
-- Name: sys_relation_users_tokens_to_tokens id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_users_tokens_to_tokens ALTER COLUMN id SET DEFAULT nextval('public.sys_relation_users_tokens_to_tokens_id_seq'::regclass);


--
-- Name: sys_status id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_status ALTER COLUMN id SET DEFAULT nextval('public.sys_status_id_seq'::regclass);


--
-- Name: sys_users id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_users ALTER COLUMN id SET DEFAULT nextval('public.sys_users_id_seq'::regclass);


--
-- Name: sys_users_tokens id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_users_tokens ALTER COLUMN id SET DEFAULT nextval('public.sys_users_tokens_id_seq'::regclass);


--
-- Name: users_options id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users_options ALTER COLUMN id SET DEFAULT nextval('public.users_options_id_seq'::regclass);


--
-- Name: abonents abonents_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.abonents
    ADD CONSTRAINT abonents_pkey PRIMARY KEY (id);


--
-- Name: contracts contracts_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.contracts
    ADD CONSTRAINT contracts_pkey PRIMARY KEY (id);


--
-- Name: migration migration_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (version);


--
-- Name: partners partners_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.partners
    ADD CONSTRAINT partners_pkey PRIMARY KEY (id);


--
-- Name: phones phones_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.phones
    ADD CONSTRAINT phones_pkey PRIMARY KEY (id);


--
-- Name: billing_journal pk_billing_journal; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.billing_journal
    ADD CONSTRAINT pk_billing_journal PRIMARY KEY (id);


--
-- Name: ticket pk_ticket_$id; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.ticket
    ADD CONSTRAINT "pk_ticket_$id" PRIMARY KEY (id);


--
-- Name: ticket_subscription pk_ticket_product_subscription_$id; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.ticket_subscription
    ADD CONSTRAINT "pk_ticket_product_subscription_$id" PRIMARY KEY (id);


--
-- Name: products_journal product_statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.products_journal
    ADD CONSTRAINT product_statuses_pkey PRIMARY KEY (id);


--
-- Name: products products_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: queue queue_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.queue
    ADD CONSTRAINT queue_pkey PRIMARY KEY (id);


--
-- Name: ref_partners_categories ref_partners_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.ref_partners_categories
    ADD CONSTRAINT ref_partners_categories_pkey PRIMARY KEY (id);


--
-- Name: relation_abonents_to_products relation_abonents_to_products_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_abonents_to_products
    ADD CONSTRAINT relation_abonents_to_products_pkey PRIMARY KEY (id);


--
-- Name: relation_contracts_to_products relation_contracts_to_products_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_contracts_to_products
    ADD CONSTRAINT relation_contracts_to_products_pkey PRIMARY KEY (id);


--
-- Name: relation_ticket_to_billing relation_ticket_to_billing_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_ticket_to_billing
    ADD CONSTRAINT relation_ticket_to_billing_pkey PRIMARY KEY (id);


--
-- Name: relation_users_to_phones relation_users_to_phones_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_users_to_phones
    ADD CONSTRAINT relation_users_to_phones_pkey PRIMARY KEY (id);


--
-- Name: revshare_rates revshare_rates_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.revshare_rates
    ADD CONSTRAINT revshare_rates_pkey PRIMARY KEY (id);


--
-- Name: subscriptions subscriptions_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.subscriptions
    ADD CONSTRAINT subscriptions_pkey PRIMARY KEY (id);


--
-- Name: sys_exceptions sys_exceptions_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_exceptions
    ADD CONSTRAINT sys_exceptions_pkey PRIMARY KEY (id);


--
-- Name: sys_file_storage sys_file_storage_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_file_storage
    ADD CONSTRAINT sys_file_storage_pkey PRIMARY KEY (id);


--
-- Name: sys_file_storage_tags sys_file_storage_tags_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_file_storage_tags
    ADD CONSTRAINT sys_file_storage_tags_pkey PRIMARY KEY (id);


--
-- Name: sys_history sys_history_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_history
    ADD CONSTRAINT sys_history_pkey PRIMARY KEY (id);


--
-- Name: sys_history_tags sys_history_tags_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_history_tags
    ADD CONSTRAINT sys_history_tags_pkey PRIMARY KEY (id);


--
-- Name: sys_import sys_import_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_import
    ADD CONSTRAINT sys_import_pkey PRIMARY KEY (id);


--
-- Name: sys_notifications sys_notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_notifications
    ADD CONSTRAINT sys_notifications_pkey PRIMARY KEY (id);


--
-- Name: sys_options sys_options_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_options
    ADD CONSTRAINT sys_options_pkey PRIMARY KEY (id);


--
-- Name: sys_permissions_collections sys_permissions_collections_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_permissions_collections
    ADD CONSTRAINT sys_permissions_collections_pkey PRIMARY KEY (id);


--
-- Name: sys_permissions sys_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_permissions
    ADD CONSTRAINT sys_permissions_pkey PRIMARY KEY (id);


--
-- Name: sys_relation_permissions_collections_to_permissions_collections sys_relation_permissions_collections_to_permissions_collec_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_permissions_collections_to_permissions_collections
    ADD CONSTRAINT sys_relation_permissions_collections_to_permissions_collec_pkey PRIMARY KEY (id);


--
-- Name: sys_relation_permissions_collections_to_permissions sys_relation_permissions_collections_to_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_permissions_collections_to_permissions
    ADD CONSTRAINT sys_relation_permissions_collections_to_permissions_pkey PRIMARY KEY (id);


--
-- Name: sys_relation_users_to_permissions_collections sys_relation_users_to_permissions_collections_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_users_to_permissions_collections
    ADD CONSTRAINT sys_relation_users_to_permissions_collections_pkey PRIMARY KEY (id);


--
-- Name: sys_relation_users_to_permissions sys_relation_users_to_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_users_to_permissions
    ADD CONSTRAINT sys_relation_users_to_permissions_pkey PRIMARY KEY (id);


--
-- Name: sys_relation_users_tokens_to_tokens sys_relation_users_tokens_to_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_users_tokens_to_tokens
    ADD CONSTRAINT sys_relation_users_tokens_to_tokens_pkey PRIMARY KEY (id);


--
-- Name: sys_status sys_status_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_status
    ADD CONSTRAINT sys_status_pkey PRIMARY KEY (id);


--
-- Name: sys_users sys_users_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_users
    ADD CONSTRAINT sys_users_pkey PRIMARY KEY (id);


--
-- Name: sys_users_tokens sys_users_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_users_tokens
    ADD CONSTRAINT sys_users_tokens_pkey PRIMARY KEY (id);


--
-- Name: users_options users_options_pkey; Type: CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.users_options
    ADD CONSTRAINT users_options_pkey PRIMARY KEY (id);


--
-- Name: _relation_users_to_permissions_collections_user_id_collection_i; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX _relation_users_to_permissions_collections_user_id_collection_i ON public.sys_relation_users_to_permissions_collections USING btree (user_id, collection_id);


--
-- Name: channel; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX channel ON public.queue USING btree (channel);


--
-- Name: daddy; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX daddy ON public.sys_status USING btree (daddy);


--
-- Name: delegate; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX delegate ON public.sys_history USING btree (delegate);


--
-- Name: domain; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX domain ON public.sys_import USING btree (domain);


--
-- Name: event; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX event ON public.sys_history USING btree (event);


--
-- Name: history_tag; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX history_tag ON public.sys_history_tags USING btree (history, tag);


--
-- Name: i_billing_journal_to_rel_abonents_to_products; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX i_billing_journal_to_rel_abonents_to_products ON public.billing_journal USING btree (rel_abonents_to_products_id);


--
-- Name: idx-abonents-deleted; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX "idx-abonents-deleted" ON public.abonents USING btree (deleted);


--
-- Name: idx-abonents-phone; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX "idx-abonents-phone" ON public.abonents USING btree (phone);


--
-- Name: idx-contracts-deleted; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX "idx-contracts-deleted" ON public.contracts USING btree (deleted);


--
-- Name: idx-contracts-numbers; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX "idx-contracts-numbers" ON public.contracts USING btree (contract_number, contract_number_nfs);


--
-- Name: idx-name-partner_id-type_id; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX "idx-name-partner_id-type_id" ON public.products USING btree (name, partner_id, type_id);


--
-- Name: idx-partners-deleted; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX "idx-partners-deleted" ON public.partners USING btree (deleted);


--
-- Name: idx-partners-inn; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX "idx-partners-inn" ON public.partners USING btree (inn);


--
-- Name: idx-partners-name; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX "idx-partners-name" ON public.partners USING btree (name);


--
-- Name: idx-partners-payment_period; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX "idx-partners-payment_period" ON public.products USING btree (payment_period);


--
-- Name: idx-products-deleted; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX "idx-products-deleted" ON public.products USING btree (deleted);


--
-- Name: in_revshare_$deleted; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX "in_revshare_$deleted" ON public.revshare_rates USING btree (deleted);


--
-- Name: model; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX model ON public.sys_import USING btree (model);


--
-- Name: model_class; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX model_class ON public.sys_history USING btree (model_class);


--
-- Name: model_class_model_key; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX model_class_model_key ON public.sys_history USING btree (model_class, model_key);


--
-- Name: model_key; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX model_key ON public.sys_history USING btree (model_key);


--
-- Name: model_name_model_key; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX model_name_model_key ON public.sys_status USING btree (model_name, model_key);


--
-- Name: operation_identifier; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX operation_identifier ON public.sys_history USING btree (operation_identifier);


--
-- Name: phones_deleted; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX phones_deleted ON public.phones USING btree (deleted);


--
-- Name: phones_phone; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX phones_phone ON public.phones USING btree (phone);


--
-- Name: phones_status; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX phones_status ON public.phones USING btree (status);


--
-- Name: priority; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX priority ON public.queue USING btree (priority);


--
-- Name: processed; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX processed ON public.sys_import USING btree (processed);


--
-- Name: ref_partners_categories_deleted; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX ref_partners_categories_deleted ON public.ref_partners_categories USING btree (deleted);


--
-- Name: ref_partners_categories_name; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX ref_partners_categories_name ON public.ref_partners_categories USING btree (name);


--
-- Name: relation_abonents_to_products_abonent_id_product_id; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX relation_abonents_to_products_abonent_id_product_id ON public.relation_abonents_to_products USING btree (abonent_id, product_id);


--
-- Name: relation_contracts_to_products_contract_id_product_id; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX relation_contracts_to_products_contract_id_product_id ON public.relation_contracts_to_products USING btree (contract_id, product_id);


--
-- Name: relation_model; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX relation_model ON public.sys_history USING btree (relation_model);


--
-- Name: relation_users_to_phones_user_id_phone_id; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX relation_users_to_phones_user_id_phone_id ON public.relation_users_to_phones USING btree (user_id, phone_id);


--
-- Name: reserved_at; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX reserved_at ON public.queue USING btree (reserved_at);


--
-- Name: rmissions_collections_to_permissions_collection_id_permission_i; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX rmissions_collections_to_permissions_collection_id_permission_i ON public.sys_relation_permissions_collections_to_permissions USING btree (collection_id, permission_id);


--
-- Name: ssions_collections_to_permissions_collections_master_id_slave_i; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX ssions_collections_to_permissions_collections_master_id_slave_i ON public.sys_relation_permissions_collections_to_permissions_collections USING btree (master_id, slave_id);


--
-- Name: status; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX status ON public.sys_status USING btree (status);


--
-- Name: sys_file_storage_daddy; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_file_storage_daddy ON public.sys_file_storage USING btree (daddy);


--
-- Name: sys_file_storage_deleted; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_file_storage_deleted ON public.sys_file_storage USING btree (deleted);


--
-- Name: sys_file_storage_model_name_model_key; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_file_storage_model_name_model_key ON public.sys_file_storage USING btree (model_name, model_key);


--
-- Name: sys_file_storage_path; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_file_storage_path ON public.sys_file_storage USING btree (path);


--
-- Name: sys_file_storage_tags_file_tag; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_file_storage_tags_file_tag ON public.sys_file_storage_tags USING btree (file, tag);


--
-- Name: sys_notifications_initiator; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_notifications_initiator ON public.sys_notifications USING btree (initiator);


--
-- Name: sys_notifications_object_id; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_notifications_object_id ON public.sys_notifications USING btree (object_id);


--
-- Name: sys_notifications_receiver; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_notifications_receiver ON public.sys_notifications USING btree (receiver);


--
-- Name: sys_notifications_type; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_notifications_type ON public.sys_notifications USING btree (type);


--
-- Name: sys_notifications_type_receiver_object_id; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_notifications_type_receiver_object_id ON public.sys_notifications USING btree (type, receiver, object_id);


--
-- Name: sys_options_option; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_options_option ON public.sys_options USING btree (option);


--
-- Name: sys_permissions_collections_default; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_permissions_collections_default ON public.sys_permissions_collections USING btree ("default");


--
-- Name: sys_permissions_collections_name; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_permissions_collections_name ON public.sys_permissions_collections USING btree (name);


--
-- Name: sys_permissions_controller_action_verb; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_permissions_controller_action_verb ON public.sys_permissions USING btree (controller, action, verb);


--
-- Name: sys_permissions_module; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_permissions_module ON public.sys_permissions USING btree (module);


--
-- Name: sys_permissions_name; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_permissions_name ON public.sys_permissions USING btree (name);


--
-- Name: sys_permissions_priority; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_permissions_priority ON public.sys_permissions USING btree (priority);


--
-- Name: sys_relation_users_to_permissions_user_id_permission_id; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_relation_users_to_permissions_user_id_permission_id ON public.sys_relation_users_to_permissions USING btree (user_id, permission_id);


--
-- Name: sys_relation_users_tokens_to_tokens_parent_id_child_id; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_relation_users_tokens_to_tokens_parent_id_child_id ON public.sys_relation_users_tokens_to_tokens USING btree (parent_id, child_id);


--
-- Name: sys_users_daddy; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_users_daddy ON public.sys_users USING btree (daddy);


--
-- Name: sys_users_deleted; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_users_deleted ON public.sys_users USING btree (deleted);


--
-- Name: sys_users_email; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_users_email ON public.sys_users USING btree (email);


--
-- Name: sys_users_is_pwd_outdated; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX sys_users_is_pwd_outdated ON public.sys_users USING btree (is_pwd_outdated);


--
-- Name: sys_users_login; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_users_login ON public.sys_users USING btree (login);


--
-- Name: sys_users_tokens_user_id_auth_token; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX sys_users_tokens_user_id_auth_token ON public.sys_users_tokens USING btree (user_id, auth_token);


--
-- Name: user; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX "user" ON public.sys_history USING btree ("user");


--
-- Name: users_options_user_id; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX users_options_user_id ON public.users_options USING btree (user_id);


--
-- Name: users_options_user_id_option; Type: INDEX; Schema: public; Owner: root
--

CREATE UNIQUE INDEX users_options_user_id_option ON public.users_options USING btree (user_id, option);


--
-- Name: abonents update_updated_at; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_updated_at BEFORE UPDATE ON public.abonents FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: contracts update_updated_at; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_updated_at BEFORE UPDATE ON public.contracts FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: partners update_updated_at; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_updated_at BEFORE UPDATE ON public.partners FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: products update_updated_at; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_updated_at BEFORE UPDATE ON public.products FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: subscriptions update_updated_at; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_updated_at BEFORE UPDATE ON public.subscriptions FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: partners fk-partners-category_id; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.partners
    ADD CONSTRAINT "fk-partners-category_id" FOREIGN KEY (category_id) REFERENCES public.ref_partners_categories(id) ON DELETE CASCADE;


--
-- Name: products fk-products-partner_id; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT "fk-products-partner_id" FOREIGN KEY (partner_id) REFERENCES public.partners(id) ON DELETE CASCADE;


--
-- Name: products fk-products-user_id; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT "fk-products-user_id" FOREIGN KEY (user_id) REFERENCES public.sys_users(id) ON DELETE CASCADE;


--
-- Name: subscriptions fk-subscriptions-product_id; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.subscriptions
    ADD CONSTRAINT "fk-subscriptions-product_id" FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: products_journal fk_ps_to_rel_abonents_to_products; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.products_journal
    ADD CONSTRAINT fk_ps_to_rel_abonents_to_products FOREIGN KEY (rel_abonents_to_products_id) REFERENCES public.relation_abonents_to_products(id);


--
-- Name: relation_abonents_to_products fk_ratp_to_abonents; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_abonents_to_products
    ADD CONSTRAINT fk_ratp_to_abonents FOREIGN KEY (abonent_id) REFERENCES public.abonents(id);


--
-- Name: relation_abonents_to_products fk_ratp_to_products; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_abonents_to_products
    ADD CONSTRAINT fk_ratp_to_products FOREIGN KEY (product_id) REFERENCES public.products(id);


--
-- Name: sys_relation_users_tokens_to_tokens fk_rel_tokens_to_child_token; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_users_tokens_to_tokens
    ADD CONSTRAINT fk_rel_tokens_to_child_token FOREIGN KEY (child_id) REFERENCES public.sys_users_tokens(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: sys_relation_users_tokens_to_tokens fk_rel_tokens_to_parent_token; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.sys_relation_users_tokens_to_tokens
    ADD CONSTRAINT fk_rel_tokens_to_parent_token FOREIGN KEY (parent_id) REFERENCES public.sys_users_tokens(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: relation_ticket_to_billing fk_relation_ticket_to_billing_to_billing_journal; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_ticket_to_billing
    ADD CONSTRAINT fk_relation_ticket_to_billing_to_billing_journal FOREIGN KEY (billing_id) REFERENCES public.billing_journal(id);


--
-- Name: relation_ticket_to_billing fk_relation_ticket_to_billing_to_ticket; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.relation_ticket_to_billing
    ADD CONSTRAINT fk_relation_ticket_to_billing_to_ticket FOREIGN KEY (ticket_id) REFERENCES public.ticket(id);


--
-- Name: revshare_rates fk_revshare_$product_id; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.revshare_rates
    ADD CONSTRAINT "fk_revshare_$product_id" FOREIGN KEY (product_id) REFERENCES public.products(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: ticket_subscription fk_ticket_product_subscription_to_abonents_products; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.ticket_subscription
    ADD CONSTRAINT fk_ticket_product_subscription_to_abonents_products FOREIGN KEY (rel_abonents_to_products_id) REFERENCES public.relation_abonents_to_products(id);


--
-- Name: ticket_subscription fk_ticket_product_subscription_to_ticket; Type: FK CONSTRAINT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.ticket_subscription
    ADD CONSTRAINT fk_ticket_product_subscription_to_ticket FOREIGN KEY (id) REFERENCES public.ticket(id);


--
-- PostgreSQL database dump complete
--

