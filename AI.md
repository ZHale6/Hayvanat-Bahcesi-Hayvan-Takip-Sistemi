# Yapay Zeka (AI) Kullanım Logu

Bu dosya, Web Tabanlı Programlama dersi dönem projesi geliştirme sürecinde yapay zeka araçlarıyla gerçekleştirilen tüm teknik sohbetleri, sorulan soruları, alınan kod bloklarını ve karşılaşılan hataların çözümlerini kronolojik olarak listelemektedir.

---

## 💬 Soru 1: PHP ile Veritabanı Bağlantısı (PDO) Nasıl Yapılır?

**Kullanıcı:**
PHP'de bir MySQL/MariaDB veritabanına güvenli bir şekilde nasıl bağlanabilirim? Hata yönetimini nasıl yaparım?

**Yapay Zeka:**
PHP'de veritabanı bağlantısı için en güvenli ve modern yöntem **PDO (PHP Data Objects)** kullanmaktır. Hata yönetimini `try-catch` blokları ile yaparak olası bağlantı hatalarını yakalayabilirsin.

```php
<?php
$host = 'localhost';
$dbname = 'veritabanı_adı';
$username = 'kullanıcı_adı'; 
$password = 'şifre';     

try {
    // UTF-8 karakter desteğiyle PDO bağlantısı oluşturuluyor
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Hata modunu Exception olarak ayarlıyoruz
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
```

---

## 💬 Soru 2: Veritabanında Şifre Güvenliği ve Hashleme Nasıl Yapılmalı?

**Kullanıcı:**
Kullanıcı kayıt ve giriş sisteminde şifreleri veritabanına düz metin olarak kaydetmek istemiyorum. PHP'nin hash fonksiyonlarını nasıl kullanırım?

**Yapay Zeka:**
Proje güvenliği için şifreler asla düz metin (plain-text) olarak saklanmamalıdır. PHP'nin güncel `password_hash()` ve `password_verify()` fonksiyonlarını kullanmalısın.

**Kayıt Olurken (Register):**
```php
$pass = $_POST['password'];
// Şifreyi güvenli bir algoritma ile hashleme
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute([$user, $hashed_password]);
```

**Giriş Yaparken (Login):**
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$user]);
$userData = $stmt->fetch();

// Veritabanındaki hash ile girilen şifrenin doğrulanması
if ($userData && password_verify($pass, $userData['password'])) {
    // Giriş başarılı
}
```

---

## 💬 Soru 3: Çerezler Yerine Oturum (Session) Kontrolü Nasıl Yapılır?

**Kullanıcı:**
Kullanıcı giriş yaptıktan sonra sayfalar arası geçişte oturumu düz çerezler yerine Session ile nasıl kontrol edebilirim? Sayfa güvenliğini nasıl sağlarım?

**Yapay Zeka:**
Güvenli bir oturum yönetimi için her sayfanın en başında `session_start()` fonksiyonunu çağırmalı ve oturum değişkenlerini kontrol etmelisin.

**Oturum Başlatma (Giriş Başarılı Olduğunda):**
```php
session_start();
$_SESSION['user_id'] = $userData['id'];
$_SESSION['username'] = $userData['username'];
```

**Sayfa Koruma Kontrolü (Sayfaların Başında):**
```php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
```

---

## 💬 Soru 4: Hayvan Verilerini Listeleme (Read)

**Kullanıcı:**
Veritabanındaki hayvan listesini `index.php` üzerinde nasıl listelerim? Kullanıcıların girdiği verilerin sayfayı bozmaması için ne yapmalıyım?

**Yapay Zeka:**
Verileri çekerken PDO'nun `query()` veya `prepare()` metotlarını kullanabilirsin. Ekrana veri basarken XSS saldırılarını engellemek için kesinlikle `htmlspecialchars()` fonksiyonunu kullanmalısın.

```php
// Verileri Azalan Sırayla Çekme
$stmt = $pdo->query("SELECT * FROM animals ORDER BY id DESC");
$animals = $stmt->fetchAll();

// HTML İçinde Güvenli Listeleme Örneği
foreach($animals as $animal) {
    echo htmlspecialchars($animal['name']);
}
```

---

## 💬 Soru 5: Veri Ekleme (Create) İşlemi Nasıl Yapılır?

**Kullanıcı:**
`add.php` sayfasından form aracılığıyla gelen verileri, oturum açmış kullanıcının ID'si ile birlikte veritabanına nasıl kaydederim?

**Yapay Zeka:**
Formdan gelen verileri `$_POST` süper küresel değişkeni ile alıp, SQL Injection riskine karşı `prepare()` ve `execute()` mekanizmasını kullanarak güvenli bir şekilde ekleyebilirsin.

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $health = $_POST['health_status'];
    $habitat = $_POST['habitat'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO animals (name, species, health_status, habitat, added_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $species, $health, $habitat, $user_id]);
    
    header("Location: index.php");
    exit;
}
```

---

## 💬 Soru 6: Veri Güncelleme (Update) İşlemi Nasıl Yapılır?

**Kullanıcı:**
Seçilen bir hayvanın mevcut bilgilerini `edit.php` sayfasındaki formun içine doldurarak nasıl güncelleyebilirim?

**Yapay Zeka:**
Öncelikle URL üzerinden gelen `id` parametresini alarak ilgili hayvanın mevcut verilerini çekip form elementlerinin `value` niteliğine yazmalısın. Ardından POST isteğiyle veritabanında `UPDATE` sorgusu çalıştırmalısın.

```php
$id = $_GET['id'];
// Mevcut veriyi çekme
$stmt = $pdo->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch();

// Güncelleme Sorgusu
$update = $pdo->prepare("UPDATE animals SET name=?, species=?, health_status=?, habitat=? WHERE id=?");
$update->execute([$name, $species, $health, $habitat, $id]);
```

---

## 💬 Soru 7: Veri Silme (Delete) ve Güvenlik Onayı

**Kullanıcı:**
Listeleme sayfasında silme işlemini nasıl tetiklerim ve kullanıcının kazara silmesini önlemek için nasıl bir onay mekanizması kurabilirim?

**Yapay Zeka:**
Silme butonuna bir `GET` parametresi (örn: `?delete=ID`) bağlayabilir ve `index.php` sayfasının başında bu parametreyi yakalayarak silme sorgusunu çalıştırabilirsin. Kazara silmeleri önlemek için HTML tarafında `onclick` olayına küçük bir JavaScript `confirm` uyarısı ekleyebilirsin.

```php
// PHP Tarafı (index.php)
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM animals WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
    exit;
}

// HTML Tarafı
<a href="index.php?delete=<?= $animal['id'] ?>" onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?');">Sil</a>
```

---

## 💬 Soru 8: Güvenli Çıkış Yapma (Logout) İşlemi

**Kullanıcı:**
Kullanıcı çıkış yaptığında oturumunu tamamen sonlandırıp giriş sayfasına nasıl yönlendirebilirim?

**Yapay Zeka:**
Çıkış işlemi için `logout.php` dosyasında `session_start()` dedikten sonra `session_destroy()` fonksiyonu ile sunucudaki tüm oturum verilerini yok edip `header()` ile yönlendirme yapmalısın.

```php
<?php
session_start();
session_destroy();
header("Location: login.php");
exit;
?>
```

---

## 💬 Soru 9: Bootstrap 5 Arayüzünü Nasıl Özelleştirebilirim?

**Kullanıcı:**
Projemin backend kodlarına dokunmadan, Bootstrap kullanarak daha göze hitap eden, modern ve "Doğa/Doğal Yaşam" temasına uygun bir arayüz tasarımı nasıl yapabilirim?

**Yapay Zeka:**
Arayüzü güzelleştirmek için şu Bootstrap bileşenlerinden ve tasarım trendlerinden faydalanabilirsin:
1. **Emerald Green Gradyanlar:** Giriş ekranlarında `linear-gradient(135deg, #1e4620, #0f2310)` gibi tonlar kullanılarak tema desteklenebilir.
2. **Form-Floating (Yüzen Etiketler):** Girdi alanlarına `<div class="form-floating">` yapısı eklenerek daha temiz form tasarımları elde edilir.
3. **Bootstrap Icons:** Buton ve başlıklara görsel zenginlik katmak için harici ikon kütüphanesi (`bi bi-tree-fill`, `bi-trash3-fill`) entegre edilebilir.
4. **Yumuşatılmış Kartlar:** Tablolar ve formlar `card shadow-sm` ve `rounded-3` sınıfları içeren paneller içerisine alınarak modernleştirilebilir.

---

## 💬 Soru 10: Yabancı Anahtar (Foreign Key Constraint Fails) Hatası Çözümü

**Kullanıcı:**
Hayvan eklemeye çalışırken şu hatayı alıyorum, sebebi ve çözümü nedir?
`Fatal error: Uncaught PDOException: SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails...`

**Yapay Zeka:**
Bu hata bir **MySQL Yabancı Anahtar (Foreign Key) kısıtlaması ihlalidir**. `animals` tablosundaki `added_by` sütununa göndermeye çalıştığın kullanıcı ID'si (`$_SESSION['user_id']`), `users` tablosunda mevcut değil.

**Nedenleri ve Çözümü:**
1. **Eski Oturum Kalıntısı:** Lokal ortamdan (localhost) canlı sunucuya (hosting) geçişte tarayıcında eski veritabanına ait bir session kalmış olabilir. Çözüm olarak `logout.php` üzerinden oturumu kapatıp, `register.php` ile canlı veritabanında yeni bir kullanıcı oluşturup giriş yapmalısın.
2. **Veritabanı Uyuşmazlığı:** `users.id` ile `animals.added_by` sütunlarının veri tiplerinin ve uzunluklarının (örn. INT veya BIGINT) phpMyAdmin üzerinde birebir aynı olduğundan emin olmalısın.

---

## 💬 Soru 11: Projeyi GitHub'a Yüklerken Güvenlik Önlemleri

**Kullanıcı:**
Projemi GitHub reposuna yüklerken nelere dikkat etmeliyim? Veritabanı şifrelerimin açıkça görünmesini nasıl engellerim?

**Yapay Zeka:**
GitHub'a yüklenen dosyalardan hassas bilgilerin silinmesi çok önemlidir. `db.php` dosyasındaki gerçek hosting veritabanı kullanıcı adını ve şifresini repoya göndermeden önce mutlaka sansürlemeli/temizlemelisin.

```php
// db.php (GitHub'a gönderilen güvenli versiyon örneği)
$host = 'localhost';
$dbname = 'SANSÜRLENDİ';
$username = 'SANSÜRLENDİ'; 
$password = 'SANSÜRLENDİ'; 
```
```
