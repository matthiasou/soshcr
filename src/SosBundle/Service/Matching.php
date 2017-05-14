<?php 

namespace SosBundle\Service;

class Matching {

    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getEmploye($data){


        $secteur_join = "";
        $service_join = "";
        $formation = "";
        $poste_join = "";
        $cursus_scolaire_join = "";
        $formation_minimum_join = "";
        $experience_minimum_join = "";
        $niveau_anglais_join = "";
        $classification_join = "";
        $classification = "";
        $secteur_activite = "";
        $service_activite = "";
        $poste = "";
        $contrat = "";
        $contrat_duree = "";
        $formation_minimum = "";
        $experience_minimum = "";
        $cursus_scolaire = "";
        $niveau_anglais = "";
        $contrat_duree_join = "";
        $formation_join = "";

        if (isset($data['ville'])) {
            // Recherche de l'employé
            $formule="(6366*acos(cos(radians(".$data['ville']['latitude']."))*cos(radians('uc.latitude'))*cos(radians('uc.longitude') -radians(".$data['ville']['longitude']."))+sin(radians(".$data['ville']['latitude']."))*sin(radians('uc.latitude'))))";
            dump($formule);
            $ville = "WHERE ".$formule." < uc.rayon_emploi";
        }

        if (isset($data['classification'])) {
            $classification_join = "JOIN user_critere_etablissement uce ON uc.id = uce.user_critere_id";
            $classification =  "AND uce.etablissement_id = ".$data['classification'];
        }

        if (isset($data['poste'])) {
            $poste_join = "JOIN poste_recherche p ON uc.poste_id = p.id";
            $poste =  "AND p.id = ".$data['poste'];
        }

        if (isset($data['contrat'])) {
            $contrat =  "AND uc.contrat_id = ".$data['contrat'];
        }

        if (isset($data['contrat_duree'])) {
            $contrat_duree_join = "JOIN user_critere_type_contrat uctc ON uc.id = uctc.user_critere_id";
            if ($data['contrat_duree'] != 3) { // si tout n'est pas sélectionné
                $contrat_duree =  "AND uc.type_contrat_id = ".$data['contrat_duree'];
            }

        }

        if (isset($data['formation_minimum'])) {
            $formation_join = "JOIN user_critere_formation ucf ON uc.id = ucf.user_critere_id";
            if ($data['formation_minimum'] != 0) { // si tout n'est pas sélectionné
                $formation =  "AND ucf.formation_id >= ".$data['formation'];
            }
        }

        if (isset($data['cursus_scolaire'])) {
            $cursus_scolaire_join = "JOIN user_critere_cursus_scolaire uccs ON uc.id = uccs.user_critere_id";
            if ($data['cursus_scolaire'] != 5) { // si tout n'est pas sélectionné
                $cursus_scolaire =  "AND uc.cursus_id = ".$data['cursus_scolaire'];
            }
        }

        $query = "SELECT DISTINCT u.id
            FROM utilisateur u
            JOIN user_critere uc
            ON u.id = uc.user_id ".
            $classification_join." ".
            $poste_join." ".
            $contrat_duree_join." ".
            $formation_join." ".
            $cursus_scolaire_join."
            WHERE ".$formule." <> uc.rayon_emploi ".
            $classification." ".
            $poste." ".
            $contrat." ".
            $contrat_duree." ".
            $formation." ".
            $cursus_scolaire;

        

        $stmt = $this->entityManager->getConnection()->prepare($query);
        $stmt->execute();
        
        $employes = $stmt->fetchAll();


        $listeEmployes = array();

        foreach ($employes as $value) {
            $listeEmployes[] = $this->entityManager->getRepository("SosBundle:User")->find($value);
        }
        return $listeEmployes;

    }

    public function getNumberOfEmploye($data, $form){


        $secteur_join = "";
        $service_join = "";
        $formation = "";
        $poste_join = "";
        $cursus_scolaire_join = "";
        $formation_minimum_join = "";
        $experience_minimum_join = "";
        $niveau_anglais_join = "";
        $classification_join = "";
        $classification = "";
        $secteur_activite = "";
        $service_activite = "";
        $poste = "";
        $contrat = "";
        $contrat_duree = "";
        $formation_minimum = "";
        $experience_minimum = "";
        $cursus_scolaire = "";
        $niveau_anglais = "";
        $contrat_duree_join = "";
        $formation_join = "";

        if (isset($data['ville'])) {
            // Recherche de l'employé
            $formule="(6366*acos(cos(radians(".$data['ville']['latitude']."))*cos(radians('uc.latitude'))*cos(radians('uc.longitude') -radians(".$data['ville']['longitude']."))+sin(radians(".$data['ville']['latitude']."))*sin(radians('uc.latitude'))))";
            dump($formule);
            $ville = "WHERE ".$formule." < uc.rayon_emploi";
        }

        if (isset($data['classification'])) {
            $classification_join = "JOIN user_critere_etablissement uce ON uc.id = uce.user_critere_id";
            $classification =  "AND uce.etablissement_id = ".$data['classification'];
        }

        if (isset($data['poste'])) {
            $poste_join = "JOIN poste_recherche p ON uc.poste_id = p.id";
            $poste =  "AND p.id = ".$data['poste'];
        }

        if (isset($data['contrat'])) {
            $contrat =  "AND uc.contrat_id = ".$data['contrat'];
        }

        if (isset($data['contrat_duree'])) {
            $contrat_duree_join = "JOIN user_critere_type_contrat uctc ON uc.id = uctc.user_critere_id";
            if ($data['contrat_duree'] != 3) { // si tout n'est pas sélectionné
                $contrat_duree =  "AND uc.type_contrat_id = ".$data['contrat_duree'];
            }

        }

        if (isset($data['formation_minimum'])) {
            $formation_join = "JOIN user_critere_formation ucf ON uc.id = ucf.user_critere_id";
            if ($data['formation_minimum'] != 0) { // si tout n'est pas sélectionné
                $formation =  "AND ucf.formation_id >= ".$data['formation'];
            }
        }

        if (isset($data['cursus_scolaire'])) {
            $cursus_scolaire_join = "JOIN user_critere_cursus_scolaire uccs ON uc.id = uccs.user_critere_id";
            if ($data['cursus_scolaire'] != 5) { // si tout n'est pas sélectionné
                $cursus_scolaire =  "AND uc.cursus_id = ".$data['cursus_scolaire'];
            }
        }

        $query2 = "SELECT COUNT(DISTINCT u.id) as nb
            FROM utilisateur u
            JOIN user_critere uc
            ON u.id = uc.user_id ".
            $classification_join." ".
            $poste_join." ".
            $contrat_duree_join." ".
            $formation_join." ".
            $cursus_scolaire_join."
            WHERE ".$formule." <> uc.rayon_emploi ".
            $classification." ".
            $poste." ".
            $contrat." ".
            $contrat_duree." ".
            $formation." ".
            $cursus_scolaire;


        $stmt = $this->entityManager->getConnection()->prepare($query2);
        $stmt->execute();
        
        $match_employe = $stmt->fetchAll();

        return $data['match_employe'] = $match_employe[0];

    }


    public function setScoreEmploye($data){


        $secteur_join = "";
        $service_join = "";
        $formation = "";
        $poste_join = "";
        $cursus_scolaire_join = "";
        $formation_minimum_join = "";
        $experience_minimum_join = "";
        $niveau_anglais_join = "";
        $classification_join = "";
        $classification = "";
        $secteur_activite = "";
        $service_activite = "";
        $poste = "";
        $contrat = "";
        $contrat_duree = "";
        $formation_minimum = "";
        $experience_minimum = "";
        $cursus_scolaire = "";
        $niveau_anglais = "";
        $contrat_duree_join = "";
        $formation_join = "";

        if (isset($data['ville'])) {
            // Recherche de l'employé
            $formule="(6366*acos(cos(radians(".$data['ville']['latitude']."))*cos(radians('uc.latitude'))*cos(radians('uc.longitude') -radians(".$data['ville']['longitude']."))+sin(radians(".$data['ville']['latitude']."))*sin(radians('uc.latitude'))))";
            dump($formule);
            $ville = "WHERE ".$formule." < uc.rayon_emploi";
        }

        if (isset($data['classification'])) {
            $classification_join = "JOIN user_critere_etablissement uce ON uc.id = uce.user_critere_id";
            $classification =  "AND uce.etablissement_id = ".$data['classification'];
        }

        if (isset($data['poste'])) {
            $poste_join = "JOIN poste_recherche p ON uc.poste_id = p.id";
            $poste =  "AND p.id = ".$data['poste'];
        }

        if (isset($data['contrat'])) {
            $contrat =  "AND uc.contrat_id = ".$data['contrat'];
        }

        if (isset($data['contrat_duree'])) {
            $contrat_duree_join = "JOIN user_critere_type_contrat uctc ON uc.id = uctc.user_critere_id";
            if ($data['contrat_duree'] != 3) { // si tout n'est pas sélectionné
                $contrat_duree =  "AND uc.type_contrat_id = ".$data['contrat_duree'];
            }

        }

        if (isset($data['formation_minimum'])) {
            $formation_join = "JOIN user_critere_formation ucf ON uc.id = ucf.user_critere_id";
            if ($data['formation_minimum'] != 0) { // si tout n'est pas sélectionné
                $formation =  "AND ucf.formation_id >= ".$data['formation'];
            }
        }

        if (isset($data['cursus_scolaire'])) {
            $cursus_scolaire_join = "JOIN user_critere_cursus_scolaire uccs ON uc.id = uccs.user_critere_id";
            if ($data['cursus_scolaire'] != 5) { // si tout n'est pas sélectionné
                $cursus_scolaire =  "AND uc.cursus_id = ".$data['cursus_scolaire'];
            }
        }

        $query = "SELECT DISTINCT u.id
            FROM utilisateur u
            JOIN user_critere uc
            ON u.id = uc.user_id ".
            $classification_join." ".
            $poste_join." ".
            $contrat_duree_join." ".
            $formation_join." ".
            $cursus_scolaire_join."
            WHERE ".$formule." <> uc.rayon_emploi ".
            $classification." ".
            $poste." ".
            $contrat." ".
            $contrat_duree." ".
            $formation." ".
            $cursus_scolaire;



        $stmt = $this->entityManager->getConnection()->prepare($query);
        $stmt->execute();

        $employes = $stmt->fetchAll();

        $score = 0 ;

        foreach ($employes as $value) {
            $pointsRecommandation = (count($this->entityManager->getRepository("SosBundle:Recommandation")->findby(array("user"=>$value,'valide'=>1))))*10;
            $user = $this->entityManager->getRepository("SosBundle:User")->find($value);
            $pointsAnglais = $this->entityManager->getRepository("SosBundle:Anglais")->findOneBy(array("id"=>$user->getNiveauAnglais()))->getPoints();
            $criters = $this->entityManager->getRepository("SosBundle:UserCritere")->findOneBy(array("poste"=>$data['poste'],"user"=>$value,"etablissement"=>$data['classification']));
            $pointsExperience = $this->entityManager->getRepository("SosBundle:Experience")->find($criters->getExperience())->getPoints();
            $pointsPoste = $this->entityManager->getRepository("SosBundle:PosteRecherche")->find($criters->getPoste())->getCoefficient();

            $score = $pointsRecommandation+$pointsAnglais+($pointsExperience*$pointsPoste);
            $user->setScore($score);
            $this->entityManager->flush();
        }

    }




}