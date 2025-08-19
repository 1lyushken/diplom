--
-- PostgreSQL database dump
--

-- Dumped from database version 17.4
-- Dumped by pg_dump version 17.4

-- Started on 2025-05-27 12:05:24

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 218 (class 1259 OID 16463)
-- Name: root_causes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.root_causes (
    id integer NOT NULL,
    job character varying(255),
    equipment character varying(255),
    issue text,
    workshop character varying(255),
    root_cause text
);


ALTER TABLE public.root_causes OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 16462)
-- Name: root_causes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.root_causes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.root_causes_id_seq OWNER TO postgres;

--
-- TOC entry 4910 (class 0 OID 0)
-- Dependencies: 217
-- Name: root_causes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.root_causes_id_seq OWNED BY public.root_causes.id;


--
-- TOC entry 220 (class 1259 OID 16472)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(255) DEFAULT 'user'::character varying
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16471)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 4911 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4747 (class 2604 OID 16466)
-- Name: root_causes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.root_causes ALTER COLUMN id SET DEFAULT nextval('public.root_causes_id_seq'::regclass);


--
-- TOC entry 4748 (class 2604 OID 16475)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4902 (class 0 OID 16463)
-- Dependencies: 218
-- Data for Name: root_causes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.root_causes (id, job, equipment, issue, workshop, root_cause) FROM stdin;
\.


--
-- TOC entry 4904 (class 0 OID 16472)
-- Dependencies: 220
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, username, password, role) FROM stdin;
3	Dimon	1234	user
1	admin	admin	admin
\.


--
-- TOC entry 4912 (class 0 OID 0)
-- Dependencies: 217
-- Name: root_causes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.root_causes_id_seq', 1, false);


--
-- TOC entry 4913 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 9, true);


--
-- TOC entry 4751 (class 2606 OID 16470)
-- Name: root_causes root_causes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.root_causes
    ADD CONSTRAINT root_causes_pkey PRIMARY KEY (id);


--
-- TOC entry 4753 (class 2606 OID 16479)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4755 (class 2606 OID 16481)
-- Name: users users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);


-- Completed on 2025-05-27 12:05:24

--
-- PostgreSQL database dump complete
--

