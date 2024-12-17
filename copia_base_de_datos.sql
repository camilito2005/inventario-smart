--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.25
-- Dumped by pg_dump version 9.5.25

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

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
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorias (
    categoria_id integer NOT NULL,
    nombre character varying(255) NOT NULL
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- Name: categorias_categoria_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categorias_categoria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categorias_categoria_id_seq OWNER TO postgres;

--
-- Name: categorias_categoria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorias_categoria_id_seq OWNED BY public.categorias.categoria_id;


--
-- Name: dispositivos; Type: TABLE; Schema: public; Owner: postgres
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
    dispositivo_direccion_mac character varying NOT NULL,
    observacion character varying,
    categoria_id integer,
    "dispositivo_contraseña" text
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
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
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
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
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
-- Name: categoria_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias ALTER COLUMN categoria_id SET DEFAULT nextval('public.categorias_categoria_id_seq'::regclass);


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
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categorias (categoria_id, nombre) FROM stdin;
1	Portátil
2	Computador de Mesa
\.


--
-- Name: categorias_categoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_categoria_id_seq', 4, true);


--
-- Data for Name: dispositivos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dispositivos (dispositivo_id, dispositivo_marca, dispositivo_modelo, dispositivo_ram, dispositivo_procesador, dispositivo_almacenamiento, dispositivo_perifericos, dispositivo_nombre_usuario, fecha_registro, fecha_modificacion, dispositivo_direccion_mac, observacion, categoria_id, "dispositivo_contraseña") FROM stdin;
9	Apple	Imac	16 GB	Intel core i5 	240 GB	teclado y mouse 	Diana	2024-12-16 15:38:11	\N	68:e0:eb:53:8a:b5	\N	2	\N
13	Apple	Imac	8 GB	Intel core i5	240 GB	mouse y teclado	raysa	2024-12-16 15:47:40	\N	28:f0:76:4C:17:60	\N	2	\N
28	Dell	computador de mesa 	20 GB	Intel core i3	222 GB	mouse y teclado 	Yalith	2024-12-16 16:31:40	\N	192:168:88:55	\N	2	\N
5	apple	Mackbook pro	16 GB	intel core i5	500 GB	cargador y mouse(genius)	sebastian	2024-12-15 11:57:39	2024-12-16 15:20:34	90:fd:51:c8:84:e8	\N	1	\N
7	Apple	Mackbook pro	16 GB	Intel core i5	250 GB	mouse (genius) teclado (trust)	Duvan	2024-12-16 15:27:27	\N	fe80:534b:4e1C:8388:4080	\N	1	\N
22	Apple	Mackbook pro	8 GB	Intel core i5	740 GB	mouse y cargador 	Lina	2024-12-16 16:19:45	\N	24:f0:94:e5:af:fc	\N	1	\N
25	Apple	Mackbook pro	16 GB	intel core i7	1 TR	cargador	brayan 1 - sierra	2024-12-16 16:24:21	\N	34:36:3b:d2:b8:a6	\N	1	\N
27	Lenovo	idepad s145	8 GB	intel core i7	475 GB	mouse y cargador	Adriana	2024-12-16 16:29:24	\N	28-39-26-87-63-87	\N	1	\N
29	Dell	 PENDIENTE	6 GB	Intel core i3	500 GB	mouse	Alys	2024-12-16 16:33:51	\N	64-27-37-E5-5a-87	\N	1	\N
30	PENDIENTE	PENDIENTE	PENDIENTE	PENDIENTE	PENDIENTE	PENDIENTE	FALTA JHOA	2024-12-16 16:34:26	\N	PENDIENTE	\N	2	\N
31	PENDIENTE	PENDIENTE	PENDIENTE	PENDIENTE	PENDIENTE	PENDIENTE	FALTA LESLY	2024-12-16 16:34:56	\N	PENDIENTE	\N	2	\N
32	prueba	prueba	prueba	prueba	prueba	prueba	prueba	2024-12-16 20:27:52	\N	prueba	prueba	\N	prueba
3	apple	imac	8 GB	intel core i5	500 GB	mouse y teclado marca dell	camilo	2024-12-14 19:46:00	2024-12-17 15:29:29	82:16:00:CF:02:00		2	smartinfo
6	apple	mackbook pro	8 GB	intel core i5	250 GB	cargador	Gustavo	2024-12-16 15:25:04	2024-12-17 15:29:47	8c:85:90:OC:51:fa		1	smartinfo
8	Apple	imac	16 GB	intel core i5	1 TR 	mouse teclado	Anyie sarmiento	2024-12-16 15:33:02	2024-12-17 15:30:24	82:69:4a:C3:50:00		2	Artes2
10	Apple	Imac	16 GB	Intel core i5 	1 TR	teclado-mouse	anyie - chiqui	2024-12-16 15:41:57	2024-12-17 15:30:46	64:18:d1:f0:e7:fa		2	artes
11	Apple	Imac	8 GB	Intel core i5	500 GB	mouse teclado	Jhon	2024-12-16 15:43:27	2024-12-17 15:31:31	PENDIENTE		2	qwerty
12	Apple	Imac	8 GB	Intel core i5	240 GB	mouse - teclado 	Martha 	2024-12-16 15:46:04	2024-12-17 15:31:47	b8:09:8a:b8:11:71		2	smartinfo
14	Apple	Mackbook pro	8 GB	Intel core i5	1 TR	mouse y cargador	maria jose 	2024-12-16 15:51:19	2024-12-17 15:51:28	80:e6:50:1e:3c:d8		1	Trafico2k18.
16	Apple	Imac	12 GB	Intel core i5	240 GB	mouse y teclado- observacion pendiente 	Samantha	2024-12-16 16:02:01	2024-12-17 15:51:51	04:54:53:05:fa:32	se desconecta del internet y toca reiniciarlo, cuando pasa pasa mas de una vez en el dia 	2	smartinfo
17	Apple	Mackbook pro	32 GB	Intel core i8	1 TR	mouse y cargador 	Ivan	2024-12-16 16:07:36	2024-12-17 15:52:08	88:66:5a:35:dc:27		1	12345
18	Apple	Mackbook pro	16 GB	intel core i7	250 GB	mouse y teclado	Gabriel	2024-12-16 16:10:17	2024-12-17 15:52:29	80:e6:50:25:52:e4		1	qwerty
19	Samsung	Armado 	24 GB	AMD ryzen 7 5700g	500 GB	mouse y teclado 	Michael	2024-12-16 16:12:29	2024-12-17 15:53:02	192:168:88:72		2	1505
20	Lg	Pc armado	24 GB 	AMD ryzen 7 5700g	480 GB	mouse y teclado	Luis mendez 	2024-12-16 16:15:07	2024-12-17 15:53:31	04:7C:16:6c:88:5c		2	NADA, SIN CONTRASEÑA
21	Apple	Imac	16 GB 	Intel core i5	1 TR	mouse y teclado 	Migue	2024-12-16 16:17:44	2024-12-17 15:53:46	68:fe:f7:04:05:87		2	artes
23	Dell	Pc de escritorio armado	16 GB	Intel core i3	222 GB	mouse y teclado	Jesus	2024-12-16 16:21:31	2024-12-17 15:54:05	78-45-C4-2d-4d-86		2	jesus1224
24	Apple	Mackbook pro	8 GB	intel core i7	250 GB	teclado	Brayan 2 	2024-12-16 16:23:03	2024-12-17 16:50:53	02:42:df:48:82:87		1	brayan2k21
26	LG	Pc de escritorio armado	16 GB	AMD ryzen 7 5700g	480 GB	NADA 	Andres	2024-12-16 16:26:26	2024-12-17 16:51:21	00-41-0E-3c-85-AF		2	smart2k23.
\.


--
-- Name: dispositivos_dispositivos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dispositivos_dispositivos_id_seq', 33, true);


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
4	999999	prueba	prueba	300	prueba	prueba@gmail.com	prueba	2024-12-16	2	2024-12-16 13:52:08.596757
5	104329	CAMILO-Prueba	marrugo	0	la boquilla	camilo@gmail.com	camilo	2024-12-17	2	2024-12-17 10:22:28.713545
\.


--
-- Name: usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuarios_id_seq', 5, true);


--
-- Name: categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (categoria_id);


--
-- Name: dispositivos_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dispositivos
    ADD CONSTRAINT dispositivos_pk PRIMARY KEY (dispositivo_id);


--
-- Name: roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: usuarios_correo_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_correo_key UNIQUE (correo);


--
-- Name: usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: fk_rol; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT fk_rol FOREIGN KEY (rol_id) REFERENCES public.roles(id);


--
-- Name: fk_rol_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT fk_rol_id FOREIGN KEY (rol_id) REFERENCES public.roles(id);


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

