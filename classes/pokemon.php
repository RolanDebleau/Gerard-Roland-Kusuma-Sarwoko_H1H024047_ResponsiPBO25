<?php
abstract class Pokemon {
    protected $name;
    protected $type;
    protected $level;
    protected $hp;
    protected $maxHp;
    protected $attack;
    protected $defense;
    protected $specialAttack;
    protected $specialDefense;
    protected $speed;
    protected $xp;
    protected $xpToNextLevel;

    public function __construct($name, $type, $level, $hp, $attack, $defense, $specialAttack, $specialDefense, $speed) {
        $this->name = $name;
        $this->type = $type;
        $this->level = $level;
        $this->hp = $hp;
        $this->maxHp = $hp;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->specialAttack = $specialAttack;
        $this->specialDefense = $specialDefense;
        $this->speed = $speed;
        $this->xp = 0;
        $this->xpToNextLevel = $this->calculateXpToNextLevel();
    }
    public function getName() { return $this->name; }
    public function getType() { return $this->type; }
    public function getLevel() { return $this->level; }
    public function getHp() { return $this->hp; }
    public function getMaxHp() { return $this->maxHp; }
    public function getAttack() { return $this->attack; }
    public function getDefense() { return $this->defense; }
    public function getSpecialAttack() { return $this->specialAttack; }
    public function getSpecialDefense() { return $this->specialDefense; }
    public function getSpeed() { return $this->speed; }
    public function getXp() { return $this->xp; }
    public function getXpToNextLevel() { return $this->xpToNextLevel; }

    protected function setLevel($level) { 
        $this->level = $level; 
        $this->xpToNextLevel = $this->calculateXpToNextLevel();
    }
    protected function setHp($hp) { 
        $this->hp = max(0, min($this->maxHp, $hp)); 
    }
    protected function setMaxHp($maxHp) { 
        $this->maxHp = $maxHp;
        if ($this->hp > $maxHp) $this->hp = $maxHp;
    }

    protected function addXp($xp) {
        $this->xp += $xp;
        $leveledUp = 0;
        
        while ($this->xp >= $this->xpToNextLevel) {
            $this->levelUp();
            $leveledUp++;
        }
        
        return $leveledUp;
    }

    protected function calculateXpToNextLevel() {
        return 50 + ($this->level * 10);
    }
    protected function levelUp() {
        $this->level++;
        $this->xp -= $this->xpToNextLevel;
        $this->xpToNextLevel = $this->calculateXpToNextLevel();
        $this->maxHp += 5;
        $this->hp = $this->maxHp;
        $this->attack += 2;
        $this->defense += 2;
        $this->specialAttack += 3;
        $this->specialDefense += 2;
        $this->speed += 2;
    }

    public function getTrainingMultiplier($trainingType) {
        return match(strtolower($trainingType)) {
            'mental focus' => 1.0,
            'speed' => 1.0,
            'defense' => 1.0,
            'attack' => 1.0,
            default => 1.0
        };
    }

    public function getBestTrainingType() {
        return "Balanced Training";
    }

    protected function applyTrainingBoost($trainingType, $intensity) {
        $boost = max(1, (int)ceil($intensity * 0.05));
        
        switch (strtolower($trainingType)) {
            case 'attack':
                $this->attack += $boost;
                break;
            case 'defense':
                $this->defense += $boost;
                break;
            case 'speed':
                $this->speed += $boost;
                break;
            default:
                $this->specialAttack += $boost;
        }
    }

    abstract public function specialMove();
    abstract public function train($trainingType, $intensity);
    abstract public function getWeaknesses();
    abstract public function getDescription();
}
?>