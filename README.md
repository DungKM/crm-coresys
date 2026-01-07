Dưới đây là mã nguồn Markdown hoàn chỉnh cho file `README.md` của bạn. Tôi đã tích hợp thông tin kỹ thuật bạn vừa cung cấp vào một giao diện chuyên nghiệp, có đầy đủ các huy hiệu (badges), bảng biểu và sơ đồ luồng dữ liệu (mermaid).

Bạn chỉ cần copy toàn bộ đoạn code dưới đây và dán vào file `README.md` của mình:

---

```markdown
# 🛡️ CRM CoreSys - Enterprise Management System

![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-777bb4.svg?style=for-the-badge&logo=php)
![Laravel Version](https://img.shields.io/badge/laravel-10.x-ff2d20.svg?style=for-the-badge&logo=laravel)
![Node Version](https://img.shields.io/badge/node-%3E%3D%2018.x-339933.svg?style=for-the-badge&logo=nodedotjs)
![License](https://img.shields.io/badge/license-MIT-green.svg?style=for-the-badge)

**CRM CoreSys** là hệ thống quản trị khách hàng chuyên sâu, được xây dựng dựa trên lõi Krayin CRM, tích hợp các giải pháp tự động hóa và kết nối đa kênh (Facebook, Instagram, WhatsApp).

---

## 📋 Yêu cầu hệ thống (Requirements)

Hệ thống yêu cầu môi trường vận hành tiêu chuẩn để đảm bảo hiệu suất tốt nhất:

| Thành phần | Yêu cầu tối thiểu |
| :--- | :--- |
| **Server** | Apache 2 hoặc NGINX |
| **RAM** | 3 GB hoặc cao hơn |
| **PHP** | 8.1 hoặc cao hơn |
| **MySQL** | 5.7.23 hoặc cao hơn |
| **MariaDB** | 10.2.7 hoặc cao hơn |
| **Node.js** | 8.11.3 LTS hoặc cao hơn |
| **Composer** | 2.5 hoặc cao hơn |

---

## 🚀 Cài đặt & Cấu hình (Installation)

### 1. Khởi tạo dự án
Chạy lệnh sau để tạo project và cài đặt các thư viện phụ thuộc:
```bash
composer create-project

```

### 2. Thiết lập môi trường

* Tìm file `.env` tại thư mục gốc.
* Cập nhật tham số `APP_URL` thành tên miền của bạn (ví dụ: `APP_URL=https://coresyscompany.com`).
* Cấu hình các thông số **Database** và **Mail** để hệ thống có thể gửi thông báo.

### 3. Cài đặt lõi CRM

Chạy lệnh artisan để thiết lập cơ sở dữ liệu và các thành phần cốt lõi:

```bash
php artisan krayin-crm:install

```

---

## ⚙️ Chế độ vận hành

### 🌐 Trên Server (Production)

> **⚠️ Cảnh báo:** Trước khi đưa lên môi trường thực tế, hãy gỡ bỏ các thư viện dành cho nhà phát triển để tối ưu bảo mật.

```bash
composer install --no-dev --optimize-autoloader

```

*Lưu ý: Đảm bảo bạn đã cấu hình Entry Point trỏ vào thư mục `/public` trong file cấu hình hosts của server.*

### 💻 Dưới máy cục bộ (Local Development)

Dành cho việc chỉnh sửa và phát triển tính năng mới:

```bash
# Xóa cache và khởi chạy server
php artisan route:clear
php artisan serve

# Cài đặt và build giao diện Admin
cd packages/Webkul/Admin/
npm install
npm run dev

```

---

## 🔐 Thông tin đăng nhập mặc định

Sau khi cài đặt thành công, truy cập trang quản trị tại:

🔗 **URL:** `http(s)://your-domain.com/admin/login`

| Trường | Thông tin mặc định |
| --- | --- |
| **Email** | `admin@example.com` |
| **Mật khẩu** | `admin123` |

---

## 🧪 Khởi tạo dữ liệu mẫu (Fake Data)

Để thử nghiệm tính năng với dữ liệu giả lập, hãy sử dụng **Artisan Tinker**:

```bash
php artisan tinker

```

Sau đó copy và dán các dòng lệnh sau:

```php
// Tạo 20 sản phẩm
\Webkul\Product\Models\Product::factory()->count(20)->create();

// Tạo 10 tổ chức
\Webkul\Contact\Models\Organization::factory()->count(10)->create();

// Tạo 20 cá nhân (khách hàng)
\Webkul\Contact\Models\Person::factory()->count(20)->create();

// Tạo 10 báo giá (Quotes)
\Webkul\Quote\Models\Quote::factory()->count(10)->create();

```


