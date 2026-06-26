Berikut isi file `design.md` yang sudah disatukan dan siap dipakai. 

````markdown
# Design System & Theme Specification

Dokumen ini memuat spesifikasi visual, token desain, dan variabel tema yang digunakan dalam proyek. Gaya desain ini mengusung tema **Modern Neo-Brutalist / Clean Tech** dengan ciri khas garis batas tegas (`--border: #000000`), sudut tumpul yang konsisten (`1rem`), serta kombinasi warna kontras tinggi menggunakan warna kuning cerah sebagai identitas utama.

---

## 1. Visual Style & Concept

- **Design Directive:** Neo-Brutalisme Modern yang diperlembut. Menggunakan stroke hitam tebal untuk batas komponen, namun tetap menjaga kebersihan layout dengan latar belakang yang tenang.
- **Primary Vibe:** Energetik, berani, profesional, dan tech-oriented.
- **Layout Grid:** Berbasis kelipatan 4px (`--spacing: 0.25rem`), sangat ideal untuk flexbox dan komponen berbasis grid.

---

# 2. Design Tokens (CSS Variables)

## ☀️ Light Mode

Tema default menggunakan latar belakang cerah yang bersih dengan aksen kuning yang dominan pada elemen interaktif utama.

```css
:root {
  /* Surface & Backgrounds */
  --background: #f7f9f3;
  --card: #ffffff;
  --popover: #ffffff;
  --sidebar: #f7f9f3;
  --muted: #f0f0f0;

  /* Typography Colors */
  --foreground: #000000;
  --card-foreground: #000000;
  --popover-foreground: #000000;
  --sidebar-foreground: #000000;
  --muted-foreground: #333333;

  /* Brand Hierarchy */
  --primary: #FFED35;
  --primary-foreground: #000000;
  --sidebar-primary: #FFED35;
  --sidebar-primary-foreground: #000000;
  --ring: #FFED35;
  --sidebar-ring: #FFED35;

  /* Secondary & Accents */
  --secondary: #14b8a6;
  --secondary-foreground: #ffffff;
  --accent: #f59e0b;
  --accent-foreground: #000000;
  --sidebar-accent: #f59e0b;
  --sidebar-accent-foreground: #000000;
  --destructive: #ef4444;
  --destructive-foreground: #ffffff;

  /* Borders & Inputs */
  --border: #000000;
  --sidebar-border: #000000;
  --input: #737373;

  /* Typography */
  --font-sans: DM Sans, sans-serif;
  --font-serif: DM Sans, sans-serif;
  --font-mono: Space Mono, monospace;
  --letter-spacing: normal;

  /* Layout */
  --radius: 1rem;
  --spacing: 0.25rem;

  /* Neo Brutalist Shadows */
  --shadow-blur: 0px;
  --shadow-spread: 0px;
  --shadow-offset-x: 0px;
  --shadow-offset-y: 0px;
  --shadow-color: #1a1a1a;
  --shadow-opacity: 0.05;

  /* Charts */
  --chart-1: #FFED35;
  --chart-2: #14b8a6;
  --chart-3: #f59e0b;
  --chart-4: #ec4899;
  --chart-5: #22c55e;
}
````

---

## 🌙 Dark Mode

Saat berganti ke mode gelap, warna dasar berubah menjadi gelap pekat, namun warna kuning tetap dipertahankan sebagai warna primer elektrik agar elemen penting tetap mencolok.

```css
.dark {
  /* Surface & Backgrounds */
  --background: #000000;
  --card: #1a212b;
  --popover: #1a212b;
  --sidebar: #000000;
  --muted: #333333;

  /* Typography Colors */
  --foreground: #ffffff;
  --card-foreground: #ffffff;
  --popover-foreground: #ffffff;
  --sidebar-foreground: #ffffff;
  --muted-foreground: #cccccc;

  /* Brand Hierarchy */
  --primary: #FFED35;
  --primary-foreground: #000000;
  --sidebar-primary: #FFED35;
  --sidebar-primary-foreground: #000000;
  --ring: #FFED35;
  --sidebar-ring: #FFED35;

  /* Secondary & Accents */
  --secondary: #2dd4bf;
  --secondary-foreground: #000000;
  --accent: #fcd34d;
  --accent-foreground: #000000;
  --sidebar-accent: #fcd34d;
  --sidebar-accent-foreground: #000000;
  --destructive: #f87171;
  --destructive-foreground: #000000;

  /* Borders & Inputs */
  --border: #545454;
  --sidebar-border: #ffffff;
  --input: #ffffff;

  /* Geometry & Shadows */
  --radius: 1rem;
  --spacing: 0.25rem;
  --shadow-blur: 0px;
  --shadow-spread: 0px;
  --shadow-offset-x: 0px;
  --shadow-offset-y: 0px;
  --shadow-color: #1a1a1a;
  --shadow-opacity: 0.05;

  /* Charts */
  --chart-1: #FFED35;
  --chart-2: #2dd4bf;
  --chart-3: #fcd34d;
  --chart-4: #f472b6;
  --chart-5: #4ade80;
}
```

---

# 3. Tailwind CSS v4 Theme Mapping

```css
@theme inline {
  --color-card: var(--card);
  --color-ring: var(--ring);
  --color-input: var(--input);
  --color-muted: var(--muted);
  --color-accent: var(--accent);
  --color-border: var(--border);
  --color-radius: var(--radius);
  --color-chart-1: var(--chart-1);
  --color-chart-2: var(--chart-2);
  --color-chart-3: var(--chart-3);
  --color-chart-4: var(--chart-4);
  --color-chart-5: var(--chart-5);
  --color-popover: var(--popover);
  --color-primary: var(--primary);
  --color-sidebar: var(--sidebar);
  --color-spacing: var(--spacing);
  --color-font-mono: var(--font-mono);
  --color-font-sans: var(--font-sans);
  --color-secondary: var(--secondary);
  --color-background: var(--background);
  --color-font-serif: var(--font-serif);
  --color-foreground: var(--foreground);
  --color-destructive: var(--destructive);
  --color-shadow-blur: var(--shadow-blur);
  --color-shadow-color: var(--shadow-color);
  --color-sidebar-ring: var(--sidebar-ring);
  --color-shadow-spread: var(--shadow-spread);
  --color-letter-spacing: var(--letter-spacing);
  --color-shadow-opacity: var(--shadow-opacity);
  --color-sidebar-accent: var(--sidebar-accent);
  --color-sidebar-border: var(--sidebar-border);
  --color-card-foreground: var(--card-foreground);
  --color-shadow-offset-x: var(--shadow-offset-x);
  --color-shadow-offset-y: var(--shadow-offset-y);
  --color-sidebar-primary: var(--sidebar-primary);
  --color-muted-foreground: var(--muted-foreground);
  --color-accent-foreground: var(--accent-foreground);
  --color-popover-foreground: var(--popover-foreground);
  --color-primary-foreground: var(--primary-foreground);
  --color-sidebar-foreground: var(--sidebar-foreground);
  --color-secondary-foreground: var(--secondary-foreground);
  --color-destructive-foreground: var(--destructive-foreground);
  --color-sidebar-accent-foreground: var(--sidebar-accent-foreground);
  --color-sidebar-primary-foreground: var(--sidebar-primary-foreground);
}
```

---

# 4. Component Implementation Rules

## 🔘 Buttons & Badges

### Primary Button

Gunakan:

```html
class="bg-primary text-primary-foreground"
```

Cocok untuk tombol CTA utama seperti:

* Login
* Register
* Buy Now
* Start Project

---

### Secondary Button

Gunakan:

```html
class="bg-secondary text-secondary-foreground"
```

Dipakai untuk aksi sekunder agar hirarki visual tetap jelas.

---

## 📦 Cards & Containers

Aturan utama:

* Selalu gunakan border tegas
* Radius konsisten `1rem`
* Gunakan warna `bg-card`

Contoh:

```html
class="bg-card border border-border rounded-[--radius]"
```

---

## 🔤 Typography Rules

### Interface & Heading

Gunakan:

```html
class="font-sans"
```

Font utama:

* DM Sans

Karakter:

* modern
* bersih
* mudah dibaca
* cocok untuk dashboard & SaaS

---

### Technical / Code Area

Gunakan:

```html
class="font-mono"
```

Font:

* Space Mono

Cocok untuk:

* terminal
* code block
* statistik
* angka
* debug information

---

# 5. Design Principles

## Visual Hierarchy

Hierarchy utama:

1. Kuning (`primary`)
2. Toska (`secondary`)
3. Orange (`accent`)
4. Merah (`destructive`)

Jangan gunakan semua warna sekaligus dalam satu section. Itu bikin UI terasa berisik dan tidak fokus.

---

## Accessibility

Karena kuning terang punya luminance tinggi:

* teks di atas kuning wajib hitam
* hindari teks putih di atas primary
* border hitam membantu mempertahankan kontras

---

## Motion & Feel

Gunakan animasi cepat dan ringan:

* `transition-all`
* `duration-200`
* `hover:-translate-y-[2px]`

Hindari animasi lambat dan terlalu smooth. Itu bertabrakan dengan karakter neo-brutalist yang tegas.

---

# 6. Recommended Stack

Framework & tools yang cocok dengan design system ini:

* Tailwind CSS v4
* shadcn/ui
* React / Next.js
* Framer Motion
* Lucide Icons

---

# 7. Final Identity Summary

Design system ini punya karakter:

* modern
* tech startup vibe
* playful tapi tetap profesional
* high contrast
* strong visual identity
* cocok untuk dashboard, AI tools, SaaS, portfolio, atau productivity apps

Warna kuning `#FFED35` menjadi pusat identitas visual dan harus digunakan konsisten agar brand terasa kuat dan mudah dikenali.

```
```
