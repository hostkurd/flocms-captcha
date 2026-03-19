# FloCMS Captcha

A lightweight, framework-friendly image captcha package for **FloCMS**.  
Designed for secure guest form verification with minimal dependencies.

---

## ✨ Features

- Simple image-based captcha (GD powered)
- Fully customizable (size, fonts, characters, noise, etc.)
- Session-based verification
- Framework-agnostic (works outside FloCMS too)
- PSR-4 autoloaded
- Extensible renderer system (future support for other drivers)

---

## 📦 Installation

Install via Composer:

```bash
composer require hostkurd/flocms-captcha
```

### Requirements

- PHP >= 8.1
- GD extension enabled (`ext-gd`)

---

## 🚀 Quick Start

### 1. Create captcha instance

```php
use FloCMS\Captcha\CaptchaManager;

$captcha = new CaptchaManager();
```

---

### 2. Output captcha image

Create a simple endpoint:

```php
// public/captcha.php

require dirname(__DIR__) . '/vendor/autoload.php';

use FloCMS\Captcha\CaptchaManager;

$captcha = new CaptchaManager();
$captcha->output();
```

---

### 3. Display in form

```html
<img src="/captcha.php" alt="Captcha">
<input type="text" name="captcha" required>
```

---

### 4. Verify input

```php
use FloCMS\Captcha\CaptchaManager;

$captcha = new CaptchaManager();

if (!$captcha->verify($_POST['captcha'] ?? null)) {
    die('Invalid captcha');
}
```

---

### 5. Clear after success

```php
$captcha->clear();
```

---

## ⚙️ Configuration

You can override default settings by passing a config array:

```php
$captcha = new CaptchaManager([
    'length' => 5,
    'width' => 200,
    'height' => 60,
]);
```

---

### Default Configuration

```php
return [
    'length' => 6,
    'width' => 180,
    'height' => 50,
    'font_size' => 24,
    'angle_min' => -15,
    'angle_max' => 15,
    'noise_lines' => 8,
    'session_key' => 'flocms_captcha',
    'characters' => 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789',
    'case_sensitive' => false,
    'fonts' => [
        __DIR__ . '/../public/fonts/Acme.ttf',
        __DIR__ . '/../public/fonts/PlayfairDisplay.ttf',
        __DIR__ . '/../public/fonts/Ubuntu.ttf',
    ],
];
```

---

## 🧠 How It Works

1. A random captcha string is generated  
2. The string is stored in session  
3. An image is rendered using GD  
4. User submits input  
5. Input is compared securely using `hash_equals`  

---

## 🔐 Security Notes

- Uses `random_int()` for cryptographically secure randomness  
- Uses `hash_equals()` to prevent timing attacks  
- Case-insensitive comparison by default (configurable)  
- Always use **CSRF protection alongside captcha**  

---

## 🧩 Architecture

```
FloCMS\Captcha\
├── Captcha.php          // Logic (generate, store, verify)
├── CaptchaManager.php   // Facade / main API
├── Renderer\
│   ├── RendererInterface.php
│   └── GdRenderer.php   // Image rendering (GD)
```

---

## 🔄 Session Handling

By default, the package uses native PHP sessions:

```php
$_SESSION['flocms_captcha']
```

Session is automatically started if not already active.

---

## 📁 Fonts

Fonts are included inside the package:

```
public/fonts/
```

You can replace or extend them:

```php
$captcha = new CaptchaManager([
    'fonts' => [
        '/path/to/custom/font1.ttf',
        '/path/to/custom/font2.ttf',
    ]
]);
```

---

## 🔌 Extending (Custom Renderer)

You can create your own renderer by implementing:

```php
FloCMS\Captcha\Renderer\RendererInterface
```

Example:

```php
use FloCMS\Captcha\Renderer\RendererInterface;

class CustomRenderer implements RendererInterface
{
    public function render(string $text): void
    {
        // your rendering logic
    }
}
```

Then inject it:

```php
$captcha = new CaptchaManager([], new CustomRenderer());
```

---

## 🧪 Example

Run the included example:

```bash
php -S localhost:8000 -t examples
```

Then open:

```
http://localhost:8000/basic.php
```

---

## 🏗️ Integration with FloCMS

Recommended usage:

- Keep captcha as a **separate package**
- Use alongside:
  - `flocms-core` (Session, CSRF, Validation)
- Expose via route or `public/captcha.php`

---

## ❌ What NOT to Do

Avoid placing captcha inside:

```
public/themes/default/
```

Captcha is **application logic**, not a theme asset.

---

## 🛣️ Roadmap

- [ ] Session adapter (FloCMS Session integration)
- [ ] Multiple captcha drivers (SVG, math captcha, etc.)
- [ ] API-based captcha (future support)
- [ ] Rate limiting / anti-bruteforce
- [ ] Audio captcha support

---

## 🤝 Contributing

Contributions are welcome.

- Fork the repository
- Create a feature branch
- Submit a pull request

---

## 📄 License

MIT License

---

## 👤 Author

**HostKurd**

---

## 💡 Final Note

This package is intentionally lightweight and modular.  
Use it as a drop-in captcha solution or extend it to fit your framework needs.