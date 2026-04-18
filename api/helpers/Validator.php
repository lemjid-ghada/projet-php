<?php
/**
 * Helper pour Validation des Données
 */

class Validator {
    private $errors = [];

    /**
     * Valider un email
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valider un téléphone (format tunisien)
     */
    public static function validatePhone($phone) {
        return preg_match('/^(\+216|0)[0-9]{8}$/', $phone);
    }

    /**
     * Valider une URL
     */
    public static function validateUrl($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Valider une chaîne (longueur)
     */
    public static function validateString($string, $min = 1, $max = 255) {
        $length = strlen($string);
        return $length >= $min && $length <= $max;
    }

    /**
     * Valider un nombre (entier)
     */
    public static function validateInteger($value, $min = null, $max = null) {
        if (!is_numeric($value) || $value != intval($value)) {
            return false;
        }
        
        $intValue = intval($value);
        
        if ($min !== null && $intValue < $min) {
            return false;
        }
        
        if ($max !== null && $intValue > $max) {
            return false;
        }
        
        return true;
    }

    /**
     * Valider un nombre décimal
     */
    public static function validateDecimal($value, $min = null, $max = null) {
        if (!is_numeric($value)) {
            return false;
        }
        
        $decValue = floatval($value);
        
        if ($min !== null && $decValue < $min) {
            return false;
        }
        
        if ($max !== null && $decValue > $max) {
            return false;
        }
        
        return true;
    }

    /**
     * Valider un mot de passe (force)
     */
    public static function validatePassword($password) {
        // Au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password) !== 0;
    }

    /**
     * Valider un mot de passe faible (pour développement)
     */
    public static function validatePasswordWeak($password) {
        // Au moins 6 caractères
        return strlen($password) >= 6;
    }

    /**
     * Valider une date (format YYYY-MM-DD)
     */
    public static function validateDate($date) {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * Valider une heure (format HH:MM:SS)
     */
    public static function validateTime($time) {
        $t = \DateTime::createFromFormat('H:i:s', $time);
        return $t && $t->format('H:i:s') === $time;
    }

    /**
     * Valider une énumération
     */
    public static function validateEnum($value, $allowedValues) {
        return in_array($value, $allowedValues);
    }

    /**
     * Valider un tableau requis
     */
    public static function validateRequired($value) {
        return !empty($value) && $value !== null;
    }

    /**
     * Nettoyer une chaîne
     */
    public static function sanitizeString($string) {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Nettoyer un email
     */
    public static function sanitizeEmail($email) {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Nettoyer une URL
     */
    public static function sanitizeUrl($url) {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * Valider un ensemble de données
     */
    public function validate($data, $rules) {
        $this->errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            
            foreach ($fieldRules as $rule => $params) {
                switch ($rule) {
                    case 'required':
                        if (!self::validateRequired($value)) {
                            $this->addError($field, "Le champ {$field} est obligatoire");
                        }
                        break;
                    
                    case 'email':
                        if ($value && !self::validateEmail($value)) {
                            $this->addError($field, "L'email {$field} n'est pas valide");
                        }
                        break;
                    
                    case 'phone':
                        if ($value && !self::validatePhone($value)) {
                            $this->addError($field, "Le téléphone {$field} n'est pas valide");
                        }
                        break;
                    
                    case 'url':
                        if ($value && !self::validateUrl($value)) {
                            $this->addError($field, "L'URL {$field} n'est pas valide");
                        }
                        break;
                    
                    case 'min_length':
                        if ($value && strlen($value) < $params['length']) {
                            $this->addError($field, "Le champ {$field} doit contenir au moins {$params['length']} caractères");
                        }
                        break;
                    
                    case 'max_length':
                        if ($value && strlen($value) > $params['length']) {
                            $this->addError($field, "Le champ {$field} ne doit pas dépasser {$params['length']} caractères");
                        }
                        break;
                    
                    case 'numeric':
                        if ($value && !is_numeric($value)) {
                            $this->addError($field, "Le champ {$field} doit être numérique");
                        }
                        break;
                    
                    case 'enum':
                        if ($value && !self::validateEnum($value, $params['values'])) {
                            $this->addError($field, "Le champ {$field} contient une valeur invalide");
                        }
                        break;
                }
            }
        }
        
        return empty($this->errors);
    }

    /**
     * Ajouter une erreur
     */
    private function addError($field, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Obtenir les erreurs
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Obtenir le premier message d'erreur
     */
    public function getFirstError() {
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }
        return null;
    }
}

?>
