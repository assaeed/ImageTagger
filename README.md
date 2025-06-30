
<h1 align="center">ImageTagger 🖼️</h1>

<p align="center">
  تطبيق ويب متعدد اللغات لتحليل الصور باستخدام الذكاء الصناعي عبر PHP وPython.<br>
  يدعم اللغة العربية والإنجليزية.
</p>

---

## 💡 فكرة المشروع
يتيح هذا المشروع للمستخدم رفع صورة، ثم تحليل محتواها تلقائيًا باستخدام نموذج ذكاء صناعي (ResNet18) عبر سكربت بايثون، مع عرض الوسوم المقترحة بشكل مباشر.

---

## 🚀 الميزات
- واجهة بسيطة تدعم العربية والإنجليزية
- رفع صور وتحليلها فورًا باستخدام الذكاء الصناعي
- استخدام PyTorch وResNet18 للتعرف على محتوى الصور
- حفظ الصور والنتائج في قاعدة بيانات MySQL
- واجهة لعرض آخر الصور والوسوم المقترحة

---

## 🛠️ التقنيات المستخدمة
- `PHP` و `PDO` – لواجهة المستخدم ومعالجة البيانات
- `Python` – لتحليل الصور باستخدام PyTorch
- `MySQL` – لتخزين البيانات
- `HTML / CSS` – لتنسيق الواجهات

---

## 📁 هيكل المشروع

```
ImageTagger/
├── php/
│   ├── index.php          ← صفحة الرفع (عربي/إنجليزي)
│   ├── upload.php         ← رفع وتحليل الصور
│   ├── show_results.php   ← عرض الوسوم والصور المحللة
│   └── db.php             ← الاتصال بقاعدة البيانات
├── python/
│   └── tagger.py          ← سكربت بايثون لتحليل الصورة
├── uploads/               ← الصور المرفوعة
├── results/               ← مجلد اختياري لنتائج مؤقتة
├── README.md
├── .gitignore
└── LICENSE
```

---

## ⚙️ تثبيت المتطلبات

### Python
```bash
pip install torch torchvision pillow
```

### قاعدة البيانات
```sql
CREATE DATABASE imagetagger CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE imagetagger;

CREATE TABLE uploads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    tags TEXT,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🔐 جوانب الأمان
- يتم التحقق من نوع الملف ومحتواه
- حماية من رفع ملفات خبيثة عبر `mime_content_type`
- استخدام `PDO` لمنع SQL Injection
- ترميز HTML لمنع XSS
- حماية أوامر shell باستخدام `escapeshellarg`

---


-

## 📄 الرخصة
هذا المشروع مرخص باستخدام [MIT License](LICENSE)

---

## 👤 المطور
- الاسم: **Abdullah Alsaeed**
- GitHub: [@assaeed](https://github.com/assaeed)
- LinkedIn: [عبدالله السعيد](https://www.linkedin.com/in/عبدالله-السعيد-10b41b3b/)

---
