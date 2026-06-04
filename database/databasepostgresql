-- PostgreSQL dump convertido de MySQL/MariaDB
-- Banco de dados: entomologia

SET client_encoding = 'UTF8';

START TRANSACTION;

-- --------------------------------------------------------
-- Tabela: admins
-- --------------------------------------------------------

CREATE TABLE admins (
  id SERIAL PRIMARY KEY,
  nome varchar(100) NOT NULL,
  email varchar(150) NOT NULL UNIQUE,
  senha varchar(255) NOT NULL,
  criado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (id, nome, email, senha, criado_em) VALUES
(7, 'renna', 'renna@gmail.com', '$2y$10$4fgrWFvb1wN/eYMK/BqxH.g5yOp0KRYTkhkf6.Qre0YRfjpm236ny', '2026-04-14 23:40:38'),
(8, 'professor', 'professor@gmail.com', '$2y$10$ZawlbOQXR3wU8OTYwlBZNec2TD7C0g5A.Tr3Uew3VbNKqFRKRmYt6', '2026-04-15 17:17:26');

SELECT setval('admins_id_seq', (SELECT MAX(id) FROM admins));

-- --------------------------------------------------------
-- Tabela: ordens
-- --------------------------------------------------------

CREATE TABLE ordens (
  id SERIAL PRIMARY KEY,
  nome varchar(100) NOT NULL,
  descricao text DEFAULT NULL,
  caracteristicas text DEFAULT NULL,
  exemplos varchar(255) DEFAULT NULL,
  importancia_agricola text DEFAULT NULL,
  imagem varchar(255) DEFAULT NULL,
  ativo boolean DEFAULT TRUE,
  ordem_exibicao integer DEFAULT 0,
  criado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO ordens (id, nome, descricao, caracteristicas, exemplos, importancia_agricola, imagem, ativo, ordem_exibicao, criado_em, atualizado_em) VALUES
(1, 'Hemiptera-Auchenorrhyncha', 'Subordem de Hemiptera com antenas curtas com filamento apical e rostro originando-se da parte posterior da cabeça.', '["Peças bucais picadoras-sugadoras","Rostro com origem na parte posterior da cabeça","Antenas curtas e setáceas","Asas anteriores uniformes em textura"]', 'Cigarras, cigarrinhas', 'Importantes vetores de doenças em plantas agrícolas. Sugam seiva do floema e xilema.', '', TRUE, 1, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(2, 'Hemiptera-Sternorrhyncha', 'Subordem de Hemiptera com antenas longas ou curtas sem filamento apical e rostro surgindo entre as coxas anteriores.', '["Rostro surgindo entre as coxas anteriores","Antenas longas ou curtas sem filamento apical","Corpo frequentemente coberto por secreções"]', 'Pulgões, moscas-brancas, cochonilhas, psilídeos', 'Pragas de grande importância econômica. Causam danos diretos por sucção de seiva e indiretos como vetores de vírus.', '', TRUE, 2, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(3, 'Hemiptera-Heteroptera', 'Subordem de Hemiptera com hemiélitros e grande diversidade de hábitos.', '["Hemiélitros com parte basal coriácea e parte apical membranosa","Glândulas odoríferas metatorácicas","Metamorfose hemimetabólica"]', 'Percevejos, barbeiros, baratas-d''água, marigosas', 'Inclui pragas agrícolas importantes como percevejos da soja, além de predadores benéficos.', '', TRUE, 3, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(4, 'Orthoptera', 'Ordem com pernas posteriores saltatórias, antenas filiformes ou setáceas.', '["Pernas posteriores saltatórias","Metamorfose hemimetabólica","Tegminas coriáceas","Cercos presentes"]', 'Gafanhotos, grilos, esperanças, paquinhas', 'Gafanhotos podem causar devastação em lavouras. Grilos atacam plântulas e raízes.', '', TRUE, 4, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(5, 'Odonata', 'Ordem de insetos com dois pares de asas membranosas e olhos compostos grandes.', '["Dois pares de asas membranosas reticuladas","Olhos compostos muito desenvolvidos","Abdome longo e delgado","Metamorfose hemimetabólica aquática"]', 'Libélulas, donzelinhas', 'Predadores de outros insetos, incluindo pragas agrícolas. As larvas são aquáticas.', '', TRUE, 5, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(6, 'Dermaptera', 'Ordem conhecida pelas tesourinhas com cercos quitinosos em forma de pinça.', '["Cercos quitinosos em forma de pinça","Élitros curtos e coriáceos","Abdome flexível","Metamorfose hemimetabólica"]', 'Tesourinhas, bicho-tesourinha', 'Algumas espécies são predadoras de pragas; outras podem atacar plantas e frutas.', '', TRUE, 6, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(7, 'Isoptera', 'Ordem dos cupins, insetos sociais com colônias organizadas.', '["Insetos sociais com castas","Antenas moniliformes","Metamorfose hemimetabólica","Asas iguais nos alados"]', 'Cupins, siri-siris', 'Causam enormes prejuízos em madeiras, pastagens, cana-de-açúcar e outras culturas.', '', TRUE, 7, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(8, 'Neuroptera', 'Ordem com asas reticuladas e antenas longas.', '["Asas com aspecto reticulado","Antenas longas e bem visíveis","Metamorfose holometabólica","Larvas predadoras"]', 'Crisopídeos, formiga-leão, mantídeos-neurópteros', 'Importantes agentes de controle biológico. As larvas de crisopídeos são predadoras vorazes de pulgões.', '', TRUE, 8, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(9, 'Thysanoptera', 'Ordem dos tripes, insetos minúsculos com asas franjadas.', '["Asas franjadas","Corpo muito pequeno (0,5-5mm)","Peças bucais assimétrica raspadora-sugadora","Metamorfose intermediária"]', 'Tripes, trips', 'Causam danos em flores, frutos e folhas. Vetores importantes de Tospoviruses.', '', TRUE, 9, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(10, 'Coleoptera', 'Maior ordem de insetos, com asas anteriores do tipo élitro.', '["Asas anteriores do tipo élitro","Metamorfose holometabólica","Grande diversidade de formas e hábitos","Aparelho bucal mastigador"]', 'Besouros, joaninhas, vagalumes, carunchos, brocas', 'Inclui pragas importantíssimas como curculionídeos, crisomelídeos e cerambicídeos, além de predadores úteis como joaninhas.', '', TRUE, 10, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(11, 'Lepidoptera', 'Ordem das borboletas e mariposas, com asas cobertas por escamas.', '["Asas cobertas por escamas","Espirotromba para sucção de néctar","Metamorfose holometabólica","Lagartas fitófagas"]', 'Borboletas, mariposas, lagartas', 'As lagartas (larvas) são importantes pragas de diversas culturas. Adultos podem ser polinizadores.', '', TRUE, 11, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(12, 'Hymenoptera', 'Ordem das abelhas, vespas, formigas e abelhas.', '["Dois pares de asas membranosas acopladas","Metamorfose holometabólica","Muitas espécies com comportamento social","Peças bucais mastigadoras-lambedoras"]', 'Abelhas, vespas, formigas, mamangavas', 'Incluem importantes polinizadores (abelhas) e agentes de controle biológico (parasitoides). Formigas podem ser pragas ou benéficas.', '', TRUE, 12, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(13, 'Diptera', 'Ordem das moscas e mosquitos, com apenas um par de asas funcionais.', '["Um par de asas membranosas","Asas posteriores modificadas em halteres","Metamorfose holometabólica","Peças bucais variadas"]', 'Moscas, mosquitos, mutucas, pernilongos', 'Inclui pragas agrícolas (moscas-das-frutas, moscas-minadoras) e vetores de doenças humanas e animais (mosquitos).', '', TRUE, 13, '2026-04-08 18:51:52', '2026-04-08 18:51:52');

SELECT setval('ordens_id_seq', (SELECT MAX(id) FROM ordens));

-- --------------------------------------------------------
-- Tabela: familias
-- --------------------------------------------------------

CREATE TABLE familias (
  id SERIAL PRIMARY KEY,
  ordem_id integer NOT NULL,
  nome varchar(100) NOT NULL,
  descricao text DEFAULT NULL,
  exemplos varchar(255) DEFAULT NULL,
  imagem varchar(255) DEFAULT NULL,
  ativo boolean DEFAULT TRUE,
  CONSTRAINT familias_ibfk_1 FOREIGN KEY (ordem_id) REFERENCES ordens (id) ON DELETE CASCADE
);

INSERT INTO familias (id, ordem_id, nome, descricao, exemplos, imagem, ativo) VALUES
(1, 1, 'Cicadidae', 'Três ocelos; fêmures anteriores dilatados. Insetos de grande porte com órgão estridulador nos machos.', 'Cigarras', NULL, TRUE),
(2, 1, 'Membracidae', 'Pronoto estendendo-se sobre o abdome, às vezes com ornamentações grotescas.', 'Membracídeos, "bichos espinho"', NULL, TRUE),
(3, 1, 'Cicadellidae', 'Tíbias posteriores com 1 ou 2 fileiras de espinhos.', 'Cigarrinhas', NULL, TRUE),
(4, 1, 'Cercopidae', 'Tíbias posteriores com 1 ou 2 espinhos.', 'Cigarrinhas-espumadeiras', NULL, TRUE),
(5, 1, 'Delphacidae', 'Tíbias posteriores com 1 esporão apical.', 'Delfacídeos', NULL, TRUE),
(6, 1, 'Flatidae', 'Segundo artículo dos tarsos posteriores com 2 espinhos apicais.', 'Flatídeos', NULL, TRUE),
(7, 1, 'Fulgoridae', 'Asas posteriores com a área anal reticulada.', 'Fulgurídeos', NULL, TRUE),
(8, 1, 'Aethalionidae', 'Tíbias posteriores com pêlos e sem espinhos.', 'Etalionídeos', NULL, TRUE),
(9, 2, 'Psyllidae', 'Antenas geralmente com 10 artículos.', 'Psilídeos, "pulgas-de-planta"', NULL, TRUE),
(10, 2, 'Aleyrodidae', 'Corpo e asas revestidos por secreção pulverulenta branca.', 'Moscas-brancas', NULL, TRUE),
(11, 2, 'Aphididae', 'Sifúnculos presentes; corpo e asas sem revestimento branco.', 'Pulgões, afídeos', NULL, TRUE),
(12, 3, 'Pentatomidae', 'Escutelo estendendo-se até metade do abdome; pernas anteriores ambulatórias.', 'Percevejos-verdes, maria-fedida', NULL, TRUE),
(13, 3, 'Reduviidae', 'Rostro com 3 segmentos; proesterno com sulco.', 'Barbeiros, assassin bugs', NULL, TRUE),
(14, 3, 'Miridae', 'Hemiélitro com uma nervura na membrana; com cúneo.', 'Miriídeos', NULL, TRUE),
(15, 3, 'Tingidae', 'Hemiélitros reticulados.', 'Tingídeos, percevejos-de-renda', NULL, TRUE),
(16, 3, 'Lygaeidae', 'Hemiélitro com menos de 7 nervuras na base da membrana; ocelos presentes.', 'Ligeideos', NULL, TRUE),
(17, 3, 'Coreidae', 'Cabeça mais estreita que o pronoto; glândula odorífera entre 2º e 3º par de pernas.', 'Coreideos', NULL, TRUE),
(18, 3, 'Gerridae', 'Fêmures posteriores ultrapassando muito o ápice do abdome.', 'Barqueiros, water striders', NULL, TRUE),
(19, 3, 'Scutelleridae', 'Hemiélitros cobertos pelo escutelo.', 'Escutelarídeos', NULL, TRUE),
(20, 4, 'Acrididae', 'Tíbias posteriores com último espinho externo afastado do ápice.', 'Gafanhotos', NULL, TRUE),
(21, 4, 'Gryllidae', 'Tarsos com 3 segmentos.', 'Grilos', NULL, TRUE),
(22, 4, 'Tettigoniidae', 'Tarsos com 4 segmentos; asas presentes.', 'Esperanças, gafanhotos-de-antena-longa', NULL, TRUE),
(23, 4, 'Gryllotalpidae', 'Pernas anteriores fossoriais.', 'Paquinhas, grilos-toupeira', NULL, TRUE),
(24, 4, 'Tetrigidae', 'Pronoto longo, prolongando-se sobre o abdome.', 'Tetrigídeos', NULL, TRUE),
(25, 5, 'Libellulidae', 'Triângulos diferentes nos dois pares de asas; alça anal com formato de pé.', 'Libélulas', NULL, TRUE),
(26, 5, 'Aeshnidae', 'Triângulos semelhantes nos dois pares de asas.', 'Libélulas grandes', NULL, TRUE),
(27, 5, 'Coenagrionidae', 'Duas nervuras antenodais; asas anteriores e posteriores semelhantes.', 'Donzelinhas', NULL, TRUE),
(28, 5, 'Calopterygidae', 'Várias nervuras antenodais; asas anteriores e posteriores semelhantes.', 'Donzelinhas metálicas', NULL, TRUE),
(29, 6, 'Forficulidae', 'Segundo tarsômero dilatado distalmente.', 'Tesourinhas comuns', NULL, TRUE),
(30, 6, 'Labiduridae', 'Antenas com mais de 20 artículos; 20 a 30 mm.', 'Tesourinhas grandes', NULL, TRUE),
(31, 6, 'Spongiphoridae', 'Antenas com menos de 20 artículos; menos de 20 mm.', 'Tesourinhas pequenas', NULL, TRUE),
(32, 7, 'Termitidae', 'Fontanela presente; escama anterior curta.', 'Cupins-de-solo, cupins-arbóreos', NULL, TRUE),
(33, 7, 'Rhinotermitidae', 'Fontanela presente; escama anterior longa.', 'Cupins-subterrâneos', NULL, TRUE),
(34, 7, 'Kalotermitidae', 'Fontanela ausente.', 'Cupins-de-madeira-seca', NULL, TRUE),
(35, 8, 'Chrysopidae', 'Asas anteriores com nervuras transversais costais simples; insetos esverdeados.', 'Crisopídeos, "bicho-lixeiro"', NULL, TRUE),
(36, 8, 'Myrmeleontidae', 'Antenas clavadas mais curtas que metade das asas.', 'Formiga-leão', NULL, TRUE),
(37, 8, 'Ascalaphidae', 'Antenas clavadas mais longas que metade das asas.', 'Ascalafídeos', NULL, TRUE),
(38, 8, 'Mantispidae', 'Pernas anteriores raptatórias; pronoto alongado.', 'Mantíspa', NULL, TRUE),
(39, 8, 'Hemerobiidae', 'Asas anteriores com nervuras transversais costais bifurcadas.', 'Hemerobiídeos', NULL, TRUE),
(40, 9, 'Phlaeothripidae', 'Ápice do abdome tubular; asas anteriores sem nervuras.', 'Tripes-com-tubo', NULL, TRUE),
(41, 9, 'Thripidae', 'Ovipositor voltado para baixo; antenas com 6 a 8 artículos.', 'Tripes comuns', NULL, TRUE),
(42, 9, 'Aeolothripidae', 'Ovipositor voltado para cima; antenas com 9 artículos.', 'Eolotripídeos', NULL, TRUE),
(43, 10, 'Scarabaeidae', 'Antenas lameladas; corpo sem constrição; pronoto sem sulco.', 'Besouros-rola-bosta, pão-de-mel, mariposas-da-mandioca', NULL, TRUE),
(44, 10, 'Curculionidae', 'Cabeça prolongada em rostro; antenas geniculadas.', 'Carunchos, bicudo, gorgulhos', NULL, TRUE),
(45, 10, 'Cerambycidae', 'Antenas longas inseridas em elevação frontal; tarsos criptopentâmeros.', 'Brocas, serras-paus', NULL, TRUE),
(46, 10, 'Chrysomelidae', 'Antenas mais curtas que o corpo; tarsos criptopentâmeros.', 'Vaquinhas, besouro-da-batata', NULL, TRUE),
(47, 10, 'Coccinellidae', 'Tarsos criptotetrâmeros (aparentemente 3-3-3).', 'Joaninhas', NULL, TRUE),
(48, 10, 'Staphylinidae', 'Élitros não cobrindo o abdome; 6 ou 7 segmentos abdominais visíveis.', 'Estafilinídeos', NULL, TRUE),
(49, 10, 'Elateridae', 'Proesterno com apófise livre e pontiaguda.', 'Elaterídeos, besouros-click', NULL, TRUE),
(50, 10, 'Lampyridae', 'Abdome com órgão luminescente.', 'Vagalumes, pirilampos', NULL, TRUE),
(51, 10, 'Carabidae', 'Mandíbulas sem dente; coxas posteriores dividindo urosternito.', 'Carabídeos, besouros-de-solo', NULL, TRUE),
(52, 11, 'Papilionidae', 'Antenas clavadas; asas posteriores com uma nervura anal.', 'Borboletas-pavão, macaão', NULL, TRUE),
(53, 11, 'Nymphalidae', 'Pernas anteriores atrofiadas; olhos compostos sem reentrância.', 'Borboletas-monarca, borboletas-coruja', NULL, TRUE),
(54, 11, 'Pieridae', 'Pernas anteriores normais; asas posteriores com 2 nervuras anais.', 'Borboletas-brancas, borboletas-amarelas', NULL, TRUE),
(55, 11, 'Sphingidae', 'Antenas estiliformes; corpo robusto.', 'Mariposas-esfinge, manduca', NULL, TRUE),
(56, 11, 'Saturniidae', 'Frênulo vestigial ou ausente.', 'Mariposas-saturnia, bicho-da-seda selvagem', NULL, TRUE),
(57, 11, 'Noctuidae', 'Frênulo desenvolvido; Sc da asa posterior sem ângulo basal.', 'Mariposas-noturnas, lagartas-do-cartucho', NULL, TRUE),
(58, 11, 'Pyralidae', 'Asas posteriores com Sc+R1 aproximada ou fundida à Rs.', 'Piralídeos, broca-do-milho', NULL, TRUE),
(59, 11, 'Hesperiidae', 'Antenas fusiformes.', 'Borboletas-hesperídeas', NULL, TRUE),
(60, 12, 'Formicidae', 'Pecíolo abdominal com 1 ou 2 nódulos.', 'Formigas, saúvas, quenquéns', NULL, TRUE),
(61, 12, 'Apidae', 'Pernas posteriores com corbícula.', 'Abelhas, mamangavas, uruçus', NULL, TRUE),
(62, 12, 'Vespidae', 'Pronoto estendendo-se até a tégula; sem corbícula.', 'Vespas, marimbondos, cabas', NULL, TRUE),
(63, 12, 'Braconidae', 'Parasitoides com nervação alar específica.', 'Vespinhas-parasitas de lagartas', NULL, TRUE),
(64, 12, 'Ichneumonidae', 'Trocanteres posteriores com 2 segmentos; asas com nervação característica.', 'Ichneumonídeos, parasitoides', NULL, TRUE),
(65, 13, 'Culicidae', 'Probóscida longa para picar; escamas nas asas.', 'Mosquitos, pernilongos', NULL, TRUE),
(66, 13, 'Tephritidae', 'Mosca com asas manchadas; larvas em frutos.', 'Moscas-das-frutas', NULL, TRUE),
(67, 13, 'Agromyzidae', 'Larvas minadoras de folhas.', 'Moscas-minadoras', NULL, TRUE),
(68, 13, 'Tabanidae', 'Moscas robustas; olhos compostos grandes.', 'Mutucas, tavões', NULL, TRUE),
(69, 13, 'Syrphidae', 'Nervura espúria entre R e M.', 'Sirfídeos, moscas-das-flores', NULL, TRUE);

SELECT setval('familias_id_seq', (SELECT MAX(id) FROM familias));

-- --------------------------------------------------------
-- Tabela: chave_passos
-- --------------------------------------------------------

CREATE TABLE chave_passos (
  id SERIAL PRIMARY KEY,
  ordem_id integer NOT NULL,
  passo_numero integer NOT NULL,
  pergunta text NOT NULL,
  opcao_sim_texto varchar(255) DEFAULT NULL,
  sim_imagem varchar(255) DEFAULT NULL,
  opcao_nao_texto varchar(255) DEFAULT NULL,
  nao_imagem varchar(255) DEFAULT NULL,
  sim_leva_passo integer DEFAULT NULL,
  nao_leva_passo integer DEFAULT NULL,
  sim_resultado_familia_id integer DEFAULT NULL,
  nao_resultado_familia_id integer DEFAULT NULL,
  CONSTRAINT chave_passos_ibfk_1 FOREIGN KEY (ordem_id) REFERENCES ordens (id) ON DELETE CASCADE,
  CONSTRAINT chave_passos_ibfk_2 FOREIGN KEY (sim_resultado_familia_id) REFERENCES familias (id) ON DELETE SET NULL,
  CONSTRAINT chave_passos_ibfk_3 FOREIGN KEY (nao_resultado_familia_id) REFERENCES familias (id) ON DELETE SET NULL
);

INSERT INTO chave_passos (id, ordem_id, passo_numero, pergunta, opcao_sim_texto, sim_imagem, opcao_nao_texto, nao_imagem, sim_leva_passo, nao_leva_passo, sim_resultado_familia_id, nao_resultado_familia_id) VALUES
(1, 1, 1, 'Protórax desenvolvido e expandido para trás, formando um casco que cobre o abdome?', 'Protórax muito desenvolvido, formando estrutura em forma de capacete ou chifre', NULL, 'Protórax normal, não expandido sobre o abdome', NULL, NULL, 2, 2, NULL),
(2, 1, 2, 'Inseto de tamanho grande (>2cm) com órgão estridulador nos machos?', 'Grande, com timbais para produção de som', NULL, 'Pequeno a médio, sem órgão estridulador evidente', NULL, NULL, 3, 1, NULL),
(3, 1, 3, 'Tíbias posteriores com 1 ou 2 fileiras de espinhos (não apenas 1-2 espinhos isolados)?', 'Fileiras de espinhos nas tíbias posteriores', NULL, 'Apenas 1 ou 2 espinhos isolados nas tíbias', NULL, NULL, NULL, 3, 4),
(4, 13, 1, 'esse inseto é grande?', 'inseto grande', NULL, 'inseto pequeno', NULL, 1, 2, 69, 68);

SELECT setval('chave_passos_id_seq', (SELECT MAX(id) FROM chave_passos));

COMMIT;
