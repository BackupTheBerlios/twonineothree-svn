--
-- PostgreSQL database dump
--

SET client_encoding = 'SQL_ASCII';
SET check_function_bodies = false;

SET SESSION AUTHORIZATION 'postgres';

--
-- TOC entry 4 (OID 2200)
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;


SET SESSION AUTHORIZATION 'ulrik';

SET search_path = public, pg_catalog;

--
-- TOC entry 5 (OID 17144)
-- Name: seq_users; Type: SEQUENCE; Schema: public; Owner: ulrik
--

CREATE SEQUENCE seq_users
    START WITH 100
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 7 (OID 17146)
-- Name: seq_groups; Type: SEQUENCE; Schema: public; Owner: ulrik
--

CREATE SEQUENCE seq_groups
    START WITH 100
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 9 (OID 17148)
-- Name: seq_pages; Type: SEQUENCE; Schema: public; Owner: ulrik
--

CREATE SEQUENCE seq_pages
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 11 (OID 17150)
-- Name: seq_boxes; Type: SEQUENCE; Schema: public; Owner: ulrik
--

CREATE SEQUENCE seq_boxes
    START WITH 1000
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 13 (OID 17152)
-- Name: seq_content; Type: SEQUENCE; Schema: public; Owner: ulrik
--

CREATE SEQUENCE seq_content
    START WITH 2000
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 15 (OID 17154)
-- Name: seq_layout; Type: SEQUENCE; Schema: public; Owner: ulrik
--

CREATE SEQUENCE seq_layout
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 17 (OID 17156)
-- Name: broend_pages; Type: TABLE; Schema: public; Owner: ulrik
--

CREATE TABLE broend_pages (
    id integer DEFAULT nextval('seq_pages'::text) NOT NULL,
    name character varying(128) NOT NULL,
    cdate bigint NOT NULL,
    mdate bigint NOT NULL,
    "owner" integer,
    ogroup integer,
    rights smallint,
    hidden boolean DEFAULT false,
    publishdate bigint,
    title character varying(256),
    description character varying(256),
    contributors character varying(512),
    publisher character varying(128),
    "language" character varying(2),
    coverage character varying(64),
    copyright character varying(64),
    stylesheet character varying(128),
    boxes character varying(512),
    layout character varying(128)
);


--
-- TOC entry 18 (OID 17165)
-- Name: broend_boxes; Type: TABLE; Schema: public; Owner: ulrik
--

CREATE TABLE broend_boxes (
    id integer DEFAULT nextval('seq_boxes'::text) NOT NULL,
    name character varying(128) NOT NULL,
    cdate bigint NOT NULL,
    mdate bigint NOT NULL,
    "owner" integer,
    ogroup integer,
    rights smallint,
    hidden boolean DEFAULT false,
    stylesheet character varying(256),
    content text
);


--
-- TOC entry 19 (OID 17176)
-- Name: broend_layout; Type: TABLE; Schema: public; Owner: ulrik
--

CREATE TABLE broend_layout (
    id integer DEFAULT nextval('seq_layout'::text) NOT NULL,
    name character varying(128) NOT NULL,
    cdate bigint NOT NULL,
    mdate bigint NOT NULL,
    "owner" integer,
    ogroup integer,
    rights smallint,
    hidden boolean DEFAULT false,
    content text
);


--
-- TOC entry 20 (OID 17185)
-- Name: broend_users; Type: TABLE; Schema: public; Owner: ulrik
--

CREATE TABLE broend_users (
    id integer DEFAULT nextval('seq_users'::text) NOT NULL,
    state smallint DEFAULT 512,
    realname character varying(128),
    email character varying(128),
    additionals text
);


--
-- TOC entry 21 (OID 17194)
-- Name: broend_groups; Type: TABLE; Schema: public; Owner: ulrik
--

CREATE TABLE broend_groups (
    id integer DEFAULT nextval('seq_groups'::text) NOT NULL,
    name character varying(128)
);


--
-- TOC entry 22 (OID 17200)
-- Name: broend_stylesheets; Type: TABLE; Schema: public; Owner: ulrik
--

CREATE TABLE broend_stylesheets (
    name character varying(128) NOT NULL,
    content text
);


--
-- Data for TOC entry 30 (OID 17156)
-- Name: broend_pages; Type: TABLE DATA; Schema: public; Owner: ulrik
--

INSERT INTO broend_pages VALUES (500, 'home', 237891273, 123798127, 0, 0, 700, false, 1273817997, '29o3 rocks!', 'This document describes that 29o3 simply *ROCKS*', 'Gummersbach, Ronny;Hesse, Markus', 'Ulrik G&#252;nther', 'DE', '', '(c) 2004 by Ulrik G&#252;nther', 'global', '1,2,23,1,13,12', 'mainlayout');


--
-- Data for TOC entry 31 (OID 17165)
-- Name: broend_boxes; Type: TABLE DATA; Schema: public; Owner: ulrik
--



--
-- Data for TOC entry 32 (OID 17176)
-- Name: broend_layout; Type: TABLE DATA; Schema: public; Owner: ulrik
--

INSERT INTO broend_layout VALUES (100, 'mainlayout', 1098454527, 1098454527, 0, 0, 600, false, '/* multiline
comment
over
some lines */
// Layout example for 29o3
// lines starting with ''//'' will be skipped during processing
// anything starting with ''::29o3.'' will be evaluated as command for the
// layout processor
// blocks are in this format [blockname] { [content] }
// allowed blocks are header and layout

header {
::29o3.mainStylesheet("GreenStripeLayout", "internal");
::29o3.auxiliaryStylesheet("testing_01");
::29o3.testFunc("test", "1");
}

layout {
<div class="stripediv">
	<div class="greenstripe">
	&nbsp;
	</div>
	<div class="textunderstripe">
	<strong>::29o3.getBox("Title");</strong><br/>
	::29o3.getBox("Content");
	<div style="text-align: right; font-style: italic;">::29o3.getProperty("Author");</div>
	<br/>
	<div class="menu">::29o3.getStaticBox("Menu001");</div>
	<div style="font-size: 9px; color: #CCCCCC; text-align: center;">
	<a href="http://validator.w3.org/check/referer">valid xhtml</a> &middot; 
	<a href="http://jigsaw.w3.org/css-validator/check/referer">valid css</a>
	</div>
	</div>
</div>
}');


--
-- Data for TOC entry 33 (OID 17185)
-- Name: broend_users; Type: TABLE DATA; Schema: public; Owner: ulrik
--



--
-- Data for TOC entry 34 (OID 17194)
-- Name: broend_groups; Type: TABLE DATA; Schema: public; Owner: ulrik
--



--
-- Data for TOC entry 35 (OID 17200)
-- Name: broend_stylesheets; Type: TABLE DATA; Schema: public; Owner: ulrik
--

INSERT INTO broend_stylesheets VALUES ('home', '.body {
   font-family: helvetica, sans-serif;
   font-size: 11px;
   color: black;
   background-color: white;
}');
INSERT INTO broend_stylesheets VALUES ('GreenStripeLayout', '/* 00t.org stylesheet */
body {
	background-color: white;
	font-family: sans-serif, arial, helvetica;
	font-size: 11px;
	color: black;
	margin: 0px;
	padding: 0px;
}

.stripediv {
	position: absolute;
	top: 30%;
	left: 0px;
	right: 0px;
	width: 100%;
	font-family: sans-serif, arial, helvetica;
	line-height: 130%;
	text-align: center;
}

.greenstripe {
	text-align: center;
	background: url(images/00tlogo.jpg) center top no-repeat;
	width: 100%;
	height: 90px;
	background-color: #c9ff25;
}

.textunderstripe {
	text-align: left;
	background-color: white;
	color: #666666;
	position: relative;
	top: 10px;
	left: 33%;
	width: 33%;
	line-height: 150%;
	font-family: sans-serif, arial, helvetica;
	font-size: 11px;
}

.textunderstripe a:link, .textunderstripe a:active, .textunderstripe a:visited {
	color: #666666;
	text-decoration: none;
	font-weight: bold;
}

.textunderstripe a:hover {
	text-decoration: none;
	font-weight: bold;
	border-bottom: 1px dotted #666666;
}
');


--
-- TOC entry 23 (OID 17163)
-- Name: broend_pages_pkey; Type: CONSTRAINT; Schema: public; Owner: ulrik
--

ALTER TABLE ONLY broend_pages
    ADD CONSTRAINT broend_pages_pkey PRIMARY KEY (id);


--
-- TOC entry 25 (OID 17172)
-- Name: broend_boxes_pkey; Type: CONSTRAINT; Schema: public; Owner: ulrik
--

ALTER TABLE ONLY broend_boxes
    ADD CONSTRAINT broend_boxes_pkey PRIMARY KEY (id);


--
-- TOC entry 24 (OID 17174)
-- Name: broend_boxes_name_key; Type: CONSTRAINT; Schema: public; Owner: ulrik
--

ALTER TABLE ONLY broend_boxes
    ADD CONSTRAINT broend_boxes_name_key UNIQUE (name);


--
-- TOC entry 26 (OID 17183)
-- Name: broend_layout_pkey; Type: CONSTRAINT; Schema: public; Owner: ulrik
--

ALTER TABLE ONLY broend_layout
    ADD CONSTRAINT broend_layout_pkey PRIMARY KEY (id);


--
-- TOC entry 27 (OID 17192)
-- Name: broend_users_pkey; Type: CONSTRAINT; Schema: public; Owner: ulrik
--

ALTER TABLE ONLY broend_users
    ADD CONSTRAINT broend_users_pkey PRIMARY KEY (id);


--
-- TOC entry 28 (OID 17197)
-- Name: broend_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: ulrik
--

ALTER TABLE ONLY broend_groups
    ADD CONSTRAINT broend_groups_pkey PRIMARY KEY (id);


--
-- TOC entry 29 (OID 17205)
-- Name: broend_stylesheets_pkey; Type: CONSTRAINT; Schema: public; Owner: ulrik
--

ALTER TABLE ONLY broend_stylesheets
    ADD CONSTRAINT broend_stylesheets_pkey PRIMARY KEY (name);


--
-- TOC entry 6 (OID 17144)
-- Name: seq_users; Type: SEQUENCE SET; Schema: public; Owner: ulrik
--

SELECT pg_catalog.setval('seq_users', 100, false);


--
-- TOC entry 8 (OID 17146)
-- Name: seq_groups; Type: SEQUENCE SET; Schema: public; Owner: ulrik
--

SELECT pg_catalog.setval('seq_groups', 100, false);


--
-- TOC entry 10 (OID 17148)
-- Name: seq_pages; Type: SEQUENCE SET; Schema: public; Owner: ulrik
--

SELECT pg_catalog.setval('seq_pages', 500, true);


--
-- TOC entry 12 (OID 17150)
-- Name: seq_boxes; Type: SEQUENCE SET; Schema: public; Owner: ulrik
--

SELECT pg_catalog.setval('seq_boxes', 1000, false);


--
-- TOC entry 14 (OID 17152)
-- Name: seq_content; Type: SEQUENCE SET; Schema: public; Owner: ulrik
--

SELECT pg_catalog.setval('seq_content', 2000, false);


--
-- TOC entry 16 (OID 17154)
-- Name: seq_layout; Type: SEQUENCE SET; Schema: public; Owner: ulrik
--

SELECT pg_catalog.setval('seq_layout', 100, true);


SET SESSION AUTHORIZATION 'postgres';

--
-- TOC entry 3 (OID 2200)
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'Standard public schema';


