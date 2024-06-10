# plugin-whmcs-manage-vmware
Module này cho phép bạn quản lý và tự động hóa các máy chủ ảo VMware từ WHMCS. Bạn có thể tạo, xóa, khởi động lại, dừng và khởi động máy ảo một cách dễ dàng.

# WHMCS VMware Server Module

## Giới thiệu
Module này cho phép bạn quản lý và tự động hóa các máy chủ ảo VMware từ WHMCS. Bạn có thể tạo, xóa, khởi động lại, dừng và khởi động máy ảo một cách dễ dàng.

## Yêu cầu
- WHMCS 7.x hoặc mới hơn
- PHP 8.2 hoặc mới hơn
- cURL extension cho PHP
- VMware vCenter

## Cài đặt
1. Tải module từ repository GitHub:
git clone https://github.com/hieuit095/plugin-whmcs-manage-vmware.git

Copy

2. Tải lên thư mục module vào thư mục `modules/servers/` trong cài đặt WHMCS của bạn:
cp -r whmcs-vmware-server-module /path/to/your/whmcs/modules/servers/vmware

less
Copy

3. Đảm bảo rằng các file và thư mục có quyền truy cập phù hợp cho web server.

## Cấu hình
1. Đăng nhập vào WHMCS Admin.
2. Đi tới `Setup` > `Products/Services` > `Servers`.
3. Thêm một server mới và chọn `vmware` làm module.
4. Cung cấp các thông tin sau trong phần cấu hình server:
- `Url`: URL của vCenter.
- `User`: Tên người dùng vCenter.
- `Password`: Mật khẩu của người dùng vCenter.

5. Trong phần `Products/Services`, tạo hoặc chỉnh sửa một sản phẩm và chọn module `vmware`.

6. Thiết lập các trường tùy chỉnh cho sản phẩm (custom fields):
- `VMIP`: Địa chỉ IP của máy ảo.
- `VMName`: Tên của máy ảo (tùy chọn).
- `vCPU`: Số lượng vCPU (tùy chọn).
- `RAM`: Dung lượng RAM (MB) (tùy chọn).
- `Disk`: Dung lượng ổ đĩa (GB) (tùy chọn).

## Sử dụng
1. **Tạo máy ảo mới**:
- Khi một sản phẩm mới được tạo trong WHMCS, máy ảo mới sẽ được tạo dựa trên cấu hình đã thiết lập trong các trường tùy chỉnh.

2. **Xóa máy ảo**:
- Khi một tài khoản bị xóa trong WHMCS, máy ảo tương ứng cũng sẽ bị xóa.

3. **Khởi động lại, dừng và khởi động máy ảo**:
- Bạn có thể quản lý các hoạt động này từ trang quản trị dịch vụ trong WHMCS.

## Hỗ trợ
Nếu bạn gặp vấn đề hoặc có câu hỏi, vui lòng mở một issue trên GitHub repository.
