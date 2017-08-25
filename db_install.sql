-- phpMyAdmin SQL Dump
-- version 4.1.13
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 19, 2016 at 05:19 PM
-- Server version: 5.5.47
-- PHP Version: 5.3.17

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `outbreak`
--

-- --------------------------------------------------------

--
-- Table structure for table `assays`
--

CREATE TABLE IF NOT EXISTS `assays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) NOT NULL,
  `technique_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `sample_stage_id` int(11) NOT NULL,
  `before_submit` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_assays_attempts1_idx` (`attempt_id`),
  KEY `fk_assays_techniques1_idx` (`technique_id`),
  KEY `fk_assays_sites1_idx` (`site_id`),
  KEY `fk_assays_schools1_idx` (`school_id`),
  KEY `fk_assays_children1_idx` (`child_id`),
  KEY `fk_assays_sample_stages1_idx` (`sample_stage_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4559 ;

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

CREATE TABLE IF NOT EXISTS `attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lti_user_id` int(11) NOT NULL,
  `lti_resource_id` int(11) NOT NULL,
  `user_role` varchar(255) DEFAULT NULL,
  `start` tinyint(1) NOT NULL DEFAULT '0',
  `alert` tinyint(1) NOT NULL DEFAULT '0',
  `revision` tinyint(1) NOT NULL DEFAULT '0',
  `questions` tinyint(1) NOT NULL DEFAULT '0',
  `sampling` tinyint(1) NOT NULL DEFAULT '0',
  `lab` tinyint(1) NOT NULL DEFAULT '0',
  `hidentified` tinyint(1) NOT NULL DEFAULT '0',
  `nidentified` tinyint(1) NOT NULL DEFAULT '0',
  `report` tinyint(1) NOT NULL DEFAULT '0',
  `research` tinyint(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '48',
  `money` int(11) NOT NULL DEFAULT '200',
  `happiness` int(11) NOT NULL DEFAULT '3',
  `token` varchar(255) DEFAULT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_attempts_lti_users1_idx` (`lti_user_id`),
  KEY `fk_attempts_lti_resources1_idx` (`lti_resource_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=292 ;

-- --------------------------------------------------------

--
-- Table structure for table `attempts_schools`
--

CREATE TABLE IF NOT EXISTS `attempts_schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `acuteDisabled` tinyint(1) NOT NULL DEFAULT '0',
  `convalescentDisabled` tinyint(1) NOT NULL DEFAULT '0',
  `returnTripOk` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_attempts_has_schools_schools1_idx` (`school_id`),
  KEY `fk_attempts_has_schools_attempts1_idx` (`attempt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=163 ;

-- --------------------------------------------------------

--
-- Table structure for table `children`
--

CREATE TABLE IF NOT EXISTS `children` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_children_schools1_idx` (`school_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `children`
--

INSERT INTO `children` (`id`, `code`, `name`, `school_id`, `order`, `created`, `modified`) VALUES
(1, 'ja', 'Jennifer A', 1, 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 'js', 'Jason S', 1, 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(3, 'kt', 'Kasim T', 1, 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(4, 'dl', 'Dawn L', 1, 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(5, 'jb', 'Johnny B', 2, 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(6, 'ai', 'Ann I', 2, 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(7, 'jo', 'Jamal O', 2, 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `lti_contexts`
--

CREATE TABLE IF NOT EXISTS `lti_contexts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lti_key_id` int(11) NOT NULL,
  `lti_context_id` varchar(255) DEFAULT NULL,
  `lti_context_label` varchar(255) DEFAULT NULL,
  `lti_context_title` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lit_contexts_lit_keys_idx` (`lti_key_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `lti_keys`
--

CREATE TABLE IF NOT EXISTS `lti_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oauth_consumer_key` varchar(255) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `lti_resources`
--

CREATE TABLE IF NOT EXISTS `lti_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lti_key_id` int(11) NOT NULL,
  `lti_context_id` int(11) NOT NULL,
  `lti_resource_link_id` varchar(255) NOT NULL,
  `lti_resource_link_title` varchar(255) DEFAULT NULL,
  `lti_resource_link_description` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lit_contexts_lit_keys_idx` (`lti_key_id`),
  KEY `fk_lti_resources_lti_contexts1_idx` (`lti_context_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `lti_users`
--

CREATE TABLE IF NOT EXISTS `lti_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lti_key_id` int(11) NOT NULL,
  `lti_user_id` varchar(255) NOT NULL,
  `lti_eid` varchar(255) DEFAULT NULL,
  `lti_displayid` varchar(255) DEFAULT NULL,
  `lti_lis_person_contact_email_primary` varchar(255) DEFAULT NULL,
  `lti_lis_person_name_family` varchar(255) DEFAULT NULL,
  `lti_lis_person_name_full` varchar(255) DEFAULT NULL,
  `lti_lis_person_name_given` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lit_contexts_lit_keys_idx` (`lti_key_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=170 ;

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE IF NOT EXISTS `marks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lti_resource_id` int(11) NOT NULL,
  `lti_user_id` int(11) NOT NULL,
  `mark` varchar(255) DEFAULT NULL,
  `comment` text,
  `marker_id` int(11) DEFAULT NULL,
  `revision` tinyint(1) NOT NULL DEFAULT '0',
  `locked` datetime DEFAULT NULL,
  `locker_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_marks_lti_resources1_idx` (`lti_resource_id`),
  KEY `fk_marks_lti_users1_idx` (`lti_user_id`),
  KEY `fk_marks_lti_users2_idx` (`marker_id`),
  KEY `fk_marks_lti_users3_idx` (`locker_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=345 ;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) NOT NULL,
  `technique_id` int(11) DEFAULT NULL,
  `note` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notes_techniques1_idx` (`technique_id`),
  KEY `fk_notes_attempts1_idx` (`attempt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=540 ;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `background` text,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `menu`, `name`, `background`, `order`, `created`, `modified`) VALUES
(1, 'Q1', 'Neuraminidase', 'Are the following statements concerning neuraminidase true or false?', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 'Q2', 'qRT-PCR', 'Are the following statements concerning qRT-PCR true or false?', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(3, 'Q3', 'Haemagglutinin', 'Are the following statements concerning haemagglutinin true or false?', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(4, 'Q4', 'Haemagglutination', 'Are the following statements concerning haemagglutination true or false?', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(5, 'Q5', 'The HAI test', 'Are the following statements concerning the haemagglutination-inhibition (HAI) test true or false?', 5, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(6, 'Q6', 'Interpreting an HAI assay', '<p>This images represents a completed HAI assay.</p><p><img src="../../img/question6_hai_results.png" /><br /><span class="image-caption">"HAI assays results" by Prof William James can be resued under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">CC BY-NC-SA License</a> <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons Licence" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/80x15.png" /></a></span></p><p>Are the following statements concerning this assay true or false?</p>', 6, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(7, 'Q7', 'The Plaque Assay', 'Are the following statements concerning the Plaque Assay true or false?', 7, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(8, 'Q8', 'Assay Controls', 'Are the following statements concerning Assay Controls true or false?', 8, '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `question_answers`
--

CREATE TABLE IF NOT EXISTS `question_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) NOT NULL,
  `stem_id` int(11) NOT NULL,
  `question_option_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_answers_attempts1_idx` (`attempt_id`),
  KEY `fk_answers_stems1_idx` (`stem_id`),
  KEY `fk_answers_question_options1_idx` (`question_option_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11171 ;

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE IF NOT EXISTS `question_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `option` varchar(255) DEFAULT NULL,
  `order` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_question_options_questions1_idx` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `option`, `order`, `created`, `modified`) VALUES
(1, 1, 'True', '1', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 1, 'False', '2', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(3, 2, 'True', '1', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(4, 2, 'False', '2', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(5, 3, 'True', '1', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(6, 3, 'False', '2', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(7, 4, 'True', '1', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(8, 4, 'False', '2', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(9, 5, 'True', '1', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(10, 5, 'False', '2', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(11, 6, 'True', '1', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(12, 6, 'False', '2', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(13, 7, 'True', '1', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(14, 7, 'False', '2', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(15, 8, 'True', '1', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(16, 8, 'False', '2', '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `question_scores`
--

CREATE TABLE IF NOT EXISTS `question_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scores_attempts1_idx` (`attempt_id`),
  KEY `fk_scores_questions1_idx` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1986 ;

-- --------------------------------------------------------

--
-- Table structure for table `question_stems`
--

CREATE TABLE IF NOT EXISTS `question_stems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `text` text,
  `question_option_id` int(11) NOT NULL,
  `feedback` text,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_stems_questions1_idx` (`question_id`),
  KEY `fk_stems_question_options1_idx` (`question_option_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `question_stems`
--

INSERT INTO `question_stems` (`id`, `question_id`, `text`, `question_option_id`, `feedback`, `order`, `created`, `modified`) VALUES
(1, 1, 'Anti-N ELISA measures the concentration of virion-associated neuraminidase in patients'' serum', 2, 'ELISAs can measure either antigen or antibody, but an Anti-N ELISA measures antibodies directed to the N antigen (neuraminidase). Circulating antibodies are found in the blood and serum derived from the blood, but influenza viruses are not. (This would vary for different viruses depending on their tropism.)', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 1, 'Anti-N ELISA tests for previous exposure to a virus or vaccine', 1, 'The assay tests for antibodies generated following either exposure to the virus or to a vaccine against the virus. This is an indirect way of detecting exposure, and exposure to a particular serotype of virus can be most clearly inferred if samples are available at different time points following a challenge to the immune system (e.g.  by comparing sera collected during the acute and then the convalescent phases of an infection and looking for a rising titre of antibody).', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(3, 1, 'Antigenic shift can result in the circulation of viruses with antigenically new forms of neuraminidase', 1, 'Antigenic <strong>drift</strong> is the sequential accumulation of mutations which subtly alter the immunogenicity of a particular serotype of a virus. Circulating viruses evolve away from immune responses generated to previous infections but they stay within the general "serotype" designation of H1, H3 etc. Hosts therefore retain partial immunity from previous exposures. In contrast, antigenic <strong>shift</strong> occurs when the virus replaces the genes of one or more highly immunogenic proteins (typically HA and NA) with substantially different versions, typically from an influenza virus that was circulating in a different species. This changes the serotype of the virus, removing the protection offered by previous infection or vaccination.', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(4, 1, 'Neuraminidase assists new virions in escaping from the host cell', 1, 'The neuraminidase enzyme cleaves the terminal sialic acid residues off the glycoproteins present on surface of cells as well as within the mucus of the respiratory tract. As the sialic acids are the target of the viral HA protein this helps the virus diffuse through the mucus towards its target cell at the beginning of infection, and later in an infection allows newly-formed viruses to be efficiently released from the infected cell.', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(5, 1, 'Neuraminidase is a nucleic acid polymerase', 2, 'Neuraminidases are glycoside hydrolases which catalyse cleavage of glycosidic bonds in sugar chains, specifically the glycosidic linkages of neuraminic acids. Influenza virus neuraminidase cleaves the terminal sialic acid residue from the sugar chains on glycoproteins and glycolipids.', 5, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(6, 1, 'Neuraminidase is immunologically identical in all strains of influenza', 2, 'Neuraminidase is located on the surface of both infected cells and virions, and as such is visible to the immune response and is highly immunogenic. As with HA, NA is therefore highly variable in order to escape pre-existing immunity. A number of different serotypes of NA have been characterised, though all influenza viruses circulating in humans carry NAs from serotypes N1 and N2.', 6, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(7, 2, 'Nose and throat swabs from convalescent phase patients are a good source of samples for qRT-PCR.', 4, '<p>RT-PCR assays for viral nucleic acid, specifically viral RNA (a simple qPCR would be used for DNA containing viruses), and as such requires the presence of actual viral particles. As flu is an infection of the upper respiratory tract the virions can be isolated from the nose and throat.</p><p>Once the patient has recovered the immune system has cleared the virus and therefore there are no virions to detect by qRT-PCR at any sampling site.</p>', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(8, 2, 'qRT-PCR can be used to measure the concentration of viral RNA in patient samples.', 3, '<p>Using any sample containing viral particles the qRT-PCR (or qPCR for DNA viruses) can accurately provide a quantification of the amount of viral nucleic acid. The process uses the knowledge that each round of PCR (cycle) results in a doubling of the total amount of DNA, and so if we know the cycle number at which a particular sample appears positive (Ct value = cycle number (C) at which the fluorescence reaches a target threshold (t) value), you can compare that Ct value to a standard curve of known amounts of viral RNA and calculate the concentration of viral RNA in your unknown sample.</p><p>qRT-PCR cannot be used to measure protein or antibody response.</p>', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(9, 2, 'Serum samples from acute phase patients are usually positive for influenza by qRT-PCR.', 4, 'During the course of a normal influenza infection only cells of the upper respiratory tract are infected and these shed viruses from their apical surface, releasing virus back into the respiratory mucus. This means that virions are not generally found within the blood, except (in rare and mostly fatal cases (e.g. a fatal H5N1 avian influenza case).', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(10, 2, 'Serum samples from convalescent patients are a good source of samples for qRT-PCR.', 4, 'During the course of a normal influenza infection only cells of the upper respiratory tract are infected and these shed viruses from their apical surface, releasing virus back into the respiratory mucus. This means that virions are not generally found within the blood, except (in rare and mostly fatal cases (e.g. a fatal H5N1 avian influenza case). Also once the patient has recovered the immune system will have cleared all active viral replication from the patient and no sample even from the nose or throat will be positive by qRT-PCR.', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(11, 2, 'qRT-PCR can be used to distinguish different influenza serotypes.', 3, 'Different serotypes are identified by virtue of different antibodies ability to bind the HA or NA. This binding is dependent on the structure of the viral proteins, which in turn is dependent on their amino acid sequence, and therefore on their viral RNA sequence. Hence, serotypes can be distinguished by not only antibody binding, but by their primary nucleotide sequence. The qRT-PCR reaction requires complementary primers to reverse transcribe the target RNA and amplify a stretch of DNA from it, and the product is detected using probes that bind to complementary sequences in this amplicon. As different primer and probe sets will only recognise very specific sequences, they can be used to identify the presence of different serotypes.', 5, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(24, 3, 'Haemagglutinin adopts a fusion-promoting conformation at pH values above 8.2', 6, 'The virus enters target cells through receptor mediated endocytosis, and HA only promotes fusion as an endosome alters its pH. As endosomes mature protons are pumped in and the pH lowers. Once a pH of about 5 is reached (actual pH varies by strain from 4.6 to 6) the HA protein alters its conformation to insert a fusion peptide into the endosomal membrane. It then folds back on itself, bringing the viral and endosomal membranes together and encouraging membrane fusion and the escape of the viral contents into the cytoplasm.', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(25, 3, 'Haemagglutinin binds to sialic acid residues', 5, 'The receptor bound by different viruses plays a major role in determining the host range, tissue tropism, pathogenicity and transmissibility of different viruses. The HA protein binds to sialic acid containing cell surface receptors. Specifically human adapted viral strains preferentially utilise &alpha;(2,6)-linked sialic acid residues found in the upper respiratory tract (and are therefore easily shed into the environment and transmitted from person to person) whereas avian influenza (e.g. H5N1) uses &alpha;(2,3)-linked sialic acid residues, which in humans are found only in the lower respiratory tract (making them less transmissible).', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(26, 3, 'Haemagglutinin changes conformation at low pH', 5, 'Viruses that enter cells through receptor-mediated endocytosis often use the natural successive lowing of the pH within the maturing endosome as a mechanistic trigger to time the fusion event. The HA protein within the viral membrane is in a metastable state (not in its lowest free energy state, but like a locked loaded spring) and upon exposure to external stimuli (change in pH for HA), it is able to transition to the lowest energy state via a conformational change that inserts the fusion peptide into the endosomal membrane, initiating the fusion reaction and resulting in the viral core gaining access to the cytoplasm.', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(27, 3, 'Haemagglutinin cleaves n-acetyl neuraminic acid from oligosaccharides', 6, 'N-acetyl neuraminic acid or sialic acid is cleaved from oligosaccarides on glycoproteins by the viral neuraminidase.', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(28, 3, 'Haemagglutinin enables the virion to attach to the cell surface', 5, 'All viruses require a viral attachment protein that enables the virus to bind its target cell using a specific receptor e.g. sialic acid. For influenza viruses this is carried out by HA, which in circulating human strains of the virus bind to &alpha;(2,6)-linked sialic acid residues in oligosaccaride chains found on cell surface glycoproteins of the upper respiratory tract.', 5, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(29, 3, 'Haemagglutinin is an antigen that is variable between subtypes', 5, 'HA is an integral membrane protein, most of which is found on the outside of the virion and is therefore highly visible to the immune response. Anti-HA antibodies (both secreted IgA and plasma IgG) form the primary adaptive immune response to influenza infection, and as such there is selective pressure to evolve variants in this protein. Sufficiently different protein variants can be grouped according to their detection by different antibodies and are classed as different serotypes (e.g. H1, H2, H3, H5, etc.).', 6, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(30, 4, 'Haemagglutination can be used to titrate virus concentration', 7, 'In sufficient concentrations, influenza virions will  cross-link red blood cells to each other via interactions between sialic acids on the red blood cell glycoproteins and the multiple HA proteins in each virion. If the virus is sequentially diluted eventually there will not be enough present to agglutinate the red blood cells. By observing how many dilutions are required to lose this haemagglutination response it is possible to provide a standardised measure of viral titre (i.e. the concentration of virus) in an unknown sample. As haemagglutination is very dependent on experimental conditions, the units of this titration (haemagglutinating units (HAU) /ml) are somewhat arbitrary.', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(31, 4, 'Haemagglutination is an important mechanism of influenza pathogenesis', 8, 'As, in a normal infection, influenza virions are restricted to the upper respiratory tract, they do not enter the blood and therefore do not encounter red blood cells. Therefore, haemagglutination is not a mechanism of viral pathogenesis during influenza infection. It is however a useful, if relatively non-specific, technique to detect any virus that can bind sialic acid residues.', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(32, 4, 'Haemagglutination is inhibited by antibodies to haemagglutinin', 7, '<p>As the HA proteins on the virus are integral to the cross-linking of red blood cells in the haemagglutination assay, if they are "neutralised" by antibodies directed to HA then no haemagglutination will occur and the assay will be negative. This is useful, as it can be used to titre anti-HA antibodies in patient serum. The higher the titre of antibodies, the more the serum will have to be diluted until it no longer inhibits haemagglutination. This forms the basis of the haemagglutination inhibition (HI) assay, which measures anti-HA antibody titres and can be used to assess seroconversion.</p><p>The viral HA protein is the only protein required to initiate haemagglutination, so adding antibodies to a different viral protein, even NA, has no effect on the response.</p>', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(33, 4, 'Haemagglutination is recognised by the formation of a ''shield'' in the base of a microtitre plate', 7, 'A ''button'' on the bottom of the microtitre plate results when  red blood cells sediment under gravity into a dense pellet. Haemagglutination is the cross-linking of multiple red blood cells via the HA proteins on virions; once cross-linked the red blood cells can''t settle to the bottom of the plate as freely and get stuck on the sides, thereby appearing as a diffuse ''shield''.', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(34, 4, 'Haemagglutination results from the cross-linkage of erythrocytes by virus surface glycoproteins', 7, 'Erythrocytes contain multiple glycolipids and glycoproteins that have a terminal sialic acid residue. The HA of influenza binds the sialic acid, and by virtue of having multiple HA molecules per particle the virus effectively cross-links multiple erythrocytes into a large conglomerate.', 5, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(35, 4, 'Haemagglutination results from the insertion of pores into the red cell membrane', 8, 'Although the viral HA is able to create a pore in an endosomal membrane by initiating fusion between the viral membrane and that of the endosome, this is a pH-dependent step. Haemagglutination is simply a binding reaction between the HA and sialic acid residues, which forms a mat of cross-linked virus particles and erythrocytes.', 6, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(36, 5, 'The HAI test involves concentrating virions by centrifugation', 10, 'Gravity is sufficient to pellet the red blood cells in this assay. To pellet virions, extremely high-speed ultracentrifugation would be require.', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(37, 5, 'The HAI test involves testing serial dilutions of patient sera', 9, 'Serial dilutions are important to find the "end-point" dilution at which a particular reaction no longer takes place (i.e. how far do I need to dilute a sample before I lose the inhibition of haemagglutination). The assay detects anti-HA antibodies such as the IgG present in a patient''s serum.', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(38, 5, 'The HAI test is an assay for antibodies that bind to a specific viral toxin', 10, 'The assay is a test for antibodies, but ones that bind to HA of influenza, which is not a toxin but a viral glycoprotein.', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(39, 5, 'The HAI test is an assay for antibodies that bind to specific antigenic variants of haemagglutinin', 9, 'As serotypes are defined by the ability of a particular strain of virus to be neutralised by a particular type of antibody. By using different viruses to cause haemagglutination in different wells, it is possible to probe a single serum sample for antibodies to different serotypes of virus. Each viral serotype will only be neutralised by its cognate antibody.', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(40, 5, 'The HAI test takes as its end point the first dilution failing to inhibit haemagglutination', 10, 'The end point is recorded as the last dilution showing inhibition.', 5, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(41, 5, 'The HAI test takes as its end point the last dilution showing inhibition of haemagglutination', 9, 'The last well showing a positive inhibition of the haemagglutination reaction can be said to contain enough antibody to inhibit a known standard amount of virus from agglutinating a standard amount of red blood cells. Using the amount of sample added to each well and the dilution factor providing the final positive response it is possible to calculate the amount of antibody in the starting sample as an arbitrary but standardised measurement.', 6, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(42, 5, 'The HAI test produces a ''button'' as a positive result (presence of antibody)', 9, 'A button is produced from settling red blood cells. Enough virus is added to each well to cause haemagglutination (producing a shield), so a button can only result if antibodies are present to neutralise the virus and prevent haemagglutination - thus a button is a positive result.', 7, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(43, 5, 'The HAI test produces a ''shield'' as a positive result (presence of antibody).', 10, 'A shield is the product of red blood cells being hindered from forming a pellet by the cross linking of the cells via viruses. This occurs when insufficient antibodies are present to neutralise the viruses, and therefore a shield is a negative result.', 8, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(44, 6, 'Patient ''JB'' was probably suffering from infection by an H1 strain of influenza during the acute phase', 11, '<p>With suitable samples the HAI assay indicates the history of influenza infections for a patient, and if acute phase samples are present the timings of infections can also be resolved. Patient JB does not have antibodies to H1 during acute infection but has developed them by the time the convalescent sample was taken. The correlation of recovery with seroconversion suggests that JB was suffering from an H1 serotype influenza virus.</p><p>Although there is very little response to H1 antigen in JB acute serum (antibody response has not had time yet to produce antibodies during the acute phase of infection), in the convalescent serum there are high titres of antibody capable of preventing haemagglutination even after diluting 1/512. </p><p>There is no difference between the end-point titration of JB acute serum and JB convalescent serum when assayed against H3 antigen, so no additional immune response had been mounted against H3 antigen in the time frame of sampling. The low level response detected (positive at 1/4 dilution) could result from cross-reactivity by antibodies previously raised against other influenza serotypes.</p>', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(45, 6, 'The end point of HAI in sample C corresponds to a test serum dilution of 1/1024', 12, 'The last dilution to produce a positive result (button) is the end point, which for JB sample C is 1/512.', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(46, 6, 'The evidence shows that patient ''JB'' is likely to be immune to H7 and H5 strains of influenza', 12, 'No data is provided by this assay on the exposure of JB to H5 or H7 strains. The assay is only able to indicate antibodies specific to the antigens tested (for this assay only H1 and H3 were used).', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(47, 6, 'The presence of a falling HAI titre between acute and convalescent serum samples taken from the same subject indicates a recent infection by that strain of virus', 12, 'Antibodies are not produced until the later stages of influenza infection when the virus is being cleared. So during the acute phase no antibodies (except pre-existing ones) will be detected. However, towards the later stages of infection antibodies are produced, help clear the virus and remain to help with future challenges by similar viruses. Therefore, a rising titre (more than three-fold increase in titre from acute sample to convalescent sample) of haemagglutination-inhibiting (HAI) antibodies indicates recent infection with the corresponding serotype of virus. If only convalescent sera are available, no conclusions can be drawn about the timing of the infection, though it can still be useful to know what viruses a person has been exposed to.', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(48, 7, 'The plaques seen are individual cells transformed by the virus to grow into colonies', 14, 'The end point of the plaque assay is a monolayer of cells that has been stained for protein with Coomassie Blue, therefore the observed plaques are indicative of cell loss.', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(49, 7, 'The plaques are holes in the cell monolayer created by a viral infection spreading to neighbouring cells and leading to cell death', 13, 'The assay requires that infected cells die and fall off the plate so that the effect of the virus can be observed, without this lytic effect the plaque assay will not work (unless another form of observation is possible i.e. immunostaining the plate).', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(50, 7, 'The plaque assay is qualitative not quantitative', 14, 'As well as being a good assay to show the presence of a virus, with appropriate dilutions, counting of plaques and back calculations the plaque assay can be used to quantify (titrate) the number of plaque forming units per ml (PFU/ml) in a solution.', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(51, 7, 'The plaque assay can be used for many different viruses', 13, 'As long as the virus is viable, replication competent, can infect the cells used in the monolayer and causes visible cytopathic effects (CPE, e.g. death) then the virus can be quantified.', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(52, 7, 'The plaque assay allows us to visualise by eye the effects of a single virus', 13, 'Individual virions are too small to see by eye, or even by light microscopy, but over time as the virus replicates and kills more cells we begin to be able to observe the effect of the virus by eye. The assay does assume that a single virus particle is able to initiate an infection (not universally true, but typical for animal viruses) and that any complexes of virus particles were dispersed by dilution.', 5, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(53, 8, 'A positive control is useful to show that your sample is positive', 16, 'A positive control is used to identify false negatives. When testing a sample, a negative result could mean that the sample is negative, or that there is an error in the assay. A positive control is a known sample that contains the "substance of interest" and therefore always gives a positive result. It demonstrates that your assay has worked adequately and that all your reagents are still working. If the positive control does not result in a positive signal it indicates that your experiment has failed for some reason, and negative results when testing the samples would therefore be meaningless.', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(54, 8, 'The same positive control can be used for all assays', 16, 'Each assay is testing for a very different, specific "feature", which the controls must match. For example, you need samples containing nucleic acids for a PCR based positive control, and viable virus if the assay relies on virus replication.', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(55, 8, 'A negative control is unimportant', 16, 'A negative control indicates the background for your particular assay, and shows that there is no general contamination in any of the assay components. Contamination would produce false positives and a high background reduces the dynamic range of your assay.', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(56, 8, 'A good control for the HAI assay would be serum from a convalescent influenza patient', 15, 'Though for it to work the serotype of the virus infecting the patient would need to match the serotype of the virus used in the HAI assay.', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(57, 8, 'A good control for the HA assay would be purified HA antigen', 16, 'The HA assay relies on the virus binding multiple red blood cells, if only HA antigen was used it would bind to the red blood cells but would not be able to cross-link multiple cells together as the HAs would not be linked by being on a virus. Intact virions would be needed.', 5, '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) NOT NULL,
  `revision` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(45) DEFAULT NULL,
  `serialised` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reports_attempts1_idx` (`attempt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21552 ;

-- --------------------------------------------------------

--
-- Table structure for table `reports_sections`
--

CREATE TABLE IF NOT EXISTS `reports_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `text` text,
  PRIMARY KEY (`id`),
  KEY `fk_reports_sections_reports1_idx` (`report_id`),
  KEY `fk_reports_sections_sections1_idx` (`section_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=129307 ;

-- --------------------------------------------------------

--
-- Table structure for table `research_techniques`
--

CREATE TABLE IF NOT EXISTS `research_techniques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `menu` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `video` varchar(2000) DEFAULT NULL,
  `content` text,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `research_techniques`
--

INSERT INTO `research_techniques` (`id`, `code`, `menu`, `name`, `video`, `content`, `order`, `created`, `modified`) VALUES
(1, 'em', 'EM', 'Electron Microscopy', 'https://www.youtube.com/embed/7GB4HyPE1AA?rel=0', '<p><strong>Paper:</strong> <a href="http://dx.doi.org/10.1073/pnas.1315068110" target="_blank">Isolation and characterization of the positive-sense replicative intermediate of a negative-strand RNA virus, York et al, 2013</a></p>\n<p>Using EM it is possible to visualise the virion itself, to observe the gross substructure and viral envelope glycoprotein spikes and the constituent components.</p>\n<p>Pseudocolour of Influenza with orange envelope glycoproteins, yellow membrane and purple RNPs:</p>\n<p><img src="../../img/techniques/em/flu_colour.png" style="width: 100%; max-width: 450px" alt="Influenza Electron Micrograph" /><br />\n<span class="image-caption">Photo Credit: Cynthia Goldsmith; Content Providers(s): CDC/Dr. Erskine. L. Palmer; Dr. M. L. Martin [Public domain], via <a href="https://commons.wikimedia.org/wiki/File:Influenza_virus_particle_color.jpg" target="_blank">Wikimedia Commons</a></span>\n</p>\n<p>Also using averaging of many similar particles it is possible to see even smaller units of the virus with enough resolution to propose models of how the proteins are arranged, e.g. the viral proteins and RNA within the RNP complex as shown in the image below:</p>\n<p><img src="../../img/techniques/em/flu_structural_organisation.png" alt="Structural organization of the influenza virus cRNP replicative intermediate. From York et al, 2013" /><br /><span class="image-caption">Ashley York et al. PNAS 2013;110:E4238-E4245. &copy; 2013 by National Academy of Sciences</span>\n</p>', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 'facs', 'FACS', 'Fluorescence-activated Cell Sorting (FACS)', 'https://www.youtube.com/embed/nTSoU5UAPvY?rel=0', '<p><strong>Paper:</strong> \n<a href="http://dx.doi.org/10.1016/j.chom.2014.10.010" target="_blank">Macrophage Infection via Selective Capture of HIV-1-Infected CD4+ T Cells, Baxter et al, 2014</a></p>\n<p>Flow cytometry can be used to detect the presence of viral replication or gene expression as well as effects the virus has on cellular gene expression. Here we are observing an HIV vector encoding the fluorescent reporter gene, GFP. Upon infection a cell, here a CD14 (APC)-stained macrophage, becomes fluorescent and can be detected and quantified by flow cytometry.</p>\n<p><img src="../../img/techniques/facs/facs.png" style="width: 100%" alt="Flow cytometry" /><br />\n<span class="image-caption">"Example flow cytometry results" by Dr Kenny Moore can be resued under the <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">CC BY-NC-SA License</a> <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons Licence" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/80x15.png" /></a></span></p>', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(3, 'wb', 'WB', 'Western Blotting', 'a:3:{i:0;a:2:{s:5:"title";s:6:"Part I";s:3:"url";s:47:"https://www.youtube.com/embed/GJJGNOdhP8w?rel=0";}i:1;a:2:{s:5:"title";s:7:"Part II";s:3:"url";s:47:"https://www.youtube.com/embed/JcN0EkcHrKk?rel=0";}i:2;a:2:{s:5:"title";s:8:"Part III";s:3:"url";s:47:"https://www.youtube.com/embed/IoVzpL_heFo?rel=0";}}', '<p>Western blotting can be used to identify and semi-quantitatively measure the amount of a specific protein, or protein modification such as phosphorylation. Here we are seeing a blot probing for a cellular host restriction factor, SAMHD1 and observing the effect type 1 interferon has on its phosphorylation within patient-derived macrophages.</p><p><img src="../../img/techniques/wb/wb.png" style="width: 100%; max-width: 500px;" alt="Western blotting" /></p>', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(4, 'seq', 'Sequencing', 'Sequencing', NULL, '<p><strong>Paper:</strong> <a href="http://dx.doi.org/10.1128/JVI.02765-13" target="_blank">Airborne Transmission of Highly Pathogenic H7N1 Influenza Virus in Ferrets, Sutton et al, 2014</a></p>\n<p>Sequencing is the process of determining the precise order of the four bases (nucleotides) within an RNA or DNA molecule. Knowledge of the nucleotide sequence is indispensable for biotechnology, phylogenetic analyses, the development of new vaccines, and the identification of viral strains. The ability to track the evolution of a virus at the sequence level allows numerous inferences, such as the identification of areas of the genome that are constrained due to essential motifs (either protein, RNA of DNA motifs), the relative rate of sequence divergence and therefore the timing of the origin of the epidemic, and key mutations that are required to make evolutionary changes in pathogenesis (see Linster 2014).</p>\n<p>The first sequencing methods appeared in the 1970s based on laborious chromatography experiments. Since then, the technique has undergone a number of key revolutions that have made it faster, cheaper and more reliable.</p>\n<p>The most routinely used sequencing method is called <a href="https://en.wikipedia.org/wiki/Sanger_sequencing" target="_blank">Sanger sequencing</a>, after its inventor Fred Sanger. It relies on the selective incorporation of four, differently fluorescently labeled chain-terminating <a href="https://en.wikipedia.org/wiki/Dideoxynucleotide" target="_blank">dideoxynucleotides</a> (ddNTPs). These chain-terminating nucleotides lack the 3''-<a href="https://en.wikipedia.org/wiki/Hydroxyl" target="_blank">OH</a> group that is required for phosphodiester bond formation between two nucleotides. When a polymerase incorporates such a ddNTP, extension of the product strand is terminated. Moreover, when the radio between normal NTPs and ddNTPs is chosen correctly, the termination of extension yields a random pool of products of varying length that have one of the four fluorescent ddNTP at their 3'' terminus. When these are next separated by length using capillary electrophoresis, the sequence of the template can be read using lasers that can excite the fluorophores.</p><p>Since the completion of the <a href="http://www.sciencemag.org/content/291/5507/1304.long" target="_blank">draft release</a> of the <a href="https://en.wikipedia.org/wiki/Human_Genome_Project" target="_blank">Human Genome Project</a>, commercial next generation sequencing has been become available. This involves high-throughput sequencing on the order of thousands to millions of parallel reactions, allowing researchers to rapidly sequence whole genomes or transcriptomes. The most commonly used methods rely on Illumina, Ion Torrent, and SOLiD methods.</p>', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(5, 'vs1', 'Epidemic Simulator', 'Epidemic Simulator', NULL, '<p>This simulator should appear below, but may not, depending on your browser settings. If it does not appear, or does not work properly, you can access it at <a href="http://vax.herokuapp.com/" target="_blank">vax.herokuapp.com/</a></p><p>If you are using Internet Explorer and are unable to see the simulator below, you may see a warning about only secure content being displayed. If so, you can click the "Show all content" button. The iCase will be reloaded (don''t worry, your progress will be saved), but when you come back to this page you should see the simulator below.</p><iframe src="http://vax.herokuapp.com/" style="width: 100%; height: 870px;">iFrames not supported</iframe>', 5, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(6, 'vs2', 'Herd Immunity Simulator', 'Herd Immunity Simulator', NULL, '<p>This simulator should appear below, but may not, depending on your browser settings. If it does not appear, or does not work properly, you can access it at <a href="http://www.software3d.com/Home/Vax/Immunity.php" target="_blank">www.software3d.com/Home/Vax/Immunity.php</a></p><p>If you are using Internet Explorer and are unable to see the simulator below, you may see a warning about only secure content being displayed. If so, you can click the "Show all content" button. The iCase will be reloaded (don''t worry, your progress will be saved), but when you come back to this page you should see the simulator below.</p><iframe src="http://www.software3d.com/Home/Vax/Immunity.php" style="width: 100%; height: 990px;">iFrames not supported</iframe>', 6, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(7, 'papers', 'Papers', 'Interesting Influenza Virus Papers', NULL, '<p><a href="http://dx.doi.org/10.1016/j.cell.2014.02.040" target="_blank">Identification, characterization, and natural selection of mutations driving airborne transmission of A/H5N1 virus;\n</a> Linster 2014. Cell</p>\n<p><a href="http://dx.doi.org/10.7554/eLife.03300" target="_blank">The inherent mutational tolerance and antigenic evolvability of influenza hemagglutinin;\n</a> Thyagarajan and Bloom 2014. Elife.</p>', 7, '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `samples`
--

CREATE TABLE IF NOT EXISTS `samples` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `sample_stage_id` int(11) NOT NULL,
  `before_submit` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_samples_sites1_idx` (`site_id`),
  KEY `fk_samples_attempts1_idx` (`attempt_id`),
  KEY `fk_samples_schools1_idx` (`school_id`),
  KEY `fk_samples_children1_idx` (`child_id`),
  KEY `fk_samples_sample_stages1_idx` (`sample_stage_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2841 ;

-- --------------------------------------------------------

--
-- Table structure for table `sample_stages`
--

CREATE TABLE IF NOT EXISTS `sample_stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stage` varchar(255) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sample_stages`
--

INSERT INTO `sample_stages` (`id`, `stage`, `order`, `created`, `modified`) VALUES
(1, 'acute', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 'convalescent', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE IF NOT EXISTS `schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `details` text,
  `acute` tinyint(1) NOT NULL DEFAULT '1',
  `convalescent` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `code`, `name`, `details`, `acute`, `convalescent`, `order`, `created`, `modified`) VALUES
(1, 'school1', 'Cabot Road Primary School, Westbridge', 'Infected December 2015. Children are convalescent.', 0, 1, 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 'school2', 'St. Bride''s Primary School, Tovington', 'Infected January 2016. Children are still symptomatic.', 1, 1, 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `instructions` text,
  `order` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `code`, `label`, `instructions`, `order`, `created`, `modified`) VALUES
(1, 'patients', 'Clinical presentations and summary patient information', 'Who was tested and why?', '1', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 'investigations', 'Investigations', 'What tests did you perform and why?', '2', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(3, 'results', 'Key Results', 'What were the results (HI assay titers, virus serotype confirmation etc)?', '3', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(4, 'conclusions', 'Conclusions and Recommendations', 'Given the results you have just outlined, what do you think is a suitable response? Do we have vaccines that will combat this virus? (WHO recommendations can be found here <a href="http://www.who.int/influenza/vaccines/virus/recommendations/en/" target="_blank">http://www.who.int/influenza/vaccines/virus/recommendations/en/</a>, but our stocks contain H1N1, H3N2 and Influenza B.)', '4', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(5, 'other', 'Any other business', 'Any other useful comments to help put the results into context for any reader of the report or other future investigations that you would suggest for follow up.', '5', '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(6, 'summary', 'Summary', 'In less than 250 words provide a succinct account of the investigation as a whole, including what was done, why it was done, what was found out and what you recommend as a course of action. Imagine a busy manager (or in this case Minister) glancing over this section to obtain all the info they need to make the big but defendable decisions.', '6', '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `menu` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `resultId` varchar(255) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `code`, `menu`, `name`, `resultId`, `order`, `created`, `modified`) VALUES
(1, 'np', 'Nasopharyngeal Swab', 'Nasopharyngeal Swab', 'n', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 'blood', 'Blood (Serum)', 'Blood (Serum)', 's', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(3, 'csf', 'Cerebrospinal Fluid (CSF)', 'Cerebrospinal Fluid (CSF)', 'sf', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `standards`
--

CREATE TABLE IF NOT EXISTS `standards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `standards`
--

INSERT INTO `standards` (`id`, `code`, `name`, `order`, `created`, `modified`) VALUES
(1, 'ash1', 'Antiserum H1', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 'ash3', 'Antiserum H3', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(3, 'ash5', 'Antiserum H5', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(4, 'asn1', 'Antiserum N1', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(5, 'asn2', 'Antiserum N2', 5, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(6, 'h2o', 'Water', 6, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(7, 'mna', 'Mixed Nucleic Acids (H1, H3, H5, H7, N1, N2)', 7, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(8, 'vh3n2', 'Virus H3N2 Serotype', 8, '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `standard_assays`
--

CREATE TABLE IF NOT EXISTS `standard_assays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) NOT NULL,
  `technique_id` int(11) NOT NULL,
  `standard_id` int(11) NOT NULL,
  `before_submit` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_assays_attempts1_idx` (`attempt_id`),
  KEY `fk_assays_techniques1_idx` (`technique_id`),
  KEY `fk_assays_copy1_standards1_idx` (`standard_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2516 ;

-- --------------------------------------------------------

--
-- Table structure for table `techniques`
--

CREATE TABLE IF NOT EXISTS `techniques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) NOT NULL,
  `menu` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `video` varchar(2000) DEFAULT NULL,
  `content` text,
  `revision` tinyint(1) NOT NULL DEFAULT '0',
  `lab` tinyint(1) NOT NULL DEFAULT '0',
  `results` tinyint(1) NOT NULL DEFAULT '0',
  `order` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `money` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `techniques`
--

INSERT INTO `techniques` (`id`, `code`, `menu`, `name`, `video`, `content`, `revision`, `lab`, `results`, `order`, `time`, `money`, `created`, `modified`) VALUES
(1, 'pfu', 'Plaque Assay', 'The Plaque Assay', 'https://www.youtube.com/embed/er2dwOPwSRo?rel=0', '<ul><li>Quantifies the amount of virus in a sample if:<ul><li>Virus can replicate in cells chosen</li><li>Virus causes observable changes in the cells upon infection (i.e. cytopathic effect or CPE)</li></ul></li><li>Resulting plaques do vary in size and shape for each virus type but it is not diagnostic</li><li>Requires samples containing replication competent (i.e. not inactivated) virus<ul><li>Dealing with replication competent viruses requires elevated safety considerations (<a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_1__Safe_Handling_of_Bl" target="_blank">see Brimouth Protocols 1 & 2</a>)</li></ul></li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_3__In_vitro_culture_of" target="_blank">Brimouth Protocol 3</a></li></ul>', 1, 1, 1, 1, 120, 10, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 'ha', 'HA', 'The Haemagglutination (HA) Assay', 'https://www.youtube.com/embed/CFCi5Q4rhOU?rel=0', '<ul><li>Quantifies amount of virus in a sample</li><li>Specific to any virus that can bind red blood cell surface proteins</li><li>Requires samples containing whole (live or inactivated) virus</li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_4__Titration_of_influe" target="_blank">Brimouth Protocol 4</a></li></ul>', 1, 1, 1, 2, 4, 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(3, 'hai', 'HAI', 'The Haemagglutination Inhibition (HAI) Assay', 'https://www.youtube.com/embed/nN8MBU8S4EI?rel=0', '<ul><li>Quantifies amount of antibody in a sample</li><li>Antibody serotype assayed by altering the serotype of the virus to initiate haemagglutination</li><li>Requires samples containing antibody (e.g. serum from blood)</li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_5__Titration_of_anti_H" target="_blank">Brimouth Protocol 5</a></li></ul>', 1, 1, 1, 3, 6, 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(4, 'pcrh', 'qRT-PCR (H)', 'qRT-PCR (H1/H3/H5/H7)', 'https://www.youtube.com/embed/GkIVbkPba9k?rel=0', '<ul><li>Quantifies the amount of nucleic acid (DNA for qPCR and RNA for qRT-PCR) in a sample</li><li>Choice between qPCR and qRT-PCR requires information about the genome type (i.e. Baltimore class of the virus)</li><li>	Specificity dependent on the primers chosen and the sequence divergence between viruses</li><li>Requires samples containing viral genomes (i.e. free virions or virus infected cells)</li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_9__Real_Time_RT_PCR_fo" target="_blank">Brimouth Protocol 9</a></li></ul>', 0, 1, 1, 4, 6, 8, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(5, 'pcrn', 'qRT-PCR (N)', 'qRT-PCR (N1/N2)', 'https://www.youtube.com/embed/GkIVbkPba9k?rel=0', '<ul><li>Quantifies the amount of nucleic acid (DNA for qPCR and RNA for qRT-PCR) in a sample</li><li>Choice between qPCR and qRT-PCR requires information about the genome type (i.e. Baltimore class of the virus)</li><li>	Specificity dependent on the primers chosen and the sequence divergence between viruses</li><li>Requires samples containing viral genomes (i.e. free virions or virus infected cells)</li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_9__Real_Time_RT_PCR_fo" target="_blank">Brimouth Protocol 9</a></li></ul>', 0, 1, 1, 5, 6, 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(6, 'pcr', 'PCR', 'The Polymerase Chain Reaction (PCR)', 'https://www.youtube.com/embed/GkIVbkPba9k?rel=0', '<ul><li>Quantifies the amount of nucleic acid (DNA for qPCR and RNA for qRT-PCR) in a sample</li><li>Choice between qPCR and qRT-PCR requires information about the genome type (i.e. Baltimore class of the virus)</li><li>	Specificity dependent on the primers chosen and the sequence divergence between viruses</li><li>Requires samples containing viral genomes (i.e. free virions or virus infected cells)</li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_9__Real_Time_RT_PCR_fo" target="_blank">Brimouth Protocol 9</a></li></ul>', 1, 0, 0, 6, NULL, NULL, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(7, 'elisa', 'ELISA (N)', 'The Enzyme-linked Immunosorbent Assay (ELISA)', 'https://www.youtube.com/embed/1iNwFNinhQo?rel=0', '<ul><li>Quantifies amount of virus or antibody in a sample (depending on setup)</li><li>Specificity determined by choice of antibody or antigen used as the capture molecule</li><li>Requires samples containing either antibody or protein (depending on setup)</li><li><a href="../../files/Brimouth_Protocols.pdf#pagemode=bookmarks&nameddest=Protocol_7__ELISA_assay_for_ant" target="_blank">Brimouth Protocol 7</a></li></ul>', 1, 1, 1, 7, 24, 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(8, 'quickvue', 'QuickVue', 'QuickVue', NULL, NULL, 0, 0, 1, 8, NULL, NULL, '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `technique_results`
--

CREATE TABLE IF NOT EXISTS `technique_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technique_id` int(11) NOT NULL,
  `code` varchar(45) NOT NULL,
  `label` varchar(45) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_results_techniques1_idx` (`technique_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `technique_results`
--

INSERT INTO `technique_results` (`id`, `technique_id`, `code`, `label`, `order`, `created`, `modified`) VALUES
(1, 1, 'p', 'PFU', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(2, 2, 'ha', 'HA', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(3, 3, 'hai1', 'H1', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(4, 3, 'hai3', 'H3', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(5, 3, 'hai5', 'H5', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(6, 4, 'pcrh1', 'H1', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(7, 4, 'pcrh3', 'H3', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(8, 4, 'pcrh5', 'H5', 3, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(9, 4, 'pcrh7', 'H7', 4, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(10, 5, 'pcrn1', 'N1', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(11, 5, 'pcrn2', 'N2', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(12, 7, 'en1', 'N1', 1, '2015-12-03 12:18:43', '2015-12-03 12:18:43'),
(13, 7, 'en2', 'N2', 2, '2015-12-03 12:18:43', '2015-12-03 12:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `technique_usefulness`
--

CREATE TABLE IF NOT EXISTS `technique_usefulness` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) NOT NULL,
  `technique_id` int(11) NOT NULL,
  `useful` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_techniques_useful_attempts1_idx` (`attempt_id`),
  KEY `fk_techniques_useful_techniques1_idx` (`technique_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1284 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assays`
--
ALTER TABLE `assays`
  ADD CONSTRAINT `fk_assays_attempts1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_assays_children1` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_assays_sample_stages1` FOREIGN KEY (`sample_stage_id`) REFERENCES `sample_stages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_assays_schools1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_assays_sites1` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_assays_techniques1` FOREIGN KEY (`technique_id`) REFERENCES `techniques` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `attempts`
--
ALTER TABLE `attempts`
  ADD CONSTRAINT `fk_attempts_lti_resources1` FOREIGN KEY (`lti_resource_id`) REFERENCES `lti_resources` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_attempts_lti_users1` FOREIGN KEY (`lti_user_id`) REFERENCES `lti_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `attempts_schools`
--
ALTER TABLE `attempts_schools`
  ADD CONSTRAINT `fk_attempts_has_schools_attempts1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_attempts_has_schools_schools1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `children`
--
ALTER TABLE `children`
  ADD CONSTRAINT `fk_children_schools1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `lti_contexts`
--
ALTER TABLE `lti_contexts`
  ADD CONSTRAINT `fk_lit_contexts_lit_keys` FOREIGN KEY (`lti_key_id`) REFERENCES `lti_keys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `lti_resources`
--
ALTER TABLE `lti_resources`
  ADD CONSTRAINT `fk_lit_contexts_lit_keys0` FOREIGN KEY (`lti_key_id`) REFERENCES `lti_keys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_lti_resources_lti_contexts1` FOREIGN KEY (`lti_context_id`) REFERENCES `lti_contexts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `lti_users`
--
ALTER TABLE `lti_users`
  ADD CONSTRAINT `fk_lit_contexts_lit_keys00` FOREIGN KEY (`lti_key_id`) REFERENCES `lti_keys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `fk_marks_lti_resources1` FOREIGN KEY (`lti_resource_id`) REFERENCES `lti_resources` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_marks_lti_users1` FOREIGN KEY (`lti_user_id`) REFERENCES `lti_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_marks_lti_users2` FOREIGN KEY (`marker_id`) REFERENCES `lti_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_marks_lti_users3` FOREIGN KEY (`locker_id`) REFERENCES `lti_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_notes_attempts1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_notes_techniques1` FOREIGN KEY (`technique_id`) REFERENCES `techniques` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `question_answers`
--
ALTER TABLE `question_answers`
  ADD CONSTRAINT `fk_answers_attempts1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_answers_question_options1` FOREIGN KEY (`question_option_id`) REFERENCES `question_options` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_answers_stems1` FOREIGN KEY (`stem_id`) REFERENCES `question_stems` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `fk_question_options_questions1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `question_scores`
--
ALTER TABLE `question_scores`
  ADD CONSTRAINT `fk_scores_attempts1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_scores_questions1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `question_stems`
--
ALTER TABLE `question_stems`
  ADD CONSTRAINT `fk_stems_questions1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_stems_question_options1` FOREIGN KEY (`question_option_id`) REFERENCES `question_options` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_attempts1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `reports_sections`
--
ALTER TABLE `reports_sections`
  ADD CONSTRAINT `fk_reports_sections_reports1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reports_sections_sections1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `samples`
--
ALTER TABLE `samples`
  ADD CONSTRAINT `fk_samples_attempts1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_samples_children1` FOREIGN KEY (`child_id`) REFERENCES `children` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_samples_sample_stages1` FOREIGN KEY (`sample_stage_id`) REFERENCES `sample_stages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_samples_schools1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_samples_sites1` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `standard_assays`
--
ALTER TABLE `standard_assays`
  ADD CONSTRAINT `fk_assays_attempts10` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_assays_copy1_standards1` FOREIGN KEY (`standard_id`) REFERENCES `standards` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_assays_techniques10` FOREIGN KEY (`technique_id`) REFERENCES `techniques` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `technique_results`
--
ALTER TABLE `technique_results`
  ADD CONSTRAINT `fk_results_techniques1` FOREIGN KEY (`technique_id`) REFERENCES `techniques` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `technique_usefulness`
--
ALTER TABLE `technique_usefulness`
  ADD CONSTRAINT `fk_techniques_useful_attempts1` FOREIGN KEY (`attempt_id`) REFERENCES `attempts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_techniques_useful_techniques1` FOREIGN KEY (`technique_id`) REFERENCES `techniques` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
