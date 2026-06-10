# 🌳 Hale'nin Hayvanat Bahçesi Takip Sistemi

Bu proje, **Web Tabanlı Programlama** dersi kapsamında tamamen yalın (Pure) PHP ve MySQL/MariaDB kullanılarak geliştirilmiş, modern arayüze sahip bir **Hayvanat Bahçesi Hayvan Takip Sistemi** uygulamasıdır. 

Sistem; barınaktaki hayvanların sağlık durumlarını, türlerini, yaşam alanlarını güvenli bir şekilde izlemek, kaydetmek ve yönetmek amacıyla tasarlanmıştır.

---

## 🚀 Özellikler

### 🔐 Kullanıcı & Güvenlik İşlemleri
* **Güvenli Kayıt Sistemi (`register.php`):** Kullanıcı şifreleri veritabanına düz metin olarak değil, PHP'nin `password_hash()` fonksiyonu kullanılarak güvenli bir şekilde hash'lenerek kaydedilir.
* **Oturum Yönetimi (`login.php` & `logout.php`):** Sayfa geçişleri ve yetkilendirme kontrolleri düz çerezler (cookies) yerine tamamen güvenli **PHP Sessions** ile sağlanır. Giriş yapmamış kullanıcılar doğrudan login sayfasına yönlendirilir.

### 🐾 Hayvan Envanter Yönetimi (CRUD)
* **Veri Ekleme (Create - `add.php`):** Sisteme giriş yapan personel, yeni bir hayvanın ismini, türünü, anlık sağlık durumunu ve kaldığı kafes/bölge bilgisini sisteme ekleyebilir.
* **Veri Listeleme (Read - `index.php`):** Kayıtlı tüm hayvanlar, eklenme sırasına göre (en yeni en üstte olacak şekilde) modern bir tabloda XSS korumalı (`htmlspecialchars`) olarak listelenir.
* **Veri Güncelleme (Update - `edit.php`):** Mevcut hayvanların durum değişiklikleri (örn: sağlık durumu güncellemesi) form üzerinde eski veriler otomatik getirilerek kolayca güncellenebilir.
* **Veri Silme (Delete - `index.php`):** Süresi dolan veya taşınan kayıtlar, kazara silmeleri önlemek adına JavaScript onay (`confirm`) mekanizması eşliğinde sistemden tamamen kaldırılabilir.

---

## 🛠️ Kullanılan Teknolojiler

* **Arka Uç (Backend):** Yalın PHP (Pure PHP - Herhangi bir kütüphane veya framework kullanılmamıştır)
* **Veritabanı (Database):** MySQL / MariaDB (PDO sürücüsü ile güvenli prepared statements bağlantısı)
* **Ön Uç (Frontend):** HTML5, Bootstrap 5.3 (Modern responsive tasarım ve Floating Labels yapısı)
* **Görsel Nesneler:** Bootstrap Icons

---

## 📊 Veritabanı Tasarımı

Proje ilişkisel bir veritabanı yapısına sahip olup en az 2 tablonun birbirine yabancı anahtar (Foreign Key) ile bağlanması esasına dayanır:

1. **`users` Tablosu:** Kullanıcı kimlik bilgilerini ve şifre hashlerini tutar.
2. **`animals` Tablosu:** Hayvan detaylarını barındırır. `added_by` sütunu üzerinden `users(id)` alanına `ON DELETE CASCADE` kısıtlaması ile bağlıdır.

---

## 📸 Ekran Görüntüleri

### 1. Giriş Ekranı (`login.php`)
Modern doğa temalı gradyan arka planı ve Bootstrap bileşenleriyle tasarlanmış güvenli giriş paneli.
![Giriş Ekranı](login.jpg)

### 2. Yönetim Paneli & Listeleme (`index.php`)
Barınaktaki canlıların listelendiği, işlem butonlarının ve durum rozetlerinin (badges) yer aldığı ana dashboard.
![Yönetim Paneli](dashboard.png)

### 3. Kayıt Güncelleme Ekranı (`edit.php`)
Mevcut kayıtların `form-floating` yapısıyla kolayca düzenlenebildiği güncelleme arayüzü.
![Kayıt Güncelleme](edit.png)

---

## 🎥 Proje Tanıtım Videosu

Uygulamanın canlı ortamda (hosting üzerinde) test edildiği, tüm fonksiyonlarının (Kayıt, Giriş, Ekleme, Listeleme, Güncelleme, Silme) eksiksiz çalıştığını gösteren 1-3 dakikalık sistem tanıtım videosuna aşağıdaki bağlantıdan erişebilirsiniz:

🔗 [Hale'nin Hayvanat Bahçesi Takip Sistemi - Proje Tanıtım Videosu](https://drive.google.com/file/d/1qaY2di_OGDlz4-bob-16KTVRDS8nSOud/view?usp=sharing)
