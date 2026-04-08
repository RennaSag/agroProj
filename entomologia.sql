-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/04/2026 às 21:42
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `entomologia`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `admins`
--

INSERT INTO `admins` (`id`, `nome`, `email`, `senha`, `criado_em`) VALUES
(1, 'adm', 'adm', 'b09c600fddc573f117449b3723f23d64', '2026-04-08 18:51:52');

-- --------------------------------------------------------

--
-- Estrutura para tabela `chave_passos`
--

CREATE TABLE `chave_passos` (
  `id` int(11) NOT NULL,
  `ordem_id` int(11) NOT NULL,
  `passo_numero` int(11) NOT NULL,
  `pergunta` text NOT NULL,
  `opcao_sim_texto` varchar(255) DEFAULT NULL,
  `opcao_nao_texto` varchar(255) DEFAULT NULL,
  `sim_leva_passo` int(11) DEFAULT NULL COMMENT 'NULL = resultado final',
  `nao_leva_passo` int(11) DEFAULT NULL COMMENT 'NULL = resultado final',
  `sim_resultado_familia_id` int(11) DEFAULT NULL,
  `nao_resultado_familia_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `chave_passos`
--

INSERT INTO `chave_passos` (`id`, `ordem_id`, `passo_numero`, `pergunta`, `opcao_sim_texto`, `opcao_nao_texto`, `sim_leva_passo`, `nao_leva_passo`, `sim_resultado_familia_id`, `nao_resultado_familia_id`) VALUES
(1, 1, 1, 'Protórax desenvolvido e expandido para trás, formando um casco que cobre o abdome?', 'Protórax muito desenvolvido, formando estrutura em forma de capacete ou chifre', 'Protórax normal, não expandido sobre o abdome', NULL, 2, 2, NULL),
(2, 1, 2, 'Inseto de tamanho grande (>2cm) com órgão estridulador nos machos?', 'Grande, com timbais para produção de som', 'Pequeno a médio, sem órgão estridulador evidente', NULL, 3, 1, NULL),
(3, 1, 3, 'Tíbias posteriores com 1 ou 2 fileiras de espinhos (não apenas 1-2 espinhos isolados)?', 'Fileiras de espinhos nas tíbias posteriores', 'Apenas 1 ou 2 espinhos isolados nas tíbias', NULL, NULL, 3, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `familias`
--

CREATE TABLE `familias` (
  `id` int(11) NOT NULL,
  `ordem_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `exemplos` varchar(255) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `familias`
--

INSERT INTO `familias` (`id`, `ordem_id`, `nome`, `descricao`, `exemplos`, `imagem`, `ativo`) VALUES
(1, 1, 'Cicadidae', 'Três ocelos; fêmures anteriores dilatados. Insetos de grande porte com órgão estridulador nos machos.', 'Cigarras', NULL, 1),
(2, 1, 'Membracidae', 'Pronoto estendendo-se sobre o abdome, às vezes com ornamentações grotescas.', 'Membracídeos, \"bichos espinho\"', NULL, 1),
(3, 1, 'Cicadellidae', 'Tíbias posteriores com 1 ou 2 fileiras de espinhos.', 'Cigarrinhas', NULL, 1),
(4, 1, 'Cercopidae', 'Tíbias posteriores com 1 ou 2 espinhos.', 'Cigarrinhas-espumadeiras', NULL, 1),
(5, 1, 'Delphacidae', 'Tíbias posteriores com 1 esporão apical.', 'Delfacídeos', NULL, 1),
(6, 1, 'Flatidae', 'Segundo artículo dos tarsos posteriores com 2 espinhos apicais.', 'Flatídeos', NULL, 1),
(7, 1, 'Fulgoridae', 'Asas posteriores com a área anal reticulada.', 'Fulgurídeos', NULL, 1),
(8, 1, 'Aethalionidae', 'Tíbias posteriores com pêlos e sem espinhos.', 'Etalionídeos', NULL, 1),
(9, 2, 'Psyllidae', 'Antenas geralmente com 10 artículos.', 'Psilídeos, \"pulgas-de-planta\"', NULL, 1),
(10, 2, 'Aleyrodidae', 'Corpo e asas revestidos por secreção pulverulenta branca.', 'Moscas-brancas', NULL, 1),
(11, 2, 'Aphididae', 'Sifúnculos presentes; corpo e asas sem revestimento branco.', 'Pulgões, afídeos', NULL, 1),
(12, 3, 'Pentatomidae', 'Escutelo estendendo-se até metade do abdome; pernas anteriores ambulatórias.', 'Percevejos-verdes, maria-fedida', NULL, 1),
(13, 3, 'Reduviidae', 'Rostro com 3 segmentos; proesterno com sulco.', 'Barbeiros, assassin bugs', NULL, 1),
(14, 3, 'Miridae', 'Hemiélitro com uma nervura na membrana; com cúneo.', 'Miriídeos', NULL, 1),
(15, 3, 'Tingidae', 'Hemiélitros reticulados.', 'Tingídeos, percevejos-de-renda', NULL, 1),
(16, 3, 'Lygaeidae', 'Hemiélitro com menos de 7 nervuras na base da membrana; ocelos presentes.', 'Ligeideos', NULL, 1),
(17, 3, 'Coreidae', 'Cabeça mais estreita que o pronoto; glândula odorífera entre 2º e 3º par de pernas.', 'Coreideos', NULL, 1),
(18, 3, 'Gerridae', 'Fêmures posteriores ultrapassando muito o ápice do abdome.', 'Barqueiros, water striders', NULL, 1),
(19, 3, 'Scutelleridae', 'Hemiélitros cobertos pelo escutelo.', 'Escutelarídeos', NULL, 1),
(20, 4, 'Acrididae', 'Tíbias posteriores com último espinho externo afastado do ápice.', 'Gafanhotos', NULL, 1),
(21, 4, 'Gryllidae', 'Tarsos com 3 segmentos.', 'Grilos', NULL, 1),
(22, 4, 'Tettigoniidae', 'Tarsos com 4 segmentos; asas presentes.', 'Esperanças, gafanhotos-de-antena-longa', NULL, 1),
(23, 4, 'Gryllotalpidae', 'Pernas anteriores fossoriais.', 'Paquinhas, grilos-toupeira', NULL, 1),
(24, 4, 'Tetrigidae', 'Pronoto longo, prolongando-se sobre o abdome.', 'Tetrigídeos', NULL, 1),
(25, 5, 'Libellulidae', 'Triângulos diferentes nos dois pares de asas; alça anal com formato de pé.', 'Libélulas', NULL, 1),
(26, 5, 'Aeshnidae', 'Triângulos semelhantes nos dois pares de asas.', 'Libélulas grandes', NULL, 1),
(27, 5, 'Coenagrionidae', 'Duas nervuras antenodais; asas anteriores e posteriores semelhantes.', 'Donzelinhas', NULL, 1),
(28, 5, 'Calopterygidae', 'Várias nervuras antenodais; asas anteriores e posteriores semelhantes.', 'Donzelinhas metálicas', NULL, 1),
(29, 6, 'Forficulidae', 'Segundo tarsômero dilatado distalmente.', 'Tesourinhas comuns', NULL, 1),
(30, 6, 'Labiduridae', 'Antenas com mais de 20 artículos; 20 a 30 mm.', 'Tesourinhas grandes', NULL, 1),
(31, 6, 'Spongiphoridae', 'Antenas com menos de 20 artículos; menos de 20 mm.', 'Tesourinhas pequenas', NULL, 1),
(32, 7, 'Termitidae', 'Fontanela presente; escama anterior curta.', 'Cupins-de-solo, cupins-arbóreos', NULL, 1),
(33, 7, 'Rhinotermitidae', 'Fontanela presente; escama anterior longa.', 'Cupins-subterrâneos', NULL, 1),
(34, 7, 'Kalotermitidae', 'Fontanela ausente.', 'Cupins-de-madeira-seca', NULL, 1),
(35, 8, 'Chrysopidae', 'Asas anteriores com nervuras transversais costais simples; insetos esverdeados.', 'Crisopídeos, \"bicho-lixeiro\"', NULL, 1),
(36, 8, 'Myrmeleontidae', 'Antenas clavadas mais curtas que metade das asas.', 'Formiga-leão', NULL, 1),
(37, 8, 'Ascalaphidae', 'Antenas clavadas mais longas que metade das asas.', 'Ascalafídeos', NULL, 1),
(38, 8, 'Mantispidae', 'Pernas anteriores raptatórias; pronoto alongado.', 'Mantíspa', NULL, 1),
(39, 8, 'Hemerobiidae', 'Asas anteriores com nervuras transversais costais bifurcadas.', 'Hemerobiídeos', NULL, 1),
(40, 9, 'Phlaeothripidae', 'Ápice do abdome tubular; asas anteriores sem nervuras.', 'Tripes-com-tubo', NULL, 1),
(41, 9, 'Thripidae', 'Ovipositor voltado para baixo; antenas com 6 a 8 artículos.', 'Tripes comuns', NULL, 1),
(42, 9, 'Aeolothripidae', 'Ovipositor voltado para cima; antenas com 9 artículos.', 'Eolotripídeos', NULL, 1),
(43, 10, 'Scarabaeidae', 'Antenas lameladas; corpo sem constrição; pronoto sem sulco.', 'Besouros-rola-bosta, pão-de-mel, mariposas-da-mandioca', NULL, 1),
(44, 10, 'Curculionidae', 'Cabeça prolongada em rostro; antenas geniculadas.', 'Carunchos, bicudo, gorgulhos', NULL, 1),
(45, 10, 'Cerambycidae', 'Antenas longas inseridas em elevação frontal; tarsos criptopentâmeros.', 'Brocas, serras-paus', NULL, 1),
(46, 10, 'Chrysomelidae', 'Antenas mais curtas que o corpo; tarsos criptopentâmeros.', 'Vaquinhas, besouro-da-batata', NULL, 1),
(47, 10, 'Coccinellidae', 'Tarsos criptotetrâmeros (aparentemente 3-3-3).', 'Joaninhas', NULL, 1),
(48, 10, 'Staphylinidae', 'Élitros não cobrindo o abdome; 6 ou 7 segmentos abdominais visíveis.', 'Estafilinídeos', NULL, 1),
(49, 10, 'Elateridae', 'Proesterno com apófise livre e pontiaguda.', 'Elaterídeos, besouros-click', NULL, 1),
(50, 10, 'Lampyridae', 'Abdome com órgão luminescente.', 'Vagalumes, pirilampos', NULL, 1),
(51, 10, 'Carabidae', 'Mandíbulas sem dente; coxas posteriores dividindo urosternito.', 'Carabídeos, besouros-de-solo', NULL, 1),
(52, 11, 'Papilionidae', 'Antenas clavadas; asas posteriores com uma nervura anal.', 'Borboletas-pavão, macaão', NULL, 1),
(53, 11, 'Nymphalidae', 'Pernas anteriores atrofiadas; olhos compostos sem reentrância.', 'Borboletas-monarca, borboletas-coruja', NULL, 1),
(54, 11, 'Pieridae', 'Pernas anteriores normais; asas posteriores com 2 nervuras anais.', 'Borboletas-brancas, borboletas-amarelas', NULL, 1),
(55, 11, 'Sphingidae', 'Antenas estiliformes; corpo robusto.', 'Mariposas-esfinge, manduca', NULL, 1),
(56, 11, 'Saturniidae', 'Frênulo vestigial ou ausente.', 'Mariposas-saturnia, bicho-da-seda selvagem', NULL, 1),
(57, 11, 'Noctuidae', 'Frênulo desenvolvido; Sc da asa posterior sem ângulo basal.', 'Mariposas-noturnas, lagartas-do-cartucho', NULL, 1),
(58, 11, 'Pyralidae', 'Asas posteriores com Sc+R1 aproximada ou fundida à Rs.', 'Piralídeos, broca-do-milho', NULL, 1),
(59, 11, 'Hesperiidae', 'Antenas fusiformes.', 'Borboletas-hesperídeas', NULL, 1),
(60, 12, 'Formicidae', 'Pecíolo abdominal com 1 ou 2 nódulos.', 'Formigas, saúvas, quenquéns', NULL, 1),
(61, 12, 'Apidae', 'Pernas posteriores com corbícula.', 'Abelhas, mamangavas, uruçus', NULL, 1),
(62, 12, 'Vespidae', 'Pronoto estendendo-se até a tégula; sem corbícula.', 'Vespas, marimbondos, cabas', NULL, 1),
(63, 12, 'Braconidae', 'Parasitoides com nervação alar específica.', 'Vespinhas-parasitas de lagartas', NULL, 1),
(64, 12, 'Ichneumonidae', 'Trocanteres posteriores com 2 segmentos; asas com nervação característica.', 'Ichneumonídeos, parasitoides', NULL, 1),
(65, 13, 'Culicidae', 'Probóscida longa para picar; escamas nas asas.', 'Mosquitos, pernilongos', NULL, 1),
(66, 13, 'Tephritidae', 'Mosca com asas manchadas; larvas em frutos.', 'Moscas-das-frutas', NULL, 1),
(67, 13, 'Agromyzidae', 'Larvas minadoras de folhas.', 'Moscas-minadoras', NULL, 1),
(68, 13, 'Tabanidae', 'Moscas robustas; olhos compostos grandes.', 'Mutucas, tavões', NULL, 1),
(69, 13, 'Syrphidae', 'Nervura espúria entre R e M.', 'Sirfídeos, moscas-das-flores', NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordens`
--

CREATE TABLE `ordens` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `caracteristicas` text DEFAULT NULL COMMENT 'JSON array de características gerais',
  `exemplos` varchar(255) DEFAULT NULL,
  `importancia_agricola` text DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `ordem_exibicao` int(11) DEFAULT 0,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `ordens`
--

INSERT INTO `ordens` (`id`, `nome`, `descricao`, `caracteristicas`, `exemplos`, `importancia_agricola`, `imagem`, `ativo`, `ordem_exibicao`, `criado_em`, `atualizado_em`) VALUES
(1, 'Hemiptera-Auchenorrhyncha', 'Subordem de Hemiptera com antenas curtas com filamento apical e rostro originando-se da parte posterior da cabeça.', '[\"Peças bucais picadoras-sugadoras\",\"Rostro com origem na parte posterior da cabeça\",\"Antenas curtas e setáceas\",\"Asas anteriores uniformes em textura\"]', 'Cigarras, cigarrinhas', 'Importantes vetores de doenças em plantas agrícolas. Sugam seiva do floema e xilema.', '', 1, 1, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(2, 'Hemiptera-Sternorrhyncha', 'Subordem de Hemiptera com antenas longas ou curtas sem filamento apical e rostro surgindo entre as coxas anteriores.', '[\"Rostro surgindo entre as coxas anteriores\",\"Antenas longas ou curtas sem filamento apical\",\"Corpo frequentemente coberto por secreções\"]', 'Pulgões, moscas-brancas, cochonilhas, psilídeos', 'Pragas de grande importância econômica. Causam danos diretos por sucção de seiva e indiretos como vetores de vírus.', '', 1, 2, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(3, 'Hemiptera-Heteroptera', 'Subordem de Hemiptera com hemiélitros e grande diversidade de hábitos.', '[\"Hemiélitros com parte basal coriácea e parte apical membranosa\",\"Glândulas odoríferas metatorácicas\",\"Metamorfose hemimetabólica\"]', 'Percevejos, barbeiros, baratas-d\'água, marigosas', 'Inclui pragas agrícolas importantes como percevejos da soja, além de predadores benéficos.', '', 1, 3, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(4, 'Orthoptera', 'Ordem com pernas posteriores saltatórias, antenas filiformes ou setáceas.', '[\"Pernas posteriores saltatórias\",\"Metamorfose hemimetabólica\",\"Tegminas coriáceas\",\"Cercos presentes\"]', 'Gafanhotos, grilos, esperanças, paquinhas', 'Gafanhotos podem causar devastação em lavouras. Grilos atacam plântulas e raízes.', '', 1, 4, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(5, 'Odonata', 'Ordem de insetos com dois pares de asas membranosas e olhos compostos grandes.', '[\"Dois pares de asas membranosas reticuladas\",\"Olhos compostos muito desenvolvidos\",\"Abdome longo e delgado\",\"Metamorfose hemimetabólica aquática\"]', 'Libélulas, donzelinhas', 'Predadores de outros insetos, incluindo pragas agrícolas. As larvas são aquáticas.', '', 1, 5, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(6, 'Dermaptera', 'Ordem conhecida pelas tesourinhas com cercos quitinosos em forma de pinça.', '[\"Cercos quitinosos em forma de pinça\",\"Élitros curtos e coriáceos\",\"Abdome flexível\",\"Metamorfose hemimetabólica\"]', 'Tesourinhas, bicho-tesourinha', 'Algumas espécies são predadoras de pragas; outras podem atacar plantas e frutas.', '', 1, 6, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(7, 'Isoptera', 'Ordem dos cupins, insetos sociais com colônias organizadas.', '[\"Insetos sociais com castas\",\"Antenas moniliformes\",\"Metamorfose hemimetabólica\",\"Asas iguais nos alados\"]', 'Cupins, siri-siris', 'Causam enormes prejuízos em madeiras, pastagens, cana-de-açúcar e outras culturas.', '', 1, 7, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(8, 'Neuroptera', 'Ordem com asas reticuladas e antenas longas.', '[\"Asas com aspecto reticulado\",\"Antenas longas e bem visíveis\",\"Metamorfose holometabólica\",\"Larvas predadoras\"]', 'Crisopídeos, formiga-leão, mantídeos-neurópteros', 'Importantes agentes de controle biológico. As larvas de crisopídeos são predadoras vorazes de pulgões.', '', 1, 8, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(9, 'Thysanoptera', 'Ordem dos tripes, insetos minúsculos com asas franjadas.', '[\"Asas franjadas\",\"Corpo muito pequeno (0,5-5mm)\",\"Peças bucais assimétrica raspadora-sugadora\",\"Metamorfose intermediária\"]', 'Tripes, trips', 'Causam danos em flores, frutos e folhas. Vetores importantes de Tospoviruses.', '', 1, 9, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(10, 'Coleoptera', 'Maior ordem de insetos, com asas anteriores do tipo élitro.', '[\"Asas anteriores do tipo élitro\",\"Metamorfose holometabólica\",\"Grande diversidade de formas e hábitos\",\"Aparelho bucal mastigador\"]', 'Besouros, joaninhas, vagalumes, carunchos, brocas', 'Inclui pragas importantíssimas como curculionídeos, crisomelídeos e cerambicídeos, além de predadores úteis como joaninhas.', '', 1, 10, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(11, 'Lepidoptera', 'Ordem das borboletas e mariposas, com asas cobertas por escamas.', '[\"Asas cobertas por escamas\",\"Espirotromba para sucção de néctar\",\"Metamorfose holometabólica\",\"Lagartas fitófagas\"]', 'Borboletas, mariposas, lagartas', 'As lagartas (larvas) são importantes pragas de diversas culturas. Adultos podem ser polinizadores.', '', 1, 11, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(12, 'Hymenoptera', 'Ordem das abelhas, vespas, formigas e abelhas.', '[\"Dois pares de asas membranosas acopladas\",\"Metamorfose holometabólica\",\"Muitas espécies com comportamento social\",\"Peças bucais mastigadoras-lambedoras\"]', 'Abelhas, vespas, formigas, mamangavas', 'Incluem importantes polinizadores (abelhas) e agentes de controle biológico (parasitoides). Formigas podem ser pragas ou benéficas.', '', 1, 12, '2026-04-08 18:51:52', '2026-04-08 18:51:52'),
(13, 'Diptera', 'Ordem das moscas e mosquitos, com apenas um par de asas funcionais.', '[\"Um par de asas membranosas\",\"Asas posteriores modificadas em halteres\",\"Metamorfose holometabólica\",\"Peças bucais variadas\"]', 'Moscas, mosquitos, mutucas, pernilongos', 'Inclui pragas agrícolas (moscas-das-frutas, moscas-minadoras) e vetores de doenças humanas e animais (mosquitos).', '', 1, 13, '2026-04-08 18:51:52', '2026-04-08 18:51:52');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `chave_passos`
--
ALTER TABLE `chave_passos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordem_id` (`ordem_id`),
  ADD KEY `sim_resultado_familia_id` (`sim_resultado_familia_id`),
  ADD KEY `nao_resultado_familia_id` (`nao_resultado_familia_id`);

--
-- Índices de tabela `familias`
--
ALTER TABLE `familias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordem_id` (`ordem_id`);

--
-- Índices de tabela `ordens`
--
ALTER TABLE `ordens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `chave_passos`
--
ALTER TABLE `chave_passos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `familias`
--
ALTER TABLE `familias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de tabela `ordens`
--
ALTER TABLE `ordens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `chave_passos`
--
ALTER TABLE `chave_passos`
  ADD CONSTRAINT `chave_passos_ibfk_1` FOREIGN KEY (`ordem_id`) REFERENCES `ordens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chave_passos_ibfk_2` FOREIGN KEY (`sim_resultado_familia_id`) REFERENCES `familias` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chave_passos_ibfk_3` FOREIGN KEY (`nao_resultado_familia_id`) REFERENCES `familias` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `familias`
--
ALTER TABLE `familias`
  ADD CONSTRAINT `familias_ibfk_1` FOREIGN KEY (`ordem_id`) REFERENCES `ordens` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
