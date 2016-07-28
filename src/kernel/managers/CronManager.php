<?php

require_once "interfaces/AbstractManager.php";

/**
 *    This manager is charge of managing cron tasks
 */
class CronManager extends AbstractManager
{

    // -- attributes
    // --- task list
    private $tasks = null;
    // --- datetime data
    private $min = null;
    private $hour = null;
    private $day = null;
    private $month = null;
    private $day_code = null;

    // -- functions

    public function __construct(&$kernel)
    {
        // construction du parent
        parent::__construct($kernel);
        // initialisation de la liste des taches
        $this->tasks = array();
        // mise à jour des temps
        $this->min = date('i');        // AVEC zeros initiaux
        $this->hour = date('G');        // sans zeros initiaux
        $this->day = date('j');        // sans zeros initiaux
        $this->month = date('n');        // sans zeros initiaux
        $this->day_code = date('w');    // sans zeros initiaux
    }

    public function Init()
    {
        /// nothing to do here
    }

    public function RegisterTasks($tasks)
    {
        $this->tasks = $tasks;
    }

    public function RunTasks()
    {
        $scheduled = array();
        // on cherche les taches à exécuter et on les inscrit dans scheduled
        foreach ($this->tasks as $task) {
            if ($this->_should_be_executed($task)) {
                array_push($scheduled, $task);
            }
        }
        parent::kernel()->LogInfo(get_class(), "Running tasks.");
        // on exécute les tâches planifiées
        foreach ($scheduled as $task) {
            $task->Run();
        }
    }

    public function ListTasks()
    {
        echo "----------------- CRON TASKS LIST -----------------\n";
        $i = 0;
        foreach ($this->tasks as $task) {
            echo $i . " -:- " . $task->GetName() . " -:- " . $task->GetFrequency() . "\n";
            $i++;
        }
        echo "----------------- CRON TASKS LIST -----------------\n";
    }

# PROTECTED & PRIVATE ##############################################

    private function _parse_code($datePart, $code)
    {
        $ok = false;
        if (strpos($code, ',') !== false) {
            // on vérifie que la partie de date est dans la liste des parties autorisées
            $ok = in_array($datePart, explode(',', $code));
        } else if (strcmp('*', $code) === 0) {
            // on retourne vrai
            $ok = true;
        } else if (strpos($code, '-') !== false) {
            if (strpos($code, '/') !== false) {
                // on récupère la fréquence et les bornes hautes et basses de l'intervalle horaire
                $freq = end(explode('/', $code));
                $partialCode = explode('/', $code)[0];
                $startBound = explode('-', $partialCode)[0];
                $endBound = end(explode('-', $partialCode));
                // on effectue l'operation de verification
                if ((intval($datePart) - intval($startBound)) >= 0 && // on vérifie que l'heure est dans l'intervalle
                    intval($datePart) <= intval($endBound) - ((intval($endBound) - intval($startBound)) % intval($freq))
                ) {
                    // l'heure est dans l'intervalle, on vérifie que l'heure fait partie des heures qui matchent avec la frequence donnée
                    $ok = ((intval($datePart) - intval($startBound)) % intval($freq) === 0);
                } else {
                    // l'heure n'est pas dans l'intervalle, on retourne faux
                    $ok = false;
                }
            } else {
                // on récupère les bornes hautes et basses de l'intervalle horaire
                $startBound = explode('-', $code)[0];
                $endBound = end(explode('-', $code));
                // on effectue l'operation de verification
                $ok = (intval($datePart) >= intval($startBound) && intval($datePart) <= intval($endBound));
            }
        } else {
            $ok = (intval($datePart) === intval($code));
        }
        return $ok;
    }

    private function _check_minute($minute_code)
    {
        return $this->_parse_code($this->min, $minute_code);
    }

    private function _check_hour($hour_code)
    {
        return $this->_parse_code($this->hour, $hour_code);
    }

    private function _check_day($day_code)
    {
        return $this->_parse_code($this->day, $day_code);
    }

    private function _check_month($month_code)
    {
        return $this->_parse_code($this->month, $month_code);
    }

    private function _check_day_code($daycode_code)
    {
        return $this->_parse_code($this->day_code, $daycode_code);
    }

    private function _should_be_executed($task)
    {
        $ok = false;
        // on extrait les champs de la frequence
        $codes = explode(' ', $task->GetFrequency());
        // on verifie que les 5 champs sont bien présents
        if (sizeof($codes) === 5) {
            // on verifie que la tache doit être executée
            $ok = (
                $this->_check_minute($codes[0]) &&
                $this->_check_hour($codes[1]) &&
                $this->_check_day($codes[2]) &&
                $this->_check_month($codes[3]) &&
                $this->_check_day_code($codes[4])
            );
        }
        // on retourne vrai si la tache doit être exécutée. Faux sinon.
        return $ok;
    }

}

// DOC

//
//  Format pour frequence (format crontab simplifié) : 
//
//      m h d M D
//
// chaque champs peut avoir la forme "digit", "digit,digit,...", "digit-digit", "*", "digit-digit/freq"
//
//
//  ATTENTION : 
//      + les minutes doivent présenter les zeros initiaux, i.e. 7 ème minute <=> 07 et non 7 seul
//      + les jours de la semaine vont de 0 (dimanche) à 6 (lundi) 
//