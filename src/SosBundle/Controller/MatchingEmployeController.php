<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use SosBundle\Entity\Etablissement;
use SosBundle\Entity\Secteur;

class MatchingEmployeController extends Controller
{

    public function getNumberOfEmploye($data, $form){

        // Recherche de l'employé
        $formule="(6366*ACOS(COS(RADIANS(".floatval($data['ville']['latitude'])."))*COS(RADIANS(v.latitude))*COS(RADIANS(v.longitude)-RADIANS(".floatval($data['ville']['longitude'])."))+SIN(RADIANS(".floatval($data['ville']['latitude'])."))*SIN(RADIANS(v.latitude))))";

        if ($form == "ville" || $form == "classification") {
            $query = "SELECT COUNT(*) as nb
                FROM utilisateur u
                JOIN adresse a
                ON u.adresse_id = a.id
                JOIN ville v
                ON a.ville_id = v.id
                WHERE ".$formule." < u.rayon_emploi";
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
                    AND p.secteur_id = ".$data['secteur_activite']."
                    AND p.id = ".$data['poste']."           
                    AND uc.contrat_id = ".$data['contrat']."
                    AND uc.type_contrat_id = ".$data['contrat_duree']."             
                    AND u.cursus_scolaire_id = ".$data['cursus_scolaire']."             
                    AND u.niveau_anglais_id = ".$data['niveau_anglais'];    
            }

        }

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->execute();
        
        $match_employe = $stmt->fetchAll();
        return $data['match_employe'] = $match_employe[0];

    }
}