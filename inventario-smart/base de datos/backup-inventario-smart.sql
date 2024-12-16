--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: dispositivos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.dispositivos (
    dispositivo_id integer NOT NULL,
    dispositivo_marca character varying NOT NULL,
    dispositivo_modelo character varying NOT NULL,
    dispositivo_ram character varying NOT NULL,
    dispositivo_procesador character varying NOT NULL,
    dispositivo_almacenamiento character varying NOT NULL,
    dispositivo_perifericos character varying NOT NULL,
    dispositivo_nombre_usuario character varying NOT NULL,
    fecha_registro timestamp without time zone NOT NULL,
    fecha_modificacion timestamp without time zone,
    dispositivo_direccion_mac character varying NOT NULL
);


ALTER TABLE public.dispositivos OWNER TO postgres;

--
-- Name: dispositivos_dispositivos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dispositivos_dispositivos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.dispositivos_dispositivos_id_seq OWNER TO postgres;

--
-- Name: dispositivos_dispositivos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dispositivos_dispositivos_id_seq OWNED BY public.dispositivos.dispositivo_id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    descripcion character varying(255) NOT NULL
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.roles_id_seq OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.usuarios (
    id integer NOT NULL,
    dni text NOT NULL,
    nombre text NOT NULL,
    apellido text NOT NULL,
    telefono bigint NOT NULL,
    direccion text NOT NULL,
    correo text NOT NULL,
    "contraseña" text NOT NULL,
    fecha_ingreso date,
    rol_id integer NOT NULL,
    fecha_actualizacion timestamp without time zone DEFAULT now()
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- Name: usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuarios_id_seq OWNER TO postgres;

--
-- Name: usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;


--
-- Name: dispositivo_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dispositivos ALTER COLUMN dispositivo_id SET DEFAULT nextval('public.dispositivos_dispositivos_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);


--
-- Data for Name: dispositivos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dispositivos (dispositivo_id, dispositivo_marca, dispositivo_modelo, dispositivo_ram, dispositivo_procesador, dispositivo_almacenamiento, dispositivo_perifericos, dispositivo_nombre_usuario, fecha_registro, fecha_modificacion, dispositivo_direccion_mac) FROM stdin;
3	apple	imac	16 GB	intel core i5	500 GB	mouse y teclado marca dell	camilo	2024-12-14 19:46:00	2024-12-15 11:20:38	10:20:30:40:50
5	apple	Mackbook pro	16 GB	intel core i5	250 GB	cargador y mouse	sebastian	2024-12-15 11:57:39	\N	10:20:30:40:50:60
\.


--
-- Name: dispositivos_dispositivos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dispositivos_dispositivos_id_seq', 5, true);


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, descripcion) FROM stdin;
1	administrador
2	usuario
\.


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 2, true);


--
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuarios (id, dni, nombre, apellido, telefono, direccion, correo, "contraseña", fecha_ingreso, rol_id, fecha_actualizacion) FROM stdin;
2	300000	CAMILO	marrugo 	300000	la boquilla	c@gmail.com	123456	2024-12-15	1	2024-12-15 22:01:05.808
\.


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 3, true);


--
-- Name: dispositivos_pk; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.dispositivos
    ADD CONSTRAINT dispositivos_pk PRIMARY KEY (dispositivo_id);


--
-- Name: roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: usuarios_correo_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_correo_key UNIQUE (correo);


--
-- Name: usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: fk_rol; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT fk_rol FOREIGN KEY (rol_id) REFERENCES public.roles(id);


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

