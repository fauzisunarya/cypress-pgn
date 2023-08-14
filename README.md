# CMS Service

## Overview 
CMS service digunakan untuk membuat content manajement service dimana cms berupa api


## How to Run

Employee service dapat dijalankan menggunakan docker dengan step - step berikut : 
- masuk kedalam root folder directory applikasi menggunakan terminal (contoh : `cd C:\laragon\www\cms`)
- pastikan env untuk `APP_URL=vhost/dommain` yang akan digunakan (contoh : APP_URL=https://localhost)
- kemudian jalankan perintah `docker build -t employee .` untuk melakukan build image, kemudian tunggu hingga proses build selesai seperti langkah berikut : 
![image](/uploads/51fdcbe6a8290add0c6a3e61a9bd3124/image.png)
![image](/uploads/d716014eff0a60308d9d487f5444e2fd/image.png)


- untuk menjalankan applikasi jalankan perintah `docker run -p 80:8080 -p 443:443 cms` :
![image](/uploads/b6dfc74727f8cd391eb091999a79594e/image.png)

- Kemudian akses employee menggunakan `https://localhost` pada browser
![image](/uploads/b5c53573ea2a847f3ffcfad733d08fa8/image.png)

## Note untuk setiap perubahan 
- module pada masing2 library yangdigunakan dilakukan push kedalam git
- setiap melakukan perubahan file .env maka perlu menjalankan perintah berikut ./vendor/drush/drush/drush cr

## Environment Variables 
Berikut environment variable yang digunakan oleh applikasi untuk dapat menjalankan service cms
<table>
    <tr>
        <th>DATABASE_NAME</th>
        <th>Database name / schema</th>
    </tr>
    <tr>
        <td>DATABASE_USER</td>
        <td>Database username</td>
    </tr>
    <tr>
        <td>DATABASE_PASSWORD</td>
        <td>Database password</td>
    </tr>
    <tr>
        <td>DATABASE_HOST</td>
        <td>Database host</td>
    </tr>
    <tr>
        <td>DATABASE_PORT</td>
        <td>port database</td>
    </tr>
    <tr>
        <td>DRUPAL_ROOT</td>
        <td>Default drupal folder root</td>
    </tr>
    <tr>
        <td>SERVER_ROOT</td>
        <td>Default application folder root</td>
    </tr>
    <tr>
        <td>RECAPTCHA_SITEKEY</td>
        <td>SCaptcha site key</td>
    </tr>
    <tr>
        <td>RECAPTCHA_SECRETKEY</td>
        <td>Captcha secret key</td>
    </tr>
        <td>S3_ACCESS_KEY</td>
        <td>minio access key</td>
    </tr>
    <tr>
        <td>S3_SECRET_KEY</td>
        <td>minio secret key</td>
    </tr>
    <tr>
        <td>S3_REGION</td>
        <td>minio region</td>
    </tr>
    <tr>
        <td>S3_HOSTNAME</td>
        <td>minio host</td>
    </tr>
    <tr>
        <td>APP_URL</td>
        <td>Basepath applikasi <b>Penting : </b>Isi sesuai domain / vhost yang digunakan</td>
    </tr>
    <tr>
        <td>SECRET_PUBLIC_KEY</td>
        <td>Secret jwt untuk public key</td>
    </tr>    
    <tr>
        <td>SECRET_PRIVATE_KEY</td>
        <td>Secret jwt untuk private key</td>
    </tr>
</table>