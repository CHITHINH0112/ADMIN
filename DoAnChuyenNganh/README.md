B1:
Đặt thư mục vào:
D:/wamp64/www/DoAnChuyenNganh
B2:
vào thư mục
C:\Windows\System32\drivers\etc\hosts
thêm 2 dòng
127.0.0.1 shopthoitrang.local
::1 shopthoitrang.local
B3:
D:/wamp64/bin/apache/apache2.4.xx/conf/extra/httpd-vhosts.conf

thêm
<VirtualHost \*:80>
DocumentRoot "D:/wamp64/www/DoAnChuyenNganh"
<Directory "D:/wamp64/www/DoAnChuyenNganh/">
Options +Indexes +FollowSymLinks +MultiViews
AllowOverride All
Require all granted
</Directory>
ServerName shopthoitrang.local
ServerAlias shopthoitrang.local
</VirtualHost>

B4:
D:/wamp64/bin/apache/apache2.4.xx/conf/httpd.conf
AllowOverride None
Đổi thành:
AllowOverride All

B5: vô cmd chạy với quyền admin
gõ lệnh :

- ipconfig /flushdns
- ping shopthoitrang.local
