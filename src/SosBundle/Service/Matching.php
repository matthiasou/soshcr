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
        $poste_recherche_join = "";
        $cursus_scolaire_join = "";
        $formation_minimum_join = "";
        $experience_minimum_join = "";
        $niveau_anglais_join = "";

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

        if (isset($data['ville'])) {
            // Recherche de l'employé
            $formule="(6366*ACOS(COS(RADIANS(".floatval($data['ville']['latitude'])."))*COS(RADIANS(a.latitude))*COS(RADIANS(a.longitude)-RADIANS(".floatval($data['ville']['longitude'])."))+SIN(RADIANS(".floatval($data['ville']['latitude'])."))*SIN(RADIANS(a.latitude))))";
            $ville = "WHERE ".$formule." < u.rayon_emploi";
        }

        if (isset($data['classification'])) {
            $classification =  "AND uc.etablissement_id = ".$data['classification'];
        }

        if (isset($data['secteur_activite'])) {
            $secteur_join =  "JOIN secteur s ON uc.secteur_id = s.id";
            $secteur_activite =  "AND uc.secteur_id = ".$data['secteur_activite'];
        }

        if (isset($data['service_activite'])) {
            $service_join =  "JOIN service se ON uc.service_id = se.id";
            $service_activite =  "AND uc.service_id = ".$data['service_activite'];
        }

        if (isset($data['poste'])) {
            $poste_recherche_join = "JOIN poste_recherche p ON uc.poste_id = p.id";
            $poste =  "AND p.id = ".$data['poste'];
        }

        if (isset($data['contrat'])) {
            $contrat =  "AND uc.contrat_id = ".$data['contrat'];
        }

        if (isset($data['contrat_duree'])) {

            if ($data['contrat_duree'] != 12) { // si tout n'est pas sélectionné
                $contrat_duree =  "AND uc.type_contrat_id = ".$data['contrat_duree'];
            }

        }

        if (isset($data['formation_minimum'])) {
            $formation_minimum_join = "JOIN formation f ON uc.formation_id = f.id";
            $formation_minimum =  "AND f.classement >= ".$data['formation_minimum'];
        }

        if (isset($data['experience_minimum'])) {
            $experience_minimum_join = "JOIN experience e ON uc.experience_id = e.id";
            $experience_minimum =  "AND e.classement >= ".$data['experience_minimum'];
        }

        if (isset($data['cursus_scolaire'])) {
            $cursus_scolaire_join = "JOIN cursus_scolaire cs ON uc.cursus_id = cs.id";
            if ($data['cursus_scolaire'] != 5) { // si tout n'est pas sélectionné
                $cursus_scolaire =  "AND uc.cursus_id = ".$data['cursus_scolaire'];
            }
            
        }

        if (isset($data['niveau_anglais'])) {
            $niveau_anglais_join = "JOIN anglais an ON u.niveau_anglais_id = an.id";
            $niveau_anglais =  "AND an.classement >= ".$data['niveau_anglais'];
        }


        $query = "SELECT u.id
            FROM utilisateur u
            JOIN adresse a
            ON u.adresse_id = a.id
            JOIN user_critere uc
            ON u.id = uc.user_id ".
            $secteur_join." ".
            $service_join." ".
            $poste_recherche_join." ".
            $cursus_scolaire_join." ".
            $formation_minimum_join." ".
            $experience_minimum_join." ".
            $niveau_anglais_join."
            WHERE ".$formule." < u.rayon_emploi ".
            $classification." ".
            $secteur_activite." ".
            $service_activite." ".
            $poste." ".
            $contrat." ".
            $contrat_duree." ".
            $formation_minimum." ".
            $experience_minimum." ".
            $cursus_scolaire." ".
            $niveau_anglais;

        

        $stmt = $this->entityManager->getConnection()->prepare($query);
        $stmt->execute();
        
        $employes = $stmt->fetchAll();
        $listeEmployes = array();

        foreach ($employes as $key => $value) {
            $listeEmployes[] = $this->entityManager->getRepository("SosBundle:User")->find($value);
        }

        return $listeEmployes;

    }

    public function getNumberOfEmploye($data, $form){


        $secteur_join = "";
        $service_join = "";
        $poste_recherche_join = "";
        $cursus_scolaire_join = "";
        $formation_minimum_join = "";
        $experience_minimum_join = "";
        $niveau_anglais_join = "";

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

        if (isset($data['ville'])) {
            // Recherche de l'employé
            $formule="(6366*ACOS(COS(RADIANS(".floatval($data['ville']['latitude'])."))*COS(RADIANS(a.latitude))*COS(RADIANS(a.longitude)-RADIANS(".floatval($data['ville']['longitude'])."))+SIN(RADIANS(".floatval($data['ville']['latitude'])."))*SIN(RADIANS(a.latitude))))";
            $ville = "WHERE ".$formule." < u.rayon_emploi";
        }

        if (isset($data['classification'])) {
            $classification =  "AND uc.etablissement_id = ".$data['classification'];
        }

        if (isset($data['secteur_activite'])) {
            $secteur_join =  "JOIN secteur s ON uc.secteur_id = s.id";
            $secteur_activite =  "AND uc.secteur_id = ".$data['secteur_activite'];
        }

        if (isset($data['service_activite'])) {
            $service_join =  "JOIN service se ON uc.service_id = se.id";
            $service_activite =  "AND uc.service_id = ".$data['service_activite'];
        }

        if (isset($data['poste'])) {
            $poste_recherche_join = "JOIN poste_recherche p ON uc.poste_id = p.id";
            $poste =  "AND p.id = ".$data['poste'];
        }

        if (isset($data['contrat'])) {
            $contrat =  "AND uc.contrat_id = ".$data['contrat'];
        }

        if (isset($data['contrat_duree'])) {

            if ($data['contrat_duree'] != 12) { // si tout n'est pas sélectionné
                $contrat_duree =  "AND uc.type_contrat_id = ".$data['contrat_duree'];
            }

        }

        if (isset($data['formation_minimum'])) {
            $formation_minimum_join = "JOIN formation f ON uc.formation_id = f.id";
            $formation_minimum =  "AND f.classement >= ".$data['formation_minimum'];
        }

        if (isset($data['experience_minimum'])) {
            $experience_minimum_join = "JOIN experience e ON uc.experience_id = e.id";
            $experience_minimum =  "AND e.classement >= ".$data['experience_minimum'];
        }

        if (isset($data['cursus_scolaire'])) {
            $cursus_scolaire_join = "JOIN cursus_scolaire cs ON uc.cursus_id = cs.id";
            if ($data['cursus_scolaire'] != 5) { // si tout n'est pas sélectionné
                $cursus_scolaire =  "AND uc.cursus_id = ".$data['cursus_scolaire'];
            }
            
        }

        if (isset($data['niveau_anglais'])) {
            $niveau_anglais_join = "JOIN anglais an ON u.niveau_anglais_id = an.id";
            $niveau_anglais =  "AND an.classement >= ".$data['niveau_anglais'];
        }

        $query = "SELECT COUNT(*) as nb
            FROM utilisateur u
            JOIN adresse a
            ON u.adresse_id = a.id
            JOIN user_critere uc
            ON u.id = uc.user_id ".
            $secteur_join." ".
            $service_join." ".
            $poste_recherche_join." ".
            $cursus_scolaire_join." ".
            $formation_minimum_join." ".
            $experience_minimum_join." ".
            $niveau_anglais_join."
            WHERE ".$formule." < u.rayon_emploi ".
            $classification." ".
            $secteur_activite." ".
            $service_activite." ".
            $poste." ".
            $contrat." ".
            $contrat_duree." ".
            $formation_minimum." ".
            $experience_minimum." ".
            $cursus_scolaire." ".
            $niveau_anglais;


        $stmt = $this->entityManager->getConnection()->prepare($query);
        $stmt->execute();
        
        $match_employe = $stmt->fetchAll();
        return $data['match_employe'] = $match_employe[0];

    }

}