-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 31 juil. 2026 à 18:06
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_poste_achat`
--

-- --------------------------------------------------------

--
-- Structure de la table `achat`
--

CREATE TABLE `achat` (
  `id_achat` int(11) NOT NULL,
  `numero_achat` varchar(30) DEFAULT NULL,
  `date_achat` datetime NOT NULL,
  `poids_brut` decimal(10,2) DEFAULT NULL,
  `nombre_sacs` int(11) DEFAULT NULL,
  `tare` decimal(10,2) DEFAULT NULL,
  `taux_humidite` decimal(5,2) DEFAULT NULL,
  `refaction` decimal(10,2) DEFAULT NULL,
  `poids_net` decimal(10,2) DEFAULT NULL,
  `prix_kg` decimal(12,2) DEFAULT NULL,
  `montant` decimal(14,2) DEFAULT NULL,
  `mode_paiement` enum('Cash','Credit') DEFAULT 'Cash',
  `id_fournisseur` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_poste` int(11) NOT NULL,
  `id_prix` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `qualite` varchar(30) DEFAULT 'Bonne',
  `montant_cash` decimal(14,2) DEFAULT 0.00,
  `montant_avance` decimal(14,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `achat`
--

INSERT INTO `achat` (`id_achat`, `numero_achat`, `date_achat`, `poids_brut`, `nombre_sacs`, `tare`, `taux_humidite`, `refaction`, `poids_net`, `prix_kg`, `montant`, `mode_paiement`, `id_fournisseur`, `id_produit`, `id_poste`, `id_prix`, `id_utilisateur`, `qualite`, `montant_cash`, `montant_avance`) VALUES
(5, 'ACH-20260730-931A', '2026-07-30 08:01:15', 100.00, 0, 0.30, 0.00, 0.00, 99.70, 4.30, 428.71, 'Cash', 3, 3, 1, 1, 1, 'Bonne', 0.00, 0.00),
(6, 'ACH-20260730-91B7', '2026-07-30 15:34:33', 50.00, 0, 0.20, 0.00, 0.00, 49.80, 4.20, 209.16, 'Cash', 2, 3, 1, 1, 1, 'Bonne', 0.00, 0.00),
(7, 'ACH-20260730-BE67', '2026-07-30 16:07:46', 10.00, 0, 0.10, 0.00, 0.00, 9.90, 4.10, 40.59, '', 1, 3, 1, 1, 1, 'Bonne', 0.00, 0.00),
(8, 'ACH-20260730-102D', '2026-07-30 16:20:13', 10.00, 0, 0.00, 0.00, 0.00, 10.00, 5.00, 50.00, 'Cash', 3, 2, 1, 1, 1, 'Moyenne', 50.00, 0.00),
(9, 'ACH-20260730-3292', '2026-07-30 16:21:05', 5.00, 0, 0.00, 0.00, 0.00, 5.00, 5.00, 25.00, '', 1, 2, 1, 1, 1, 'Bonne', 0.00, 25.00),
(10, 'ACH-20260730-FC0B', '2026-07-30 16:26:08', 100.00, 0, 0.00, 0.00, 0.00, 100.00, 3.00, 300.00, 'Cash', 3, 3, 1, 1, 1, 'Bonne', 300.00, 0.00),
(11, 'ACH-20260730-1E33', '2026-07-30 16:48:07', 10.00, 0, 0.00, 0.00, 0.00, 10.00, 4.00, 40.00, '', 1, 3, 1, 1, 1, 'Bonne', 0.00, 40.00),
(12, 'ACH-20260731-1660', '2026-07-31 17:06:30', 25.00, 0, 0.50, 0.00, 0.00, 24.50, 3.80, 93.10, 'Cash', 4, 3, 1, 1, 1, 'Bonne', 93.10, 0.00);

-- --------------------------------------------------------

--
-- Structure de la table `avance`
--

CREATE TABLE `avance` (
  `id_avance` int(11) NOT NULL,
  `date_avance` datetime NOT NULL,
  `montant` decimal(14,2) NOT NULL,
  `motif` varchar(255) DEFAULT NULL,
  `id_producteur` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `avance_fournisseur`
--

CREATE TABLE `avance_fournisseur` (
  `id_avance` int(11) NOT NULL,
  `id_fournisseur` int(11) NOT NULL,
  `id_poste` int(11) NOT NULL,
  `montant_avance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `solde_restant` decimal(12,2) NOT NULL DEFAULT 0.00,
  `observation` text DEFAULT NULL,
  `statut` enum('En_cours','Solde') DEFAULT 'En_cours',
  `id_utilisateur` int(11) NOT NULL,
  `date_avance` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avance_fournisseur`
--

INSERT INTO `avance_fournisseur` (`id_avance`, `id_fournisseur`, `id_poste`, `montant_avance`, `solde_restant`, `observation`, `statut`, `id_utilisateur`, `date_avance`) VALUES
(4, 3, 1, 100.00, 0.00, 'Pré financement', 'Solde', 1, '2026-07-29 22:36:46'),
(5, 1, 1, 500.00, 394.41, 'Pré financement', 'En_cours', 1, '2026-07-30 16:06:15'),
(6, 2, 1, 50.00, 50.00, 'Préfinancement Achat Cacao', 'En_cours', 1, '2026-07-31 17:07:56');

-- --------------------------------------------------------

--
-- Structure de la table `caisse`
--

CREATE TABLE `caisse` (
  `id_caisse` int(11) NOT NULL,
  `date_operation` datetime NOT NULL,
  `type_operation` enum('ENTREE','SORTIE') NOT NULL,
  `libelle` varchar(255) DEFAULT NULL,
  `montant` decimal(14,2) NOT NULL,
  `piece_justificative` varchar(255) DEFAULT NULL,
  `id_poste` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `caisse`
--

INSERT INTO `caisse` (`id_caisse`, `date_operation`, `type_operation`, `libelle`, `montant`, `piece_justificative`, `id_poste`, `id_utilisateur`) VALUES
(1, '2026-07-29 21:49:04', 'ENTREE', 'Solde initial', 2000.00, NULL, 1, 1),
(2, '2026-07-29 22:08:41', 'SORTIE', 'Achat Carburant', 50.00, 'Fact-001', 1, 1),
(3, '2026-07-29 22:36:46', 'SORTIE', 'Avance octroyée au fournisseur ID #3', 100.00, NULL, 1, 1),
(4, '2026-07-30 08:01:16', 'SORTIE', 'Paiement achat N° ACH-20260730-931A', 328.71, NULL, 1, 1),
(5, '2026-07-30 15:34:33', 'SORTIE', 'Paiement achat N° ACH-20260730-91B7', 209.16, NULL, 1, 1),
(6, '2026-07-30 16:06:15', 'SORTIE', 'Avance octroyée au fournisseur ID #1', 500.00, NULL, 1, 1),
(7, '2026-07-30 16:20:14', 'SORTIE', 'Paiement achat N° ACH-20260730-102D', 50.00, NULL, 1, 1),
(8, '2026-07-30 16:26:08', 'SORTIE', 'Paiement achat N° ACH-20260730-FC0B', 300.00, NULL, 1, 1),
(9, '2026-07-31 17:06:30', 'SORTIE', 'Paiement achat N° ACH-20260731-1660', 93.10, NULL, 1, 1),
(10, '2026-07-31 17:07:56', 'SORTIE', 'Avance octroyée au fournisseur ID #2', 50.00, NULL, 1, 1),
(11, '2026-07-31 17:22:39', 'ENTREE', 'Approvisionnement From BCDC', 1000.00, 'Cheque N°9879', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `dette`
--

CREATE TABLE `dette` (
  `id_dette` int(11) NOT NULL,
  `date_dette` datetime NOT NULL,
  `montant` decimal(14,2) NOT NULL,
  `motif` varchar(255) DEFAULT NULL,
  `solde` decimal(14,2) DEFAULT NULL,
  `id_producteur` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `id_entreprise` int(11) NOT NULL,
  `raison_sociale` varchar(150) NOT NULL,
  `sigle` varchar(50) DEFAULT NULL,
  `adresse` varchar(200) DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `rccm` varchar(80) DEFAULT NULL,
  `nif` varchar(80) DEFAULT NULL,
  `idnat` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`id_entreprise`, `raison_sociale`, `sigle`, `adresse`, `telephone`, `email`, `logo`, `rccm`, `nif`, `idnat`) VALUES
(1, 'Établissement Simulitshi', 'ETS SIMULITSHI', 'Mangina, Territoire de Beni', '+243900000000', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id_fournisseur` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `statut` enum('Actif','Inactif') DEFAULT 'Actif',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id_fournisseur`, `nom`, `prenom`, `telephone`, `adresse`, `statut`, `created_at`) VALUES
(1, 'Nathasha', 'Kibondo', '+243978984489', 'Oicha Pari', 'Actif', '2026-07-29 20:43:19'),
(2, 'Gentielle', 'Karupao', '+234987878788', 'Oicha Nzenga', 'Actif', '2026-07-29 20:44:00'),
(3, 'Gabrille', 'Guy', '+243978484884', 'Oicha Paris', 'Actif', '2026-07-29 22:24:46'),
(4, 'Safi', 'Mugheni', '+243970549809', 'Mangina Home 4', 'Actif', '2026-07-31 17:02:01');

-- --------------------------------------------------------

--
-- Structure de la table `journal_audit`
--

CREATE TABLE `journal_audit` (
  `id_audit` int(11) NOT NULL,
  `date_action` datetime NOT NULL,
  `utilisateur` varchar(100) DEFAULT NULL,
  `action_effectuee` varchar(255) DEFAULT NULL,
  `adresse_ip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mouvement_caisse`
--

CREATE TABLE `mouvement_caisse` (
  `id_mouvement` int(11) NOT NULL,
  `id_poste` int(11) NOT NULL,
  `type_mouvement` enum('Entree','Sortie') NOT NULL,
  `montant` decimal(14,2) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `solde_apres` decimal(14,2) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_mouvement` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `mouvement_caisse`
--

INSERT INTO `mouvement_caisse` (`id_mouvement`, `id_poste`, `type_mouvement`, `montant`, `libelle`, `solde_apres`, `id_utilisateur`, `date_mouvement`) VALUES
(1, 1, 'Sortie', 150.00, 'Avance octroyée au fournisseur ID #3', 1800.00, 1, '2026-07-29 22:27:47');

-- --------------------------------------------------------

--
-- Structure de la table `mouvement_stock`
--

CREATE TABLE `mouvement_stock` (
  `id_mouvement` int(11) NOT NULL,
  `date_mouvement` datetime NOT NULL,
  `type_mouvement` enum('ENTREE','SORTIE','TRANSFERT') NOT NULL,
  `quantite` decimal(12,2) NOT NULL,
  `motif` varchar(255) DEFAULT NULL,
  `id_produit` int(11) NOT NULL,
  `id_poste` int(11) NOT NULL,
  `id_achat` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `mouvement_stock`
--

INSERT INTO `mouvement_stock` (`id_mouvement`, `date_mouvement`, `type_mouvement`, `quantite`, `motif`, `id_produit`, `id_poste`, `id_achat`, `id_utilisateur`) VALUES
(1, '2026-07-30 08:01:16', 'ENTREE', 99.70, 'Achat Cacao/Café [N°: ACH-20260730-931A]', 3, 1, 5, 1),
(2, '2026-07-30 15:34:33', 'ENTREE', 49.80, 'Achat Cacao/Café [N°: ACH-20260730-91B7]', 3, 1, 6, 1),
(3, '2026-07-30 16:07:46', 'ENTREE', 9.90, 'Achat Cacao/Café [N°: ACH-20260730-BE67]', 3, 1, 7, 1),
(4, '2026-07-30 16:20:14', 'ENTREE', 10.00, 'Achat Cacao/Café [N°: ACH-20260730-102D]', 2, 1, 8, 1),
(5, '2026-07-30 16:21:05', 'ENTREE', 5.00, 'Achat Cacao/Café [N°: ACH-20260730-3292]', 2, 1, 9, 1),
(6, '2026-07-30 16:26:08', 'ENTREE', 100.00, 'Achat Cacao/Café [N°: ACH-20260730-FC0B]', 3, 1, 10, 1),
(7, '2026-07-30 16:48:07', 'ENTREE', 10.00, 'Achat Cacao/Café [N°: ACH-20260730-1E33]', 3, 1, 11, 1),
(8, '2026-07-31 17:06:30', 'ENTREE', 24.50, 'Achat Cacao/Café [N°: ACH-20260731-1660]', 3, 1, 12, 1);

-- --------------------------------------------------------

--
-- Structure de la table `parametre`
--

CREATE TABLE `parametre` (
  `id_parametre` int(11) NOT NULL,
  `devise` varchar(10) DEFAULT 'USD',
  `prochain_numero_achat` int(11) DEFAULT 1,
  `prochain_numero_recu` int(11) DEFAULT 1,
  `taux_refaction_standard` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `parametre`
--

INSERT INTO `parametre` (`id_parametre`, `devise`, `prochain_numero_achat`, `prochain_numero_recu`, `taux_refaction_standard`) VALUES
(1, 'USD', 1, 1, 0.00);

-- --------------------------------------------------------

--
-- Structure de la table `poste_achat`
--

CREATE TABLE `poste_achat` (
  `id_poste` int(11) NOT NULL,
  `nom_poste` varchar(100) NOT NULL,
  `localisation` varchar(150) DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `statut` enum('Actif','Inactif') DEFAULT 'Actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `poste_achat`
--

INSERT INTO `poste_achat` (`id_poste`, `nom_poste`, `localisation`, `telephone`, `statut`) VALUES
(1, 'Dépôt Central', 'Mangina', '+243000000001', 'Actif'),
(2, 'Poste Mangina', 'Mangina', '+243000000002', 'Actif'),
(3, 'Poste Mabalako', 'Mabalako', '+243000000003', 'Actif'),
(4, 'Poste Oïcha', 'Oïcha', '+243000000004', 'Actif');

-- --------------------------------------------------------

--
-- Structure de la table `prix_jour`
--

CREATE TABLE `prix_jour` (
  `id_prix` int(11) NOT NULL,
  `date_application` date NOT NULL,
  `prix_kg` decimal(12,2) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prix_jour`
--

INSERT INTO `prix_jour` (`id_prix`, `date_application`, `prix_kg`, `id_produit`, `id_utilisateur`) VALUES
(1, '2026-07-30', 4.30, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `producteur`
--

CREATE TABLE `producteur` (
  `id_producteur` int(11) NOT NULL,
  `nom` varchar(60) NOT NULL,
  `postnom` varchar(60) DEFAULT NULL,
  `prenom` varchar(60) DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `adresse` varchar(150) DEFAULT NULL,
  `statut` enum('Actif','Inactif') DEFAULT 'Actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `unite` varchar(20) DEFAULT 'Kg',
  `statut` enum('Actif','Inactif') DEFAULT 'Actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `designation`, `unite`, `statut`) VALUES
(1, 'Cacao', 'Kg', 'Inactif'),
(2, 'Café', 'Kg', 'Actif'),
(3, 'Cacao Conventionnel', 'Kg', 'Actif');

-- --------------------------------------------------------

--
-- Structure de la table `remboursement`
--

CREATE TABLE `remboursement` (
  `id_remboursement` int(11) NOT NULL,
  `date_remboursement` datetime NOT NULL,
  `montant` decimal(14,2) NOT NULL,
  `mode_paiement` varchar(30) DEFAULT NULL,
  `id_dette` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `libelle`) VALUES
(4, 'Acheteur'),
(1, 'Administrateur'),
(6, 'Caissier'),
(5, 'Comptable'),
(3, 'Gérant'),
(2, 'PDG');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id_stock` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_poste` int(11) NOT NULL,
  `quantite` decimal(12,2) DEFAULT 0.00,
  `derniere_mise_a_jour` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id_stock`, `id_produit`, `id_poste`, `quantite`, `derniere_mise_a_jour`) VALUES
(1, 3, 1, 144.50, '2026-07-31 15:25:45'),
(4, 2, 1, 1.00, '2026-07-30 16:14:17'),
(10, 3, 2, 150.00, '2026-07-31 15:25:45');

-- --------------------------------------------------------

--
-- Structure de la table `transfert_stock`
--

CREATE TABLE `transfert_stock` (
  `id_transfert` int(11) NOT NULL,
  `date_transfert` datetime NOT NULL,
  `quantite` decimal(12,2) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `poste_source` int(11) NOT NULL,
  `poste_destination` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `transfert_stock`
--

INSERT INTO `transfert_stock` (`id_transfert`, `date_transfert`, `quantite`, `id_produit`, `poste_source`, `poste_destination`, `id_utilisateur`) VALUES
(1, '2026-07-30 17:42:21', 100.00, 3, 1, 2, 1),
(2, '2026-07-31 17:25:45', 50.00, 3, 1, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(60) NOT NULL,
  `postnom` varchar(60) DEFAULT NULL,
  `prenom` varchar(60) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `mot_passe` varchar(255) NOT NULL,
  `statut` enum('Actif','Inactif') DEFAULT 'Actif',
  `id_role` int(11) NOT NULL,
  `id_poste` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `postnom`, `prenom`, `username`, `mot_passe`, `statut`, `id_role`, `id_poste`) VALUES
(1, 'Angelle', 'Merdy', 'Navipato', 'admin', '$2y$10$Q1I1UXYr23gUHeGR2C5KcepEExlzvibJFOoQXto6PSppLu.Ep2EbO', 'Actif', 1, 1),
(2, 'Gloire', 'Kamavu', 'Gloire', 'Gloire', '$2y$10$M/19C5gU/IPo41vRzAGheeHCGHiiNH3vxv9Pcg9MOOA0bgaLqL99u', 'Actif', 4, 2),
(3, 'Comptable', 'Comptable', 'Comptable', 'Comptable', '$2y$10$a.IAPsxpCM1zu4LcHakcMOhfNcj4rk6y28c/mslXjYnspdcdblWfa', 'Actif', 5, 1),
(4, 'Caisse', 'Caisse', 'Caisse', 'Caisse', '$2y$10$YAkJdPsZJ..mkwa/E51U9.4EETYN6Y9kfl9.wbZBuxyJ.neGJ2Fh.', 'Actif', 6, 1),
(5, 'Gerant', 'Gerant', 'Gerant', 'Gerant', '$2y$10$EB1rBzBtfX2NUeEmUdySw.KL.rqf87Z6tMdAOLAHzs.On1bZWQMzO', 'Actif', 3, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `achat`
--
ALTER TABLE `achat`
  ADD PRIMARY KEY (`id_achat`),
  ADD UNIQUE KEY `numero_achat` (`numero_achat`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_poste` (`id_poste`),
  ADD KEY `id_prix` (`id_prix`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `fk_achat_fournisseur` (`id_fournisseur`);

--
-- Index pour la table `avance`
--
ALTER TABLE `avance`
  ADD PRIMARY KEY (`id_avance`),
  ADD KEY `id_producteur` (`id_producteur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `avance_fournisseur`
--
ALTER TABLE `avance_fournisseur`
  ADD PRIMARY KEY (`id_avance`),
  ADD KEY `fk_avance_fournisseur` (`id_fournisseur`);

--
-- Index pour la table `caisse`
--
ALTER TABLE `caisse`
  ADD PRIMARY KEY (`id_caisse`),
  ADD KEY `id_poste` (`id_poste`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `dette`
--
ALTER TABLE `dette`
  ADD PRIMARY KEY (`id_dette`),
  ADD KEY `id_producteur` (`id_producteur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id_entreprise`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id_fournisseur`);

--
-- Index pour la table `journal_audit`
--
ALTER TABLE `journal_audit`
  ADD PRIMARY KEY (`id_audit`);

--
-- Index pour la table `mouvement_caisse`
--
ALTER TABLE `mouvement_caisse`
  ADD PRIMARY KEY (`id_mouvement`),
  ADD KEY `id_poste` (`id_poste`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `mouvement_stock`
--
ALTER TABLE `mouvement_stock`
  ADD PRIMARY KEY (`id_mouvement`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_poste` (`id_poste`),
  ADD KEY `id_achat` (`id_achat`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `parametre`
--
ALTER TABLE `parametre`
  ADD PRIMARY KEY (`id_parametre`);

--
-- Index pour la table `poste_achat`
--
ALTER TABLE `poste_achat`
  ADD PRIMARY KEY (`id_poste`);

--
-- Index pour la table `prix_jour`
--
ALTER TABLE `prix_jour`
  ADD PRIMARY KEY (`id_prix`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `producteur`
--
ALTER TABLE `producteur`
  ADD PRIMARY KEY (`id_producteur`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `remboursement`
--
ALTER TABLE `remboursement`
  ADD PRIMARY KEY (`id_remboursement`),
  ADD KEY `id_dette` (`id_dette`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id_stock`),
  ADD UNIQUE KEY `id_produit` (`id_produit`,`id_poste`),
  ADD KEY `id_poste` (`id_poste`);

--
-- Index pour la table `transfert_stock`
--
ALTER TABLE `transfert_stock`
  ADD PRIMARY KEY (`id_transfert`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `poste_source` (`poste_source`),
  ADD KEY `poste_destination` (`poste_destination`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_role` (`id_role`),
  ADD KEY `id_poste` (`id_poste`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `achat`
--
ALTER TABLE `achat`
  MODIFY `id_achat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `avance`
--
ALTER TABLE `avance`
  MODIFY `id_avance` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `avance_fournisseur`
--
ALTER TABLE `avance_fournisseur`
  MODIFY `id_avance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `caisse`
--
ALTER TABLE `caisse`
  MODIFY `id_caisse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `dette`
--
ALTER TABLE `dette`
  MODIFY `id_dette` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `id_entreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `journal_audit`
--
ALTER TABLE `journal_audit`
  MODIFY `id_audit` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mouvement_caisse`
--
ALTER TABLE `mouvement_caisse`
  MODIFY `id_mouvement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `mouvement_stock`
--
ALTER TABLE `mouvement_stock`
  MODIFY `id_mouvement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `parametre`
--
ALTER TABLE `parametre`
  MODIFY `id_parametre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `poste_achat`
--
ALTER TABLE `poste_achat`
  MODIFY `id_poste` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `prix_jour`
--
ALTER TABLE `prix_jour`
  MODIFY `id_prix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `producteur`
--
ALTER TABLE `producteur`
  MODIFY `id_producteur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `remboursement`
--
ALTER TABLE `remboursement`
  MODIFY `id_remboursement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `transfert_stock`
--
ALTER TABLE `transfert_stock`
  MODIFY `id_transfert` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achat`
--
ALTER TABLE `achat`
  ADD CONSTRAINT `achat_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `achat_ibfk_3` FOREIGN KEY (`id_poste`) REFERENCES `poste_achat` (`id_poste`),
  ADD CONSTRAINT `achat_ibfk_4` FOREIGN KEY (`id_prix`) REFERENCES `prix_jour` (`id_prix`),
  ADD CONSTRAINT `achat_ibfk_5` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `fk_achat_fournisseur` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`);

--
-- Contraintes pour la table `avance`
--
ALTER TABLE `avance`
  ADD CONSTRAINT `avance_ibfk_1` FOREIGN KEY (`id_producteur`) REFERENCES `producteur` (`id_producteur`),
  ADD CONSTRAINT `avance_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `avance_fournisseur`
--
ALTER TABLE `avance_fournisseur`
  ADD CONSTRAINT `fk_avance_fournisseur` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `caisse`
--
ALTER TABLE `caisse`
  ADD CONSTRAINT `caisse_ibfk_1` FOREIGN KEY (`id_poste`) REFERENCES `poste_achat` (`id_poste`),
  ADD CONSTRAINT `caisse_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `dette`
--
ALTER TABLE `dette`
  ADD CONSTRAINT `dette_ibfk_1` FOREIGN KEY (`id_producteur`) REFERENCES `producteur` (`id_producteur`),
  ADD CONSTRAINT `dette_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `mouvement_caisse`
--
ALTER TABLE `mouvement_caisse`
  ADD CONSTRAINT `mouvement_caisse_ibfk_1` FOREIGN KEY (`id_poste`) REFERENCES `poste_achat` (`id_poste`),
  ADD CONSTRAINT `mouvement_caisse_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `mouvement_stock`
--
ALTER TABLE `mouvement_stock`
  ADD CONSTRAINT `mouvement_stock_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `mouvement_stock_ibfk_2` FOREIGN KEY (`id_poste`) REFERENCES `poste_achat` (`id_poste`),
  ADD CONSTRAINT `mouvement_stock_ibfk_3` FOREIGN KEY (`id_achat`) REFERENCES `achat` (`id_achat`),
  ADD CONSTRAINT `mouvement_stock_ibfk_4` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `prix_jour`
--
ALTER TABLE `prix_jour`
  ADD CONSTRAINT `prix_jour_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `prix_jour_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `remboursement`
--
ALTER TABLE `remboursement`
  ADD CONSTRAINT `remboursement_ibfk_1` FOREIGN KEY (`id_dette`) REFERENCES `dette` (`id_dette`),
  ADD CONSTRAINT `remboursement_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`id_poste`) REFERENCES `poste_achat` (`id_poste`);

--
-- Contraintes pour la table `transfert_stock`
--
ALTER TABLE `transfert_stock`
  ADD CONSTRAINT `transfert_stock_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `transfert_stock_ibfk_2` FOREIGN KEY (`poste_source`) REFERENCES `poste_achat` (`id_poste`),
  ADD CONSTRAINT `transfert_stock_ibfk_3` FOREIGN KEY (`poste_destination`) REFERENCES `poste_achat` (`id_poste`),
  ADD CONSTRAINT `transfert_stock_ibfk_4` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`),
  ADD CONSTRAINT `utilisateur_ibfk_2` FOREIGN KEY (`id_poste`) REFERENCES `poste_achat` (`id_poste`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
