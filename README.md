PokéCare Simulator - Responsi PBO 2025
Data Diri
- Nama          : GERARD ROLAND KUSUMA SARWOKO
- NIM           : H1H024047
- Shift Awal    : C
- Shift Akhir   : C
- Pokémon       : Abra (Psychic Type)

Tentang Aplikasi
PokéCare Simulator adalah sistem simulasi berbasis web untuk Pokémon Research & Training Center (PRTC). Aplikasi ini memungkinkan trainer untuk:
- Mengelola dan melatih Pokémon Abra
- Melakukan berbagai jenis pelatihan dengan intensitas berbeda
- Melihat perkembangan statistik Pokémon secara real-time
- Mencatat riwayat lengkap semua sesi pelatihan
- Menyaksikan evolusi Pokémon (Abra → Kadabra → Alakazam)
- Kustomisasi tema dan tampilan aplikasi

Teknologi yang Digunakan
- Backend: PHP 8.1.10 (cli) (built: Aug 30 2022 18:05:49) (ZTS Visual C++ 2019 x64) (Native, tanpa framework)
- Frontend: HTML5, CSS3, Tailwind CSS
- Session Management: PHP Session
- Font: Press Start 2P (Retro Gaming Style)

Penerapan 4 Pilar OOP
```PHP
1. Encapsulation (Enkapsulasi) 
class Pokemon {
    protected $name;      // Data disembunyikan
    protected $level;     // Hanya bisa diakses via getter/setter
    
    public function getName() { return $this->name; }
    protected function setLevel($level) { $this->level = $level; }
}
Penjelasan: Semua property dibuat `protected` dan hanya bisa diakses melalui method getter/setter untuk keamanan data.
2. Inheritance (Pewarisan) 
abstract class Pokemon { /* Base class */ }
class Abra extends Pokemon { 
    // Mewarisi semua property & method dari Pokemon
}
Penjelasan: Class `Abra` mewarisi semua karakteristik dasar dari class `Pokemon`.
3. Abstraction (Abstraksi)
abstract class Pokemon {
    abstract public function specialMove();
    abstract public function train($trainingType, $intensity);
}
class Abra extends Pokemon {
    // Wajib implement method abstract
    public function specialMove() { 
        return "Teleport: ..."; 
    }
}
Penjelasan: Method abstract memaksa setiap child class untuk mengimplementasikan behavior spesifik mereka.
4. Polymorphism (Polimorfisme) 
// Parent class
class Pokemon {
    public function getTrainingMultiplier($type) {
        return 1.0; // Default multiplier
    }
}
// Child class - OVERRIDE method dengan behavior berbeda
class Abra extends Pokemon {
    public function getTrainingMultiplier($type) {
        // Psychic type lebih efektif dengan Mental Focus
        return match($type) {
            'mental focus' => 1.8,  // Sangat efektif!
            'speed' => 1.0,
            'defense' => 0.9,
            'attack' => 0.6,        // Kurang efektif
        };
    }
}
Penjelasan: 
- Setiap tipe Pokémon (Fire, Water, Electric, Psychic) akan punya `getTrainingMultiplier()` yang berbeda
- Abra (Psychic) sangat efektif dengan Mental Focus (1.8x XP)
- Jika ada Pokémon Fire type, dia akan lebih efektif dengan Attack training
- Ini adalah inti dari Polymorphism: method yang sama, behavior yang berbeda

Cara Menjalankan Aplikasi
Prasyarat
- PHP 8.0 atau lebih tinggi
- Web server (Apache/Nginx) atau PHP built-in server
- Browser modern (Chrome, Firefox, Edge)
Langkah-langkah
1. Clone repository
   Terminal Vscode
   git clone https://github.com/RolanDebleau/Gerard Roland Kusuma Sarwoko_H1H024047_ResponsiPBO25.git
   cd Gerard Roland Kusuma Sarwoko_H1H024047_ResponsiPBO25
2. Jalankan PHP built-in server
   Terminal Vscode
   php -S localhost:8000
3. Buka browser
   http://localhost:8000/index.php
4. Mulai bermain!
   - Lihat informasi Abra di halaman beranda
   - Klik "Latihan" untuk melatih Pokémon
   - Pilih jenis latihan dan intensitas
   - Lihat perkembangan di "Riwayat" dan "Analisa"
   - Ubah tema jika dirasa bosan dengan tema saat ini

Fitur Utama
Fitur Wajib
1. Halaman Beranda
   - Menampilkan nama, tipe, level, HP Pokémon
   - Menampilkan jurus spesial
   - Statistik detail (attack, defense, speed, dll)
   - Kelemahan dan kemampuan Pokémon
2. Halaman Latihan
   - Form pilihan jenis latihan (Mental Focus, Speed, Defense, Attack)
   - Slider intensitas latihan (1-100)
   - Estimasi XP yang akan didapat
   - Output perubahan level & HP setelah latihan
   - Deskripsi jurus spesial
   - Progress bar evolusi
3. Halaman Riwayat
   - List seluruh sesi latihan
   - Detail: jenis, intensitas, level before/after, HP before/after, waktu
   - Ditampilkan dalam urutan terbaru

Fitur Bonus (Nilai Tambah)
4. Halaman Analisa
   - Grafik pertumbuhan stats (Line Chart)
   - Distribusi jenis latihan (Pie Chart)
   - Stats radar chart
   - Stats comparison bar chart
   - Quick stats overview
   - Evolution timeline
5. Halaman Tema Customisasi
   - 6 pilihan background (Forest, Ocean, Mountain, Sunset, Night, City)
   - 6 pilihan color scheme (Cyan, Purple, Green, Orange, Pink, Yellow)
   - 3 pilihan font size (Small, Normal, Large)
   - Live preview

Cara Kerja Sistem
Training System
// Contoh: Mental Focus dengan intensitas 50
$trainingType = "Mental Focus";
$intensity = 50;
// 1. Hitung multiplier (Polymorphism!)
$multiplier = $abra->getTrainingMultiplier($trainingType); // 1.8x untuk Psychic
// 2. Hitung XP gain
$xpGain = $intensity * 1.5 * $multiplier; // 50 * 1.5 * 1.8 = 135 XP
// 3. Tambah XP dan cek level up
$abra->addXp($xpGain);
// 4. Boost stats sesuai training type (Polymorphism!)
$abra->applyTrainingBoost($trainingType, $intensity);
// 5. Cek evolusi
if ($level >= 16) evolveToKadabra();
if ($level >= 36) evolveToAlakazam();
Evolution System
- Level 5-15: Abra
- Level 16-35: Kadabra (stat boost +15 semua stat)
- Level 36+: Alakazam (stat boost besar, Special Attack +30)

Penjelasan Polymorphism dalam Konteks Pokémon
Polymorphism = "Bentuk yang berbeda"
Dalam aplikasi ini:
// Setiap tipe Pokémon punya cara training yang BERBEDA
// Abra (Psychic type)
$abra->getTrainingMultiplier('mental focus'); // 1.8x (TERBAIK)
$abra->getTrainingMultiplier('attack');       // 0.6x (TERBURUK)
// Jika ada Charmander (Fire type) - contoh implementasi berbeda
$charmander->getTrainingMultiplier('attack');       // 1.8x (TERBAIK)
$charmander->getTrainingMultiplier('mental focus'); // 0.6x (TERBURUK)
// Jika ada Pikachu (Electric type) - contoh implementasi berbeda
$pikachu->getTrainingMultiplier('speed');     // 1.8x (TERBAIK)
$pikachu->getTrainingMultiplier('defense');   // 0.6x (TERBURUK)
Kesimpulan: Method yang sama (`getTrainingMultiplier`), tapi behavior berbeda tergantung tipe Pokémon. Ini adalah Polymorphism!
```

### Video Demonstrasi
![Demo](https://i.imgur.com/gT4XSHG.gif)

Lisensi :
Proyek ini dibuat untuk keperluan Responsi Praktikum PBO Teknik Komputer 2025/2026.
© 2025 - PokéCare Simulator by RolanDebleau
