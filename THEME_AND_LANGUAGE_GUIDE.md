# 🌐 Global Theme & Language System - Implementation Guide

## ✅ System Overview

The entire Elixira website now supports:
1. **Dark/Light Mode Toggle** - Applied globally across all pages
2. **Language Switching** - Arabic & English with automatic direction (RTL/LTR)
3. **User Preferences** - Saved to database for logged-in users, localStorage for guests
4. **Responsive Design** - Works on all device sizes (mobile, tablet, desktop)

---

## 📋 Features Implemented

### 1. Theme System (Dark/Light Mode)

#### Components Created:
- **`resources/css/theme.css`** - Global CSS variables for all themes
- **`app/Http/Controllers/ThemeController.php`** - Theme switching logic
- **`resources/views/components/theme-toggle.blade.php`** - Theme toggle UI
- **`app/Http/Middleware/ApplyUserPreferences.php`** - Apply theme on every request

#### How It Works:
- Admin: Can switch theme using the circular button in top-right corner (🌙/☀️)
- User: Theme preference is saved to their profile
- Guest: Theme preference is saved in localStorage
- **Consistent**: Either fully dark or fully light - no mixed UI elements

#### Usage:
```bash
# Switch theme
GET /theme/{dark|light}
```

### 2. Language System (English & Arabic)

#### Components Created:
- **`resources/lang/en/app.php`** - English translations
- **`resources/lang/ar/app.php`** - Arabic translations
- **`resources/views/components/language-selector.blade.php`** - Language selector UI
- **Updated `app/Http/Controllers/LocaleController.php`** - Language switching

#### How It Works:
- Users can switch between English (EN) and العربية (AR)
- Direction automatically changes (LTR for English, RTL for Arabic)
- Language preference is saved to user profile
- Session-based for guests
- Middleware applies locale on every request

#### Usage:
```bash
# Switch language
GET /lang/{en|ar}
```

---

## 🎯 Pages Affected

### All Pages Now Support Theme & Language:
- ✅ **Public Pages** (Home, About, Contact, Shop, etc.)
- ✅ **Admin Dashboard** (Fully dark/light mode)
- ✅ **Vendor Portal** (Theme support)
- ✅ **User Profiles** (Theme support)
- ✅ **Authentication Pages** (Login, Register)
- ✅ **Mobile** (Fully responsive)

---

## 🚀 Quick Start

### For Users:

1. **Switch Theme:**
   - Look for the circular button in top-right corner
   - 🌙 = Dark mode, ☀️ = Light mode
   - Click to toggle between dark and light

2. **Switch Language:**
   - Look for EN/AR buttons in bottom-left corner
   - Click EN for English
   - Click AR for العربية

### For Developers:

1. **Access Current Theme:**
   ```php
   // In controller
   $theme = \App\Http\Controllers\ThemeController::getCurrentTheme();
   
   // In blade
   {{ auth()->user()->theme ?? session('theme', 'dark') }}
   ```

2. **Access Current Locale:**
   ```php
   app()->getLocale() // returns 'en' or 'ar'
   ```

3. **Use Translations:**
   ```blade
   {{ trans('app.home') }}
   {{ __('app.welcome') }}
   ```

---

## 📱 Responsive Design

The system is fully responsive:

### Desktop (>1200px)
- Theme toggle in top-right
- Language selector in bottom-left
- Full sidebar navigation

### Tablet (768px - 1200px)
- Theme toggle in top-right
- Language selector visible
- Collapsible sidebar

### Mobile (<768px)
- Theme toggle in top-right
- Language selector in bottom-left
- Mobile-optimized navigation
- Touch-friendly buttons

---

## 🔧 Database Changes

A migration was created: `2026_06_08_add_theme_and_locale_to_users_table.php`

Added columns to `users` table:
- `theme` (string, default: 'dark') - User's preferred theme
- `locale` (string, default: 'en') - User's preferred language

---

## 📦 Files Modified/Created

### New Files:
```
✅ resources/css/theme.css
✅ app/Http/Controllers/ThemeController.php
✅ app/Http/Middleware/ApplyUserPreferences.php
✅ resources/views/components/theme-toggle.blade.php
✅ resources/views/components/language-selector.blade.php
✅ resources/lang/en/app.php
✅ resources/lang/ar/app.php
✅ database/migrations/2026_06_08_add_theme_and_locale_to_users_table.php
```

### Modified Files:
```
✅ app/Http/Controllers/LocaleController.php
✅ routes/web.php (added theme route)
✅ bootstrap/app.php (added middleware)
✅ app/Models/User.php (added theme & locale to fillable)
✅ app/Providers/AppServiceProvider.php
✅ resources/views/layouts/app.blade.php
✅ resources/views/layouts/admin.blade.php
✅ resources/views/layouts/vendor.blade.php
✅ resources/views/layouts/guest.blade.php
✅ resources/views/layouts/framer.blade.php
```

---

## 🎨 CSS Variables

All colors use CSS variables that adapt to the theme:

```css
/* Dark Mode (default) */
--theme-bg: #1a1a1a              /* Main background */
--theme-text: #ffffff            /* Main text */
--theme-card-bg: #252525         /* Card background */
--theme-border: #404040          /* Border color */

/* Light Mode */
--theme-bg: #f8f9fa              /* Main background */
--theme-text: #212529            /* Main text */
--theme-card-bg: #ffffff         /* Card background */
--theme-border: #dee2e6          /* Border color */
```

---

## 🧪 Testing Checklist

- [x] Theme toggle works on all pages
- [x] Language selector works on all pages
- [x] Theme preference saves in database
- [x] Language preference saves in database
- [x] Responsive design works on mobile
- [x] RTL/LTR direction changes with language
- [x] CSS variables applied correctly
- [x] No mixed theme UI elements

---

## 🔐 Security Notes

- Locale validation: Only 'en' and 'ar' accepted
- Theme validation: Only 'dark' and 'light' accepted
- User preferences: Only authorized users can change their own preferences
- Middleware runs on every request for consistency

---

## 📧 Translation Keys

Common keys available in `resources/lang/{en,ar}/app.php`:

```php
'home', 'about', 'contact', 'shop', 'products', 'categories', 'brands'
'cart', 'checkout', 'profile', 'orders', 'settings', 'logout'
'success', 'error', 'warning', 'loading'
'saved_successfully', 'deleted_successfully', 'updated_successfully'
// ... and many more
```

---

## ✨ Future Enhancements

Potential improvements:
1. Add more translation keys for all text on pages
2. Create admin page to manage translations dynamically
3. Add system settings for default theme/language
4. Implement language-specific content
5. Add user preference for auto theme (follow system settings)

---

## 🆘 Troubleshooting

### Theme not changing?
1. Check if `resources/css/theme.css` is loaded
2. Clear browser cache
3. Check localStorage for 'theme' key

### Language not changing?
1. Verify route `/lang/{locale}` is accessible
2. Check session for 'locale' key
3. Verify middleware is registered in `bootstrap/app.php`

### Responsive issues?
1. Check viewport meta tag
2. Test with different screen sizes
3. Verify CSS grid/flexbox rules

---

**Last Updated:** 2026-06-08
**Version:** 3.0 (Global Theme & Language System)
