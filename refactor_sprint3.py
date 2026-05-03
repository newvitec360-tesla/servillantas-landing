import os

# 1. Desktop HTML -> PHP View
with open('landing/desktop/desktop.html', 'r') as f:
    desktop_html = f.read()

# Replace asset paths
desktop_html = desktop_html.replace('../assets/', '/assets/landing/')
desktop_html = desktop_html.replace('href="desktop.css"', 'href="/assets/landing/desktop.css"')

# Inject Login Button
login_btn_desktop = '<a class="btn btn-glass" href="/login" style="margin-right:15px; border-color: rgba(255,255,255,0.3);">🔑 Acceso CRM</a>\n      <a class="btn btn-red header-cta"'
desktop_html = desktop_html.replace('<a class="btn btn-red header-cta"', login_btn_desktop)

with open('app/Views/desktop/landing/index.php', 'w') as f:
    f.write(desktop_html)


# 2. Mobile HTML -> PHP View
with open('landing/mobile/mobile.html', 'r') as f:
    mobile_html = f.read()

# Replace asset paths
mobile_html = mobile_html.replace('../assets/', '/assets/landing/')
mobile_html = mobile_html.replace('href="mobile.css"', 'href="/assets/landing/mobile.css"')

# Inject Login Button
login_btn_mobile = '<a href="/login">🔑 Acceso CRM</a>\n      <a href="#contacto">Contacto</a>'
mobile_html = mobile_html.replace('<a href="#contacto">Contacto</a>', login_btn_mobile)

with open('app/Views/mobile/landing/index.php', 'w') as f:
    f.write(mobile_html)

print("HTML Refactored and saved as Views")
