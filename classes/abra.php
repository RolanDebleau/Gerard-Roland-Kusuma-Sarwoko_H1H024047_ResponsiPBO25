<?php
require_once __DIR__ . '/Pokemon.php';

class Abra extends Pokemon {
    private $height = "0.9 m";
    private $weight = "19.5 kg";
    private $category = "Abra";
    private $abilities = ["Synchronize", "Inner Focus"];
    private $genderRatio = "♂ / ♀";
    private $pokedexNumber = 63;
    private $description = "Abra menatap sendok perak untuk memperkuat kekuatan psikokinesisnya sebelum menggunakannya. Tampaknya tidak dapat melakukan ini dengan sendok emas.";
    private $weaknesses = ["Serangga", "Hantu", "Kegelapan"];
    private $unlockedSkills = [];

    public function __construct() {
        parent::__construct("Abra", "Psychic", 5, 25, 20, 15, 105, 55, 90);
        $this->updateUnlockedSkills();
    }
    private function getAllSkillsByLevel() {
        return [
            1 => [
                'name' => 'Teleport',
                'description' => 'Menghilang dalam sekejap untuk menghindari serangan',
                'power' => 0,
                'type' => 'Psychic'
            ],
            5 => [
                'name' => 'Confusion',
                'description' => 'Menyerang musuh dengan kekuatan psikis yang membingungkan',
                'power' => 50,
                'type' => 'Psychic'
            ],
            10 => [
                'name' => 'Disable',
                'description' => 'Menonaktifkan serangan terakhir musuh',
                'power' => 0,
                'type' => 'Normal'
            ],
            15 => [
                'name' => 'Psybeam',
                'description' => 'Menembakkan sinar psikis yang dapat membingungkan musuh',
                'power' => 65,
                'type' => 'Psychic'
            ],
            16 => [
                'name' => 'Kinesis',
                'description' => 'Menggunakan kekuatan psikis untuk menurunkan akurasi musuh',
                'power' => 0,
                'type' => 'Psychic'
            ],
            20 => [
                'name' => 'Psyshock',
                'description' => 'Menyerang dengan gelombang psikis yang merusak pertahanan fisik',
                'power' => 80,
                'type' => 'Psychic'
            ],
            25 => [
                'name' => 'Recover',
                'description' => 'Memulihkan HP hingga setengah dari maksimal',
                'power' => 0,
                'type' => 'Normal'
            ],
            30 => [
                'name' => 'Psycho Cut',
                'description' => 'Mengiris musuh dengan energi psikis yang tajam',
                'power' => 70,
                'type' => 'Psychic'
            ],
            35 => [
                'name' => 'Calm Mind',
                'description' => 'Meningkatkan Special Attack dan Special Defense',
                'power' => 0,
                'type' => 'Psychic'
            ],
            36 => [
                'name' => 'Psychic',
                'description' => 'Serangan psikis yang sangat kuat dengan efek menurunkan Special Defense',
                'power' => 90,
                'type' => 'Psychic'
            ],
            40 => [
                'name' => 'Future Sight',
                'description' => 'Memprediksi masa depan untuk menyerang dengan dahsyat',
                'power' => 120,
                'type' => 'Psychic'
            ],
            45 => [
                'name' => 'Trick',
                'description' => 'Menukar item dengan musuh menggunakan kekuatan psikis',
                'power' => 0,
                'type' => 'Psychic'
            ],
            50 => [
                'name' => 'Psychic Terrain',
                'description' => 'Menciptakan medan psikis yang memperkuat serangan Psychic',
                'power' => 0,
                'type' => 'Psychic'
            ]
        ];
    }

    private function updateUnlockedSkills() {
        $this->unlockedSkills = [];
        $allSkills = $this->getAllSkillsByLevel();
        
        foreach ($allSkills as $requiredLevel => $skill) {
            if ($this->level >= $requiredLevel) {
                $this->unlockedSkills[$requiredLevel] = $skill;
            }
        }
    }

    public function getUnlockedSkills() {
        return $this->unlockedSkills;
    }

    public function getNextSkillToUnlock() {
        $allSkills = $this->getAllSkillsByLevel();
        foreach ($allSkills as $requiredLevel => $skill) {
            if ($this->level < $requiredLevel) {
                return [
                    'level' => $requiredLevel,
                    'skill' => $skill
                ];
            }
        }
        return null; 
    }

    public function checkNewSkillsUnlocked($oldLevel, $newLevel) {
        $newSkills = [];
        $allSkills = $this->getAllSkillsByLevel();
        foreach ($allSkills as $requiredLevel => $skill) {
            if ($requiredLevel > $oldLevel && $requiredLevel <= $newLevel) {
                $newSkills[] = [
                    'level' => $requiredLevel,
                    'skill' => $skill
                ];
            }
        }
        return $newSkills;
    }

    public function getTrainingMultiplier($trainingType) {
        return match(strtolower($trainingType)) {
            'mental focus' => 1.8,
            'speed' => 1.0,
            'defense' => 0.9,
            'attack' => 0.6,
            default => 1.0
        };
    }
    public function getBestTrainingType() {
        return "Mental Focus (Psychic specialization)";
    }

    protected function applyTrainingBoost($trainingType, $intensity) {
        $boost = max(1, (int)ceil($intensity * 0.05));
        $multiplier = $this->getTrainingMultiplier($trainingType);
        $finalBoost = max(1, (int)ceil($boost * $multiplier));
        switch (strtolower($trainingType)) {
            case 'mental focus':
                $this->specialAttack += $finalBoost;
                $this->specialDefense += max(1, (int)ceil($finalBoost * 0.5));
                break;
            case 'speed':
                $this->speed += $finalBoost;
                break;
            case 'defense':
                $this->defense += $finalBoost;
                break;
            case 'attack':
                $this->attack += $finalBoost;
                break;
        }
    }

    protected function calculateXpToNextLevel() {
        return 60 + ($this->level * 12);
    }
    public function specialMove() {
        return "Teleport: Abra menghilang dalam sekejap untuk menghindari serangan.";
    }
    public function getWeaknesses() {
        return $this->weaknesses;
    }
    public function getDescription() {
        return $this->description;
    }

    public function train($trainingType, $intensity) {
        $beforeLevel = $this->level;
        $beforeHp = $this->hp;
        $beforeMaxHp = $this->maxHp;
        $beforeXp = $this->xp;
        $beforeStats = [
            'attack' => $this->attack,
            'defense' => $this->defense,
            'special_attack' => $this->specialAttack,
            'special_defense' => $this->specialDefense,
            'speed' => $this->speed
        ];
        $multiplier = $this->getTrainingMultiplier($trainingType);
        $xpGain = max(1, (int)ceil($intensity * 1.5 * $multiplier));
        $levelsGained = $this->addXp($xpGain);
        $this->applyTrainingBoost($trainingType, $intensity);

        $newSkills = [];
        if ($beforeLevel !== $this->level) {
            $newSkills = $this->checkNewSkillsUnlocked($beforeLevel, $this->level);
            $this->updateUnlockedSkills();
        }
        $evolution = null;
        if ($beforeLevel < 16 && $this->level >= 16) {
            $evolution = "🎉 Abra berevolusi menjadi **Kadabra**!";
            $this->evolveToKadabra();
        } elseif ($beforeLevel < 36 && $this->level >= 36) {
            $evolution = "🌟 Kadabra berevolusi menjadi **Alakazam**!";
            $this->evolveToAlakazam();
        }

        $afterStats = [
            'attack' => $this->attack,
            'defense' => $this->defense,
            'special_attack' => $this->specialAttack,
            'special_defense' => $this->specialDefense,
            'speed' => $this->speed
        ];
        return [
            'before_level' => $beforeLevel,
            'after_level' => $this->level,
            'before_hp' => $beforeHp,
            'after_hp' => $this->hp,
            'before_max_hp' => $beforeMaxHp,
            'after_max_hp' => $this->maxHp,
            'before_xp' => $beforeXp,
            'after_xp' => $this->xp,
            'level_gain' => $levelsGained,
            'hp_gain' => $this->maxHp - $beforeMaxHp,
            'xp_gain' => $xpGain,
            'before_stats' => $beforeStats,
            'after_stats' => $afterStats,
            'evolved' => $evolution,
            'new_skills' => $newSkills, 
            'training_effectiveness' => $this->getTrainingEffectiveness($trainingType)
        ];
    }

    private function getTrainingEffectiveness($trainingType) {
        $multiplier = $this->getTrainingMultiplier($trainingType);
        if ($multiplier >= 1.5) return "Sangat Efektif! 🌟";
        if ($multiplier >= 1.0) return "Efektif ✓";
        if ($multiplier >= 0.8) return "Cukup Efektif";
        return "Kurang Efektif";
    }

    private function evolveToKadabra() {
        $this->name = "Kadabra";
        $this->height = "1.3 m";
        $this->weight = "56.5 kg";
        $this->category = "Kadabra";
        $this->description = "Kadabra memancarkan gelombang alfa khusus dari tubuhnya yang menyebabkan sakit kepala pada siapa pun di dekatnya.";
        
        $this->maxHp += 15;
        $this->hp = $this->maxHp;
        $this->attack += 15;
        $this->defense += 15;
        $this->specialAttack += 15;
        $this->specialDefense += 15;
        $this->speed += 15;
        $this->updateUnlockedSkills();
    }

    private function evolveToAlakazam() {
        $this->name = "Alakazam";
        $this->height = "1.5 m";
        $this->weight = "48.0 kg";
        $this->category = "Alakazam";
        $this->description = "Otak Alakazam terus tumbuh, menjadikannya lebih pintar dari superkomputer. IQ-nya dikatakan mencapai 5000.";
        
        $this->maxHp += 15;
        $this->hp = $this->maxHp;
        $this->attack += 15;
        $this->defense += 15;
        $this->specialAttack += 30;
        $this->specialDefense += 25;
        $this->speed += 15;
        
        $this->updateUnlockedSkills();
    }
    public function getDisplayName() {
        if ($this->level >= 36) return "Alakazam";
        if ($this->level >= 16) return "Kadabra";
        return "Abra";
    }
    public function getSpriteId() {
        if ($this->level >= 36) return 65;
        if ($this->level >= 16) return 64;
        return 63;
    }
    public function getInfo() {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'level' => $this->level,
            'hp' => $this->hp,
            'max_hp' => $this->maxHp,
            'xp' => $this->xp,
            'xp_to_next_level' => $this->xpToNextLevel
        ];
    }
    public function getDetailedStats() {
        return [
            'height' => $this->height,
            'weight' => $this->weight,
            'category' => $this->category,
            'abilities' => $this->abilities,
            'genderRatio' => $this->genderRatio,
            'weaknesses' => $this->weaknesses
        ];
    }

    public function getPokedexNumber() { return $this->pokedexNumber; }
    public function getHeight() { return $this->height; }
    public function getWeight() { return $this->weight; }
    public function getCategory() { return $this->category; }
    public function getAbilities() { return $this->abilities; }
    public function getGenderRatio() { return $this->genderRatio; }
    public function getAvailableMoves() { 
        return array_column($this->unlockedSkills, 'name'); 
    }
}
?>