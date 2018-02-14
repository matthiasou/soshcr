<?php 

namespace SosBundle\Service;
use Symfony\Component\Validator\Constraints\DateTime;

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
            $formule="(6366*acos(cos(radians(".$data['ville']['latitude']."))*cos(radians(uc.latitude))*cos(radians(uc.longitude)-radians(".$data['ville']['longitude']."))+sin(radians(".$data['ville']['latitude']."))*sin(radians(uc.latitude))))";
            
        }

        if (isset($data['classification'])) {
            $classification_join = "JOIN user_critere_etablissement uce ON uc.id = uce.user_critere_id";
            $classification =  "AND uce.etablissement_id = ".$data['classification'];
        }

        if (isset($data['poste'])) {
            if ($data['poste'] > 1)
                $poste =  "AND uc.poste_id IN (".implode(',',$data['poste']).")";
            else
                $poste =  "AND uc.poste_id = ".$data['poste'][0];
        }

        if (isset($data['contrat'])) {
            $contrat =  "AND uc.contrat_id = ".$data['contrat'];
        }

        if (isset($data['contrat_duree'])) {
            $contrat_duree_join = "JOIN user_critere_type_contrat uctc ON uc.id = uctc.user_critere_id";
            if ($data['contrat_duree'] != 3) { // si tout n'est pas sélectionné
                $contrat_duree =  "AND uctc.type_contrat_id = ".$data['contrat_duree'];
            }

        }

        if (isset($data['formation_minimum'])) {
            $formation_join = "JOIN user_critere_formation ucf ON uc.id = ucf.user_critere_id";
            if ($data['formation_minimum'] != 0) { // si tout n'est pas sélectionné
                $formation =  "AND ucf.formation_id >= ".$data['formation_minimum'];
            }
        }

        if (isset($data['cursus_scolaire'])) {
            $cursus_scolaire_join = "JOIN user_critere_cursus_scolaire uccs ON uc.id = uccs.user_critere_id";
            if ($data['cursus_scolaire'] != 5) { // si tout n'est pas sélectionné
                $cursus_scolaire =  "AND uccs.cursus_scolaire_id = ".$data['cursus_scolaire'];
            }
        }

        if (isset($data['experience_minimum'])) {
            $experience_minimum =  "AND uc.experience_id >= ".$data['experience_minimum'];
        }

        if (isset($data['niveau_anglais'])) {
            $niveau_anglais =  "AND uc.niveau_anglais_id >= ".$data['niveau_anglais'];
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
            WHERE ".$formule." < uc.rayon_emploi ".
            $classification." ".
            $poste." ".
            $contrat." ".
            $contrat_duree." ".
            $formation." ".
            $niveau_anglais." ".
            $experience_minimum." ".
            $cursus_scolaire." ORDER BY uc.score DESC";


        $stmt = $this->entityManager->getConnection()->prepare($query);
        $stmt->execute();
        
        $employes = $stmt->fetchAll();

        $listeEmployes = array();

        foreach ($employes as $value) {
            $listeEmployes[] = $this->entityManager->getRepository("SosBundle:User")->find($value);
        }

        $employesDateMatch = array();
        foreach ($listeEmployes as $key => $value) {
            if ($data['date_debut'] == null)
            {
                $employesDateMatch[] = $value->getId();
            }
            else
            {
                $criteres = $value->getCriteres();
                foreach ($criteres as $k => $v) {
                    $disponibilites = json_decode($v->getDisponibilites());
                    foreach ($disponibilites as $cle => $dispo) {

                        // Si range de dates
                        if (preg_match('/([0-9]{2}\/[0-9]{2}\/[0-9]{4}) - ([0-9]{2}\/[0-9]{2}\/[0-9]{4})/', $dispo))
                        {
                            $dateDebut = preg_replace('/([0-9]{2}\-[0-9]{2}\-[0-9]{4}) - [0-9]{2}\-[0-9]{2}\-[0-9]{4}/', '$1', str_replace('/', '-', $dispo));
                            $dateFin = preg_replace('/[0-9]{2}\-[0-9]{2}\-[0-9]{4} - ([0-9]{2}\-[0-9]{2}\-[0-9]{4})/', '$1', str_replace('/', '-', $dispo));
                            $dispo1 = new \DateTime($dateDebut);
                            $dispo2 = new \DateTime($dateFin);
                            $dispo2->modify('+1 day'); // permet d'inclure la derniere date de disponibilité
                            $period = new \DatePeriod(
                                 $dispo1,
                                 new \DateInterval('P1D'),
                                 $dispo2
                            );

                            $range = array();
                            foreach ($period as $p) {
                                $range[] = $p;
                            }

                            $dateCheck = new \DateTime(str_replace('/', '-', $data['date_debut']));
                            foreach ($range as $dateRange) {

                                if ($dateRange == $dateCheck)
                                {
                                    $employesDateMatch[] = $value->getId();
                                }
                            }

                        }
                        else
                        {
                            $dateCheck = new \DateTime(str_replace('/', '-', $data['date_debut']));
                            $dateNew = preg_replace('/([0-9]{2}\-[0-9]{2}\-[0-9]{4}\/).*/', '$1', str_replace('/', '-', $dispo));
                            $date = \DateTime::createFromFormat("d/m/Y",$dateNew);
                            
                            if ($date == $dateCheck)
                            {
                                $employesDateMatch[] = $value->getId();
                            }
                        }

                    }
                }                
            }

        }

        $employesDateMatch = array_unique($employesDateMatch);
        $finalList = array();
        foreach ($employesDateMatch as $key => $value) {
            $finalList[] = $this->entityManager->getRepository("SosBundle:User")->find($value);
        }
        
        return $finalList;

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
            $formule="(6366*acos(cos(radians(".$data['ville']['latitude']."))*cos(radians(uc.latitude))*cos(radians(uc.longitude)-radians(".$data['ville']['longitude']."))+sin(radians(".$data['ville']['latitude']."))*sin(radians(uc.latitude))))";
            
        }

        if (isset($data['classification'])) {
            $classification_join = "JOIN user_critere_etablissement uce ON uc.id = uce.user_critere_id";
            $classification =  "AND uce.etablissement_id = ".$data['classification'];
        }

        if (isset($data['poste'])) {
            if ($data['poste'] > 1)
                $poste =  "AND uc.poste_id IN (".implode(',',$data['poste']).")";
            else
                $poste =  "AND uc.poste_id = ".$data['poste'][0];
        }

        if (isset($data['contrat'])) {
            $contrat =  "AND uc.contrat_id = ".$data['contrat'];
        }

        if (isset($data['contrat_duree'])) {
            $contrat_duree_join = "JOIN user_critere_type_contrat uctc ON uc.id = uctc.user_critere_id";
            if ($data['contrat_duree'] != 3) { // si tout n'est pas sélectionné
                $contrat_duree =  "AND uctc.type_contrat_id = ".$data['contrat_duree'];
            }

        }

        if (isset($data['formation_minimum'])) {
            $formation_join = "JOIN user_critere_formation ucf ON uc.id = ucf.user_critere_id";
            if ($data['formation_minimum'] != 0) { // si tout n'est pas sélectionné
                $formation =  "AND ucf.formation_id >= ".$data['formation_minimum'];
            }
        }

        if (isset($data['cursus_scolaire'])) {
            $cursus_scolaire_join = "JOIN user_critere_cursus_scolaire uccs ON uc.id = uccs.user_critere_id";
            if ($data['cursus_scolaire'] != 5) { // si tout n'est pas sélectionné
                $cursus_scolaire =  "AND uccs.cursus_scolaire_id = ".$data['cursus_scolaire'];
            }
        }

        if (isset($data['experience_minimum'])) {
            $experience_minimum =  "AND uc.experience_id >= ".$data['experience_minimum'];
        }

        if (isset($data['niveau_anglais'])) {
            $niveau_anglais =  "AND uc.niveau_anglais_id >= ".$data['niveau_anglais'];
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
            WHERE ".$formule." < uc.rayon_emploi ".
            $classification." ".
            $poste." ".
            $contrat." ".
            $contrat_duree." ".
            $formation." ".
            $niveau_anglais." ".
            $experience_minimum." ".
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
            $formule="(6366*acos(cos(radians(".$data['ville']['latitude']."))*cos(radians(uc.latitude))*cos(radians(uc.longitude)-radians(".$data['ville']['longitude']."))+sin(radians(".$data['ville']['latitude']."))*sin(radians(uc.latitude))))";
            
        }

        if (isset($data['classification'])) {
            $classification_join = "JOIN user_critere_etablissement uce ON uc.id = uce.user_critere_id";
            $classification =  "AND uce.etablissement_id = ".$data['classification'];
        }

        if (isset($data['poste'])) {
            if ($data['poste'] > 1)
                $poste =  "AND uc.poste_id IN (".implode(',',$data['poste']).")";
            else
                $poste =  "AND uc.poste_id = ".$data['poste'][0];
        }

        if (isset($data['contrat'])) {
            $contrat =  "AND uc.contrat_id = ".$data['contrat'];
        }

        if (isset($data['contrat_duree'])) {
            $contrat_duree_join = "JOIN user_critere_type_contrat uctc ON uc.id = uctc.user_critere_id";
            if ($data['contrat_duree'] != 3) { // si tout n'est pas sélectionné
                $contrat_duree =  "AND uctc.type_contrat_id = ".$data['contrat_duree'];
            }

        }

        if (isset($data['formation_minimum'])) {
            $formation_join = "JOIN user_critere_formation ucf ON uc.id = ucf.user_critere_id";
            if ($data['formation_minimum'] != 0) { // si tout n'est pas sélectionné
                $formation =  "AND ucf.formation_id >= ".$data['formation_minimum'];
            }
        }

        if (isset($data['cursus_scolaire'])) {
            $cursus_scolaire_join = "JOIN user_critere_cursus_scolaire uccs ON uc.id = uccs.user_critere_id";
            if ($data['cursus_scolaire'] != 5) { // si tout n'est pas sélectionné
                $cursus_scolaire =  "AND uccs.cursus_scolaire_id = ".$data['cursus_scolaire'];
            }
        }

        if (isset($data['experience_minimum'])) {
            $experience_minimum =  "AND uc.experience_id >= ".$data['experience_minimum'];
        }

        if (isset($data['niveau_anglais'])) {
            $niveau_anglais =  "AND uc.niveau_anglais_id >= ".$data['niveau_anglais'];
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
            WHERE ".$formule." < uc.rayon_emploi ".
            $classification." ".
            $poste." ".
            $contrat." ".
            $contrat_duree." ".
            $formation." ".
            $niveau_anglais." ".
            $experience_minimum." ".
            $cursus_scolaire;


        $stmt = $this->entityManager->getConnection()->prepare($query);
        $stmt->execute();

        $employes = $stmt->fetchAll();

        $score = 0 ;

        foreach ($employes as $value) {
            $pointsRecommandation = (count($this->entityManager->getRepository("SosBundle:Recommandation")->findby(array("user"=>$value,'valide'=>1))))*10;
            $user = $this->entityManager->getRepository("SosBundle:User")->find($value);
            $pointsAnglais = $this->entityManager->getRepository("SosBundle:UserCritere")->findOneBy(array("user"=>$user->getId()))->getNiveauAnglais()->getPoints();
            $criters = $this->entityManager->getRepository("SosBundle:UserCritere")->findAll(array("user"=>$user->getId()));
            
            //$pointsExperience = $this->entityManager->getRepository("SosBundle:Experience")->find($criters->getExperience())->getPoints();
            //$pointsPoste = $this->entityManager->getRepository("SosBundle:PosteRecherche")->find($criters->getPoste())->getCoefficient();

           // $score = $pointsRecommandation+$pointsAnglais+($pointsExperience*$pointsPoste);
            //$user->setScore($score);
            //$this->entityManager->flush();
        }

    }




}