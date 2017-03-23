<?php 

namespace SosBundle\Service;

class Matching {

    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getEmploye($data){
    
        // $classification = $this->entityManager->getRepository("SosBundle:Etablissement")->find($data['classification']);
        // $secteur = $this->entityManager->getRepository("SosBundle:Secteur")->find($data['secteur_activite']);
        // $poste = $this->entityManager->getRepository("SosBundle:PosteRecherche")->find($data['poste']);
        // $contrat = $this->entityManager->getRepository("SosBundle:Contrat")->find($data['contrat']); 
        // $typeContrat = $this->entityManager->getRepository("SosBundle:TypeContrat")->find($data['contrat_duree']);

        // $userCritere = $this->entityManager->getRepository("SosBundle:UserCritere")->findBy(array('poste' => $poste, 'etablissement' => $classification, 'contrat' => $contrat, 'typeContrat' => $typeContrat));
        
        // if (isset($data['service_activite'])) {
        //     $service = $this->entityManager->getRepository("SosBundle:Service")->find($data['service_activite']);
        // }

        // // $Users = $this->entityManager->getRepository("SosBundle:User")->findBy(array('criteres' => $userCritere, 'cursusScolaire' => $data['cursus_scolaire'], 'niveauAnglais' => $data['niveau_anglais']));


        // dump($userCritere);

        $formule="(6366*ACOS(COS(RADIANS(".floatval($data['ville']['latitude'])."))*COS(RADIANS(v.latitude))*COS(RADIANS(v.longitude)-RADIANS(".floatval($data['ville']['longitude'])."))+SIN(RADIANS(".floatval($data['ville']['latitude'])."))*SIN(RADIANS(v.latitude))))";

        $query = "SELECT u.id
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.id = ".$data['poste']."           
                    AND uc.contrat_id = ".$data['contrat']."
                    AND uc.type_contrat_id = ".$data['contrat_duree']."             
                    AND u.cursus_scolaire_id = ".$data['cursus_scolaire']."             
                    AND u.niveau_anglais_id = ".$data['niveau_anglais'];    
        

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

        // Recherche de l'employé
        $formule="(6366*ACOS(COS(RADIANS(".floatval($data['ville']['latitude'])."))*COS(RADIANS(v.latitude))*COS(RADIANS(v.longitude)-RADIANS(".floatval($data['ville']['longitude'])."))+SIN(RADIANS(".floatval($data['ville']['latitude'])."))*SIN(RADIANS(v.latitude))))";

        if ($form == "ville" ) {
            $query = "SELECT COUNT(*) as nb
                FROM utilisateur u
                JOIN adresse a
                ON u.adresse_id = a.id
                JOIN ville v
                ON a.ville_id = v.id
                WHERE ".$formule." < u.rayon_emploi";
        }

        if ($form == "classification" ) {
            $query = "SELECT COUNT(*) as nb
                FROM utilisateur u
                JOIN adresse a
                ON u.adresse_id = a.id
                JOIN ville v
                ON a.ville_id = v.id
                JOIN user_critere uc
                ON u.id = uc.user_id
                JOIN poste_recherche p
                ON uc.poste_id = p.id
                WHERE ".$formule." < u.rayon_emploi
                AND uc.etablissement_id = ".$data['classification'];
        }

        if ($form == "secteur") {
            $query = "SELECT COUNT(*) as nb
                FROM utilisateur u
                JOIN adresse a
                ON u.adresse_id = a.id
                JOIN ville v
                ON a.ville_id = v.id
                JOIN user_critere uc
                ON u.id = uc.user_id
                JOIN poste_recherche p
                ON uc.poste_id = p.id
                WHERE ".$formule." < u.rayon_emploi
                AND uc.etablissement_id = ".$data['classification']."
                AND p.secteur_id = ".$data['secteur_activite'];

        }

        if ($form == "service") {
            $query = "SELECT COUNT(*) as nb
                FROM utilisateur u
                JOIN adresse a
                ON u.adresse_id = a.id
                JOIN ville v
                ON a.ville_id = v.id
                JOIN user_critere uc
                ON u.id = uc.user_id
                JOIN poste_recherche p
                ON uc.poste_id = p.id
                WHERE ".$formule." < u.rayon_emploi
                AND uc.etablissement_id = ".$data['classification']."
                AND p.secteur_id = ".$data['secteur_activite']."
                AND p.service_id = ".$data['service_activite'];

        }

        if ($form == "poste_restauration" || $form == "poste_hotellerie") {
            if (isset($data['service_activite'])) {
                dump($data);
                $query = "SELECT COUNT(*) as nb
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND uc.etablissement_id = ".$data['classification']."
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.service_id = ".$data['service_activite']."
                    AND p.id = ".$data['poste'];
            }else{
                $query = "SELECT COUNT(*) as nb
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND uc.etablissement_id = ".$data['classification']."
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.id = ".$data['poste'];
            }

        }

        if ($form == "contrat") {
            if (isset($data['service_activite'])) {
                $query = "SELECT COUNT(*) as nb
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND uc.etablissement_id = ".$data['classification']."
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.service_id = ".$data['service_activite']."
                    AND p.id = ".$data['poste']."           
                    AND uc.contrat_id = ".$data['contrat'];             
            }else{
                $query = "SELECT COUNT(*) as nb
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND uc.etablissement_id = ".$data['classification']."
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.id = ".$data['poste']."           
                    AND uc.contrat_id = ".$data['contrat'];  
            }

        }

        if ($form == "duree") {
            if (isset($data['service_activite'])) {
                $query = "SELECT COUNT(*) as nb
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND uc.etablissement_id = ".$data['classification']."
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.service_id = ".$data['service_activite']."
                    AND p.id = ".$data['poste']."           
                    AND uc.contrat_id = ".$data['contrat']."            
                    AND uc.type_contrat_id = ".$data['contrat_duree'];              
            }else{
                $query = "SELECT COUNT(*) as nb
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND uc.etablissement_id = ".$data['classification']."
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.id = ".$data['poste']."           
                    AND uc.contrat_id = ".$data['contrat']."
                    AND uc.type_contrat_id = ".$data['contrat_duree']; 
            }

        }

        if ($form == "cursus") {
            if (isset($data['service_activite'])) {
                $query = "SELECT COUNT(*) as nb
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND uc.etablissement_id = ".$data['classification']."
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.service_id = ".$data['service_activite']."
                    AND p.id = ".$data['poste']."           
                    AND uc.contrat_id = ".$data['contrat']."            
                    AND uc.type_contrat_id = ".$data['contrat_duree']."             
                    AND u.cursus_scolaire_id = ".$data['cursus_scolaire'];              
            }else{
                $query = "SELECT COUNT(*) as nb
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND uc.etablissement_id = ".$data['classification']."
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.id = ".$data['poste']."           
                    AND uc.contrat_id = ".$data['contrat']."
                    AND uc.type_contrat_id = ".$data['contrat_duree']."             
                    AND u.cursus_scolaire_id = ".$data['cursus_scolaire'];  
            }

        }

        if ($form == "anglais") {
            if (isset($data['service_activite'])) {
                $query = "SELECT COUNT(*) as nb
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND uc.etablissement_id = ".$data['classification']."
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.service_id = ".$data['service_activite']."
                    AND p.id = ".$data['poste']."           
                    AND uc.contrat_id = ".$data['contrat']."            
                    AND uc.type_contrat_id = ".$data['contrat_duree']."             
                    AND u.cursus_scolaire_id = ".$data['cursus_scolaire']."             
                    AND u.niveau_anglais_id = ".$data['niveau_anglais'];            
            }else{
                $query = "SELECT COUNT(*) as nb
                    FROM utilisateur u
                    JOIN adresse a
                    ON u.adresse_id = a.id
                    JOIN ville v
                    ON a.ville_id = v.id
                    JOIN user_critere uc
                    ON u.id = uc.user_id
                    JOIN poste_recherche p
                    ON uc.poste_id = p.id
                    WHERE ".$formule." < u.rayon_emploi
                    AND uc.etablissement_id = ".$data['classification']."
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.id = ".$data['poste']."           
                    AND uc.contrat_id = ".$data['contrat']."
                    AND uc.type_contrat_id = ".$data['contrat_duree']."             
                    AND u.cursus_scolaire_id = ".$data['cursus_scolaire']."             
                    AND u.niveau_anglais_id = ".$data['niveau_anglais'];    
            }

        }

        $stmt = $this->entityManager->getConnection()->prepare($query);
        $stmt->execute();
        
        $match_employe = $stmt->fetchAll();
        return $data['match_employe'] = $match_employe[0];

    }

}