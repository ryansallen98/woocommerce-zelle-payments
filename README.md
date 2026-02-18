# WooCommerce Zelle Payments

Accept payments via Zelle in your WooCommerce store. This plugin adds Zelle as a manual payment method at checkout, allowing customers to complete payment by sending money through their bank’s Zelle service.

> ⚠️ This plugin does not integrate with Zelle’s API (Zelle does not offer a public merchant API). Payments must be verified manually by the store owner.

## ✨ Features

- Adds Zelle as a payment option in WooCommerce checkout
- Customizable payment instructions shown to customers
- Lightweight and simple (no external SDKs or APIs)
- Compatible with latest WooCommerce & WordPress versions
- Admin settings for Zelle email / phone and instructions

## 📦 Installation

### Option 1: Upload ZIP (Recommended)
1. Download the plugin ZIP.
2. In WordPress admin, go to **Plugins → Add New → Upload Plugin**.
3. Upload the ZIP file and click **Install Now**.
4. Activate the plugin.

### Option 2: Manual Installation

1. Unzip the plugin.
2. Upload the folder to:

```bash
wp-content/plugins/woocommerce-zelle-payments
```

3. Activate the plugin from Plugins in WordPress.

## Setup & Configuration
1. Go to WooCommerce → Settings → Payments
2.	Enable Zelle Payments
3.	Configure:
    - Zelle email or phone number
    - Payment instructions shown to customers
    - Optional order notes

Customers will see Zelle as a payment option during checkout and receive instructions on how to send payment.