# BIT Library - Design System Documentation

## Project Overview

**Project Name:** BIT Library Management System  
**Institution:** Birgunj Institute of Technology (BIT)  
**Purpose:** Library management system for students and administrators  
**Type:** Web Application (PHP/MySQL)

---

## Brand & Style

The design is rooted in **institutional authority** and **technical excellence**. It balances traditional academic prestige with modern engineering innovation. The visual language evokes reliability, intellectual rigor, and future-readiness for prospective students and faculty.

### Style Classification
- **Style:** Corporate / Modern influenced by Minimalism
- **Layout:** Fixed Grid (12-column, 1280px max-width)
- **Spacing:** 8px base unit with generous section padding (80px)

---

## Color Palette

### Primary Colors
| Name | Hex Code | Usage |
|------|-----------|-------|
| Primary | `#001e40` | Base primary (dark navy) |
| Primary Container | `#003366` | Headers, navigation, structural elements |
| On Primary | `#ffffff` | Text on primary backgrounds |
| On Primary Container | `#799dd6` | Text on primary container |
| Inverse Primary | `#a7c8ff` | Highlights, accents |

### Secondary Colors (Accent)
| Name | Hex Code | Usage |
|------|-----------|-------|
| Secondary | `#7c5800` | Dark accent |
| Secondary Container | `#FDB913` | CTAs, buttons, highlights |
| On Secondary | `#ffffff` | Text on secondary backgrounds |
| On Secondary Container | `#6c4d00` | Text on secondary container |

### Tertiary Colors
| Name | Hex Code | Usage |
|------|-----------|-------|
| Tertiary | `#1d1f1f` | Dark neutral |
| Tertiary Container | `#323434` | Secondary backgrounds |
| On Tertiary | `#ffffff` | Text on tertiary |
| On Tertiary Container | `#9b9c9c` | Muted text |

### Surface Colors
| Name | Hex Code | Usage |
|------|-----------|-------|
| Surface | `#f9f9f9` | Main background |
| Surface Container Low | `#f3f3f3` | Card backgrounds |
| Surface Container | `#eeeeee` | Section backgrounds |
| Surface Container High | `#e8e8e8` | Elevated surfaces |
| Surface Variant | `#e2e2e2` | Secondary surfaces |

### On Surface
| Name | Hex Code | Usage |
|------|-----------|-------|
| On Surface | `#1b1b1b` | Primary text |
| On Surface Variant | `#43474f` | Secondary text |
| Outline | `#737780` | Borders |
| Outline Variant | `#c3c6d1` | Light borders |

### Error Colors
| Name | Hex Code | Usage |
|------|-----------|-------|
| Error | `#ba1a1a` | Error states |
| Error Container | `#ffdad6` | Error backgrounds |
| On Error | `#ffffff` | Text on error |
| On Error Container | `#93000a` | Text on error container |

---

## Typography

### Font Families
- **Headings & Navigation:** Public Sans (official, stable, authoritative)
- **Body Text:** Lexend (designed for reading proficiency, reduces visual stress)

### Type Scale
| Style | Font | Size | Weight | Line Height |
|-------|------|------|--------|-------------|
| Display LG | Public Sans | 56px | 700 | 1.1 |
| Headline XL | Public Sans | 40px | 700 | 1.2 |
| Headline LG | Public Sans | 32px | 600 | 1.3 |
| Headline MD | Public Sans | 24px | 600 | 1.4 |
| Body LG | Lexend | 18px | 400 | 1.6 |
| Body MD | Lexend | 16px | 400 | 1.6 |
| Label MD | Public Sans | 14px | 600 | 1.2 |
| Caption | Lexend | 12px | 400 | 1.4 |

---

## Spacing System

| Token | Value |
|-------|-------|
| `--space-base` | 8px |
| `--space-section` | 80px |
| `--space-gutter` | 24px |
| `--space-max-width` | 1280px |

---

## Border Radius

| Token | Value |
|-------|-------|
| `--radius-sm` | 2px (0.125rem) |
| `--radius-default` | 4px (0.25rem) |
| `--radius-md` | 6px (0.375rem) |
| `--radius-lg` | 8px (0.5rem) |
| `--radius-xl` | 12px (0.75rem) |
| `--radius-full` | 9999px |

---

## Components

### Academic Cards
- **Background:** White (`#ffffff`)
- **Border:** 1px solid `--outline-variant`
- **Top Accent:** 4px solid Primary Container (`#003366`)
- **Title:** Public Sans Bold
- **Description:** Lexend regular
- **Link:** Secondary orange (`#FDB913`)

### Faculty Profiles
- **Layout:** Grid (auto-fill, min 280px)
- **Image:** 120x120px, rounded default
- **Credentials:** Label MD, uppercase, letter-spacing 0.05em

### Buttons & CTAs
- **Primary:** Secondary Container (`#FDB913`) with white text, 8px lift on hover
- **Secondary:** Transparent with Primary Container border

### Input Fields
- **Border:** 1px solid `--outline-variant`
- **Focus:** Primary Container border with 3px shadow
- **Label:** Label MD typography

### Chips & Tags
- **Background:** Primary Fixed (`#d5e3ff`)
- **Text:** On Primary Fixed (`#001b3c`)
- **Border Radius:** Full (pill shape)

---

## Elevation & Depth

The system uses **low-contrast outlines** and **tonal layers** instead of heavy shadows:

- **Surface Levels:** White primary, Light gray (`#F5F5F5`) for secondary
- **Borders:** 1px solid in muted primary or soft gray
- **Interactive Depth:** Primary CTA buttons get soft 8px lift shadow on hover

---

## Layout Structure

### Grid System
- 12-column grid
- 1280px max-width
- 24px gutters

### Responsive Breakpoints
- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

---

## File Structure

```
bit/
├── css/
│   ├── design-system.css    # Core design tokens
│   ├── modern.css          # Main application styles
│   └── style.css           # Legacy styles
├── includes/
│   ├── header.php          # Site header with nav
│   └── footer.php          # Site footer
├── pages/
│   ├── books.php           # Book catalog
│   ├── login.php           # User login
│   ├── register.php        # User registration
│   ├── profile.php         # User profile
│   ├── dashboard.php       # User dashboard
│   ├── rent_book.php       # Book rental
│   ├── return.php          # Book return
│   ├── latest.php          # Latest arrivals
│   └── contact.php         # Contact page
├── admin/
│   ├── dashboard.php        # Admin dashboard
│   ├── books/              # Book management
│   ├── users/              # User management
│   └── payments.php       # Payment management
└── assets/
    ├── images/             # Static images
    └── uploads/            # User uploads
```

---

## Theme Support

The application supports **light and dark themes**:

### Dark Theme (Default)
- `--bg-dark`: `#0f172a` (Deep Slate Blue)
- `--accent-blue`: `#3b82f6`
- `--card-bg`: `rgba(30, 41, 59, 0.7)`
- Glass-morphism navigation

### Light Theme
- Override via `[data-theme="light"]` attribute
- Lighter backgrounds and adjusted text colors

---

## Implementation Notes

1. Design system CSS is imported via `design-system.css` which provides CSS custom properties
2. Main application styles in `modern.css` reference these variables
3. Header includes both CSS files and Bootstrap for grid system
4. Theme toggle uses JavaScript to switch `data-theme` attribute

---

## Design Principles

1. **Legibility First:** Use Public Sans for headings, Lexend for body
2. **Visual Hierarchy:** Bold for titles, regular for body with generous line-height
3. **Whitespace:** 80px section padding to prevent information overload
4. **Accessibility:** High contrast colors, clear typography scale
5. **Consistency:** Use component classes consistently across all pages